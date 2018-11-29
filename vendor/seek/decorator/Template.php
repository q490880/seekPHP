<?php
namespace vendor\seek\decorator;

class Template
{
    protected $controller;

    public function beforeRequest($controller)
    {
        $this->controller = $controller;
    }

    public function afterRequest($returnValue)
    {
        if (isset($_GET['app']) && $_GET['app'] == 'json') {
            return;
        }
        if ($returnValue) {
            foreach ($returnValue as $key => $value) {
                $this->controller->assign($key, $value);
            }
        }
        $this->controller->display();
    }
}