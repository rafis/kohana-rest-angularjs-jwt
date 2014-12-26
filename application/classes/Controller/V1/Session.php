<?php defined('SYSPATH') or die();

require_once(APPPATH . '/vendor/autoload.php');

/**
 * An example controller that implements a RESTful API.
 *
 * @TODO Move all default action functions into the REST parent class.
 *
 * @package  RESTfulAPI
 * @category Controller
 * @author   Alon Pe'er
 */
class Controller_V1_Session extends Controller_Rest {

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
	public function action_index()
	{
        if ( ! $this->request->is_ajax() && Kohana::$environment !== Kohana::DEVELOPMENT )
        {
            $this->response->status(405);
            $this->response->body('');
            return;
        }
        
        Session::instance()->write();
        
//        if ( Session::instance()->get('uid') )
//        {
//            $session['uid'] = Session::instance()->get('uid');
//            $session['name'] = Session::instance()->get('name');
//            $session['email'] = Session::instance()->get('email');
//        }
//        else
//        {
//            $session['uid'] = '';
//            $session['name'] = 'Guest';
//            $session['email'] = '';
//        }
    	$this->rest_output(array(
            'id_token' => Session::instance()->getIdToken(),
        ));
	}

} // END
