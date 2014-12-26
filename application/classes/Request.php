<?php defined('SYSPATH') OR die();

class Request extends Kohana_Request
{
    
    public static function factory($uri = TRUE, $client_params = array(), $allow_external = TRUE, $injected_routes = array())
	{    
        $_COOKIE = array();
        return parent::factory($uri, $client_params, $allow_external, $injected_routes);
    }
    
}