<?php
namespace vendor\seek;

class Loader
{
    public static function autoload($class)
    {
        require BASEDIR.'/'.str_replace('\\', '/', $class).'.php';
    }
}