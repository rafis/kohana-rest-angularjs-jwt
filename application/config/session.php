<?php defined('SYSPATH') OR die('No direct script access.');

return array(

	'jwt' => array(
        'group' => 'default', // Database group
        'name' => 'id_token',
        'secret' => '12345',
        'lifetime' => 7200,
	),

);
