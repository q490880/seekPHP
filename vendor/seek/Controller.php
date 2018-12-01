<?php
namespace vendor\seek;

abstract class Controller
{
    protected $data;
    protected $controllerName;
    protected $viewName;
    protected $templateDir;

    public function __construct($controllerName, $viewName)
    {
        $this->controllerName = $controllerName;
        $this->viewName = $viewName;
        $this->templateDir = BASEDIR.'/app/views';
    }

    public function assign($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function afterAction() {
        return true;
    }

    public function beforeAction()
    {
        return true;
    }

    public function display($file = '')
    {
        if (empty($file))
        {
            $file = strtolower($this->controllerName).'/'.$this->viewName.'.php';
        }
        $filePath = $this->templateDir. '/'. $file;
        if ($this->data) {
            extract($this->data);
        }
        include $filePath;
    }
}