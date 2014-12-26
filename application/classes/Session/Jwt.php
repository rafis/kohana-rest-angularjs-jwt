<?php defined('SYSPATH') OR die();

require_once(APPPATH . '/vendor/autoload.php');

/**
 * Database-based session class.
 *
 * Sample schema:
 *
 *     CREATE TABLE  `sessions` (
 *         `session_id` VARCHAR( 24 ) NOT NULL,
 *         `last_active` INT UNSIGNED NOT NULL,
 *         `contents` TEXT NOT NULL,
 *         PRIMARY KEY ( `session_id` ),
 *         INDEX ( `last_active` )
 *     ) ENGINE = MYISAM ;
 *
 * @package    Kohana/Database
 * @category   Session
 * @author     Kohana Team
 * @copyright  (c) 2008-2009 Kohana Team
 * @license    http://kohanaphp.com/license
 */
class Session_Jwt extends Session {

	// Database instance
	protected $_db;

	// Database table name
	protected $_table = 'sessions';

	// Database column names
	protected $_columns = array(
		'session_id'  => 'session_id',
		'user_id' => 'user_id',
		'last_active' => 'last_active',
		'contents'    => 'contents'
	);

	// Garbage collection requests
	protected $_gc = 500;

	// The current session id
	protected $_session_id;

	// The old session id
	protected $_update_id;

    // Secret
    protected $_secret;
    
    // Secret
    protected $_id_token;
    
    public function __construct(array $config = NULL, $id = NULL)
	{
		if ( ! isset($config['group']))
		{
			// Use the default group
			$config['group'] = Database::$default;
		}

		// Load the database
		$this->_db = Database::instance($config['group']);

		if (isset($config['table']))
		{
			// Set the table name
			$this->_table = (string) $config['table'];
		}

		if (isset($config['gc']))
		{
			// Set the gc chance
			$this->_gc = (int) $config['gc'];
		}

		if (isset($config['columns']))
		{
			// Overload column names
			$this->_columns = $config['columns'];
		}
        
        $this->_secret = $config['secret'];

		parent::__construct($config, $id);

		if (mt_rand(0, $this->_gc) === $this->_gc)
		{
			// Run garbage collection
			// This will average out to run once every X requests
			$this->_gc();
		}
	}

	public function id()
	{
		return $this->_session_id;
	}

    /**
     * 
     * @return bool
     */
    public function loaded()
    {
        return null !== $this->id();
    }
    
	protected function _read($id = null)
	{
        if ( null === $id )
        {
            $contents = $this->parseAuthorizationHeader(Request::current()->headers('Authorization'));
            if ( null !== $contents )
            {
                return $contents;
            }
		}

		return null;
	}
    
    protected function parseAuthorizationHeader($authorization)
    {
        $parts = explode(' ', $authorization, 2);
        if ( 'bearer' != strtolower($parts[0]) || ! isset($parts[1]) )
        {
            return null;
        }

        try
        {
            $decoded = JWT::decode($parts[1], $this->_secret);
            $decoded = json_decode(json_encode($decoded), true);
        }
        catch(Exception $e)
        {
            return null;
        }

        $validation = Validation::factory($decoded)
            ->rule('ip', 'not_empty')
            ->rule('ip', 'ip')
            ->rule('ip', function(Validation $validation, $field) {
                if ( $validation[$field] != $_SERVER['REMOTE_ADDR'] )
                {
                    $validation->error($field, 'Ip-address do not match');
                }
            }, array(':validation', ':field'))
            ->rule('jti', 'not_empty')
            ->rule('jti', 'exact_length', array(':value', 40))
            ->rule('iat', 'not_empty')
            ->rule('iat', function(Validation $validation, $field) {
                if ( $validation[$field] < time() )
                {
                    $validation->error($field, 'Issued at is greater than current time');
                }
            }, array(':validation', ':field'))
            ->rule('exp', 'not_empty')
            ->rule('exp', function(Validation $validation, $field) {
                if ( $validation[$field] < time() + $this->_lifetime )
                {
                    $validation->error($field, 'JWT expired');
                }
            }, array(':validation', ':field'));

        if ( $validation->check() )
        {
            return null;
        }

        $id = $decoded['jti'];

        $result = DB::select(array($this->_columns['contents'], 'contents'))
            ->from($this->_table)
            ->where($this->_columns['session_id'], '=', ':id')
            ->limit(1)
            ->param(':id', $id)
            ->execute($this->_db);

        if ( ! $result->count() )
        {
            return null;
        }
        
        // Set the current session id
        $this->_session_id = $this->_update_id = $id;

        // Return the contents
        return $result->get('contents');
    }

	protected function _regenerate()
	{
		// Create the query to find an ID
		$query = DB::select($this->_columns['session_id'])
			->from($this->_table)
			->where($this->_columns['session_id'], '=', ':id')
			->limit(1)
			->bind(':id', $id);

		do
		{
			// Create a new session id
			$id = sha1(uniqid(null, true));

			// Get the the id from the database
			$result = $query->execute($this->_db);
		}
		while ($result->count());

		return $this->_session_id = $id;
	}

	protected function _write()
	{
        if ( null !== $this->_id_token )
        {
            return TRUE;
        }
        
        if ( null === $this->_session_id )
        {
            // Create a new session id
            $this->_regenerate();
        }
        
		if ($this->_update_id === NULL)
		{
			// Insert a new row
			$query = DB::insert($this->_table, $this->_columns)
				->values(array(':new_id', ':user_id', ':last_active', ':contents'));
		}
		else
		{
			// Update the row
			$query = DB::update($this->_table)
				->value($this->_columns['user_id'], ':user_id')
				->value($this->_columns['last_active'], ':last_active')
				->value($this->_columns['contents'], ':contents')
				->where($this->_columns['session_id'], '=', ':old_id');

			if ($this->_update_id !== $this->_session_id)
			{
				// Also update the session id
				$query->value($this->_columns['session_id'], ':new_id');
			}
		}

		$query
			->param(':new_id',   $this->_session_id)
			->param(':old_id',   $this->_update_id)
			->param(':user_id',  Arr::get($this->_data, 'user_id'))
			->param(':last_active', $this->_data['last_active'])
			->param(':contents', $this->__toString());

		// Execute the query
		$query->execute($this->_db);

		// The update and the session id are now the same
		$this->_update_id = $this->_session_id;

		// Update the cookie with the new session id
		//Cookie::set($this->_name, $this->_session_id, $this->_lifetime);
        $payload = array();
        $payload['iat'] = $this->_data['last_active'];
        $payload['exp'] = $payload['iat'] + $this->_lifetime;
        $payload['ip'] = $_SERVER['REMOTE_ADDR'];
        $payload['jti'] = $this->_session_id;
        
        $this->_id_token = JWT::encode($payload, $this->_secret);
        
		return TRUE;
	}
    
    public function getIdToken()
    {
        return $this->_id_token;
    }

	/**
	 * Encodes the session data using [base64_encode].
	 *
	 * @param   string  $data  data
	 * @return  string
	 */
	protected function _encode($data)
	{
		return $data;
	}

	/**
	 * Decodes the session data using [base64_decode].
	 *
	 * @param   string  $data  data
	 * @return  string
	 */
	protected function _decode($data)
	{
		return $data;
	}

	/**
	 * @return  bool
	 */
	protected function _restart()
	{
		$this->_regenerate();

		return TRUE;
	}

	protected function _destroy()
	{
		if ($this->_update_id === NULL)
		{
			// Session has not been created yet
			return TRUE;
		}

		// Delete the current session
		$query = DB::delete($this->_table)
			->where($this->_columns['session_id'], '=', ':id')
			->param(':id', $this->_update_id);

		try
		{
			// Execute the query
			$query->execute($this->_db);

			// Delete the cookie
			//Cookie::delete($this->_name);
		}
		catch (Exception $e)
		{
			// An error occurred, the session has not been deleted
			return FALSE;
		}

		return TRUE;
	}

	protected function _gc()
	{
		if ($this->_lifetime)
		{
			// Expire sessions when their lifetime is up
			$expires = $this->_lifetime;
		}
		else
		{
			// Expire sessions after one month
			$expires = Date::MONTH;
		}

		// Delete all sessions that have expired
		DB::delete($this->_table)
			->where($this->_columns['last_active'], '<', ':time')
			->param(':time', time() - $expires)
			->execute($this->_db);
	}

} // End Session_Database
