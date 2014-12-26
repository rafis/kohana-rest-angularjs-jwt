<?php defined('SYSPATH') or die();

/**
 * An example controller that implements a RESTful API.
 *
 * @TODO Move all default action functions into the REST parent class.
 *
 * @package  RESTfulAPI
 * @category Controller
 * @author   Alon Pe'er
 */
class Controller_V1_Login extends Controller_Rest {

	/**
	 * A Restexample model instance for all the business logic.
	 *
	 * @var Model_Restexample
	 */
	protected $_rest;

	protected $_auth_type = RestUser::AUTH_TYPE_OFF;
	protected $_auth_source = RestUser::AUTH_SOURCE_GET;

	/**
	 * Handle GET requests.
	 */
	public function action_create()
	{
        if ( ! Session::instance()->loaded() )
        {
            throw HTTP_Exception::factory(401)->headers('www-authenticate', 'None');
        }
        
        $requestUser = json_decode($this->request->body(), true);
        if ( null === $requestUser )
        {
            throw HTTP_Exception::factory(400);
        }
        $requestUser = Arr::get($requestUser, 'user', array());
        $validation = Validation::factory($requestUser)
            ->rule('username', 'not_empty')
            ->rule('password', 'not_empty');
        if ( ! $validation->check() )
        {
            throw HTTP_Exception::factory(400);
        }
        
        $user = ORM::factory('User')
            ->where('username', '=', $requestUser['username'])
            ->find();
        if ( ! $user->loaded() )
        {
            throw HTTP_Exception::factory(400);
        }
        if ( $user->password != Auth::instance()->hash($requestUser['password']) )
        {
            throw HTTP_Exception::factory(400);
        }

        Session::instance()->set('user_id', $user->id);
        Session::instance()->write();
        
        $response = array();
        $response['status'] = 'success';
        $response['message'] = 'Logged in successfully.';
        $response['id_token'] = Session::instance()->getIdToken();
        
    	$this->rest_output($response);
	}

} // END
