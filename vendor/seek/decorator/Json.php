<?php
namespace vendor\seek\decorator;

class Json implements IDecorator
{
    protected $controller;

    public function before($controller)
    {
        $this->controller = $controller;
    }

    public function after($value)
    {
        echo json_encode($value);
    }
}