<?php
namespace vendor\seek\decorator;

class Template implements IDecorator
{
    protected $controller;

    public function before($controller)
    {
        $this->controller = $controller;
    }

    public function after($value)
    {
        if ($returnValue) {
            foreach ($returnValue as $key => $value) {
                $this->controller->assign($key, $value);
            }
        }
        $this->controller->display();
    }
}