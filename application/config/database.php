<?php defined('SYSPATH') OR die();

return array
(
    'default' => array
    (
        'type'       => 'MySQLi',
        'connection' => array(
                /**
                 *
                 * string   hostname     server hostname
                 * string   database     database name
                 * string   username     database username
                 * string   password     database password
                 * string   port         server port
                 * string   socket       connection socket
                 *
                 */
                'hostname'   => 'localhost',
                'database'   => 'kohana',
                'username'   => 'kohana',
                'password'   => '',
                'port'       => NULL,
                'socket'     => '/var/run/mysqld/mysqld.sock',
        ),
        'table_prefix' => '',
        'charset'      => 'utf8',
        'caching'      => FALSE,
    ),
);
