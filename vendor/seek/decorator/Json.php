<?php
namespace vendor\seek\decorator;

class Json
{
    protected $controller;

    public function beforeRequest($controller)
    {
        $this->controller = $controller;
    }

    public function afterRequest($returnValue)
    {
        if (isset($_GET['app']) && $_GET['app'] == 'json') {
            echo json_encode($returnValue);
        }
    }
}