<?php
namespace vendor\seek;

spl_autoload_register(function ($class){
    $filePath = BASEDIR.'/'.str_replace('\\', '/', $class).'.php';
    if (is_file($filePath)) {
        require $filePath;
    }
});