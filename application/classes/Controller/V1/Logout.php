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
class Controller_V1_Logout extends Controller_Rest {

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
        if ( ! Session::instance()->get('uid') )
        {
            throw HTTP_Exception::factory(400);
        }
        Session::instance()->destroy();
        
        $response = array();
        $response['status'] = 'info';
        $response['message'] = 'Logged out successfully';
    	$this->rest_output($response);
	}

} // END
