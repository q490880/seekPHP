<?php

use vendor\seek\App;

define('BASEDIR', __DIR__);
define('DEBUG', true);

require (BASEDIR . '/vendor/autoload.php');
require (BASEDIR . '/vendor/seek/Loader.php');
App::getInstance()->start();