<?php
$config = array(
    'master' => array(
        'type' => 'PDO',
        'host' => '127.0.0.1',
        'user' => 'root',
        'password' => 'root',
        'dbname' => 'test',
    ),
    'slave' => array(
        'slave_1' => array(
            'type' => 'PDO',
            'host' => '127.0.0.1',
            'user' => 'root',
            'password' => 'root',
            'dbname' => 'test'
        )
    ),
);

return $config;