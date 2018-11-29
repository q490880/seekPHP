<?php
namespace vendor\seek;
use ArrayAccess;

class Config implements ArrayAccess
{
    protected $path;
    protected $configs = array();

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function offsetGet($key)
    {
        if (empty($this->configs[$key])) {
            $filePath = $this->path ."/". $key .'.php';
            $config = require $filePath;
            $this->configs[$key] = $config;
        } else {
            $config = $this->configs[$key];
        }
        return $config;
    }

    public function offsetSet($key, $value)
    {
        throw new \Exception("cannot write config file.");
    }

    public function offsetExists($key)
    {
        return isset($this->configs[$key]);
    }

    public function offsetUnset($key)
    {
        unset($this->configs[$key]);
    }
}