<?php
$config = array(
    'defaultController' => 'home/index',
    'timezone' => 'Asia/Shanghai',
    'decorator' => array(
        'response' => 'vendor\seek\decorator\Json',
        'extend' => array(
            #'vendor\seek\decorator\Debug'
        ),
    ),
    'logs' => array(
        array(
            'logStatus' => 1,
            'logPath' => 'runtime/logs/error',
            'level' => \Monolog\Logger::WARNING
        ),
        array(
            'logStatus' => 1,
            'logPath' => 'runtime/logs/info',
            'level' => \Monolog\Logger::DEBUG
        ),
    )
);
return $config;