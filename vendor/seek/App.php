<?php
namespace vendor\seek;

use vendor\seek\Database\MySQL;
use vendor\seek\Database\MySQLi;
use vendor\seek\database\PDO;

class App
{
    protected static $instance;

    public $config;

    protected function __construct()
    {
        $this->config = new Config(BASEDIR.'/configs');
    }

    public static function getInstance()
    {
        if (empty(self::$instance))
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // 获取数据库
    public function getDb($id = 'master')
    {

        $alias = 'database_'.$id;
        $db = Register::get($alias);
        if(!$db) {
            if($id == 'slave') {
                $slaves = $this->config['database']['salve'];
                $dbConf = $slaves[array_rand($slaves)];
            } else {
                $dbConf = $this->config['database']['master'];
            }
            if (!isset($dbConf['type'])) {
                $dbConf['type'] = 'PDO';
            }
            if ($dbConf['type'] == 'MySQL') {
                $db = new MySQL();
            } elseif($dbConf['type'] == 'MySQLi')  {
                $db = new MySQLi();
            } else {
                $db = new PDO();
            }
            $db->connect($dbConf['host'], $dbConf['user'], $dbConf['password'], $dbConf['dbname']);
            Register::set($alias, $db);
        }
        return $db;
    }


    public function start()
    {
        //$this->settingTimeZone();
        $this->requestUrl();
    }

    // 获取URL并跳转到对应的Controller
    public function requestUrl()
    {
        $param = isset($_GET['r']) ? $_GET['r'] : '';
        if ($param == '') {
            $config = App::getInstance()->config['system'];
            $param = isset($config['defaultController']) ? $config['defaultController'] : 'home/index';
        }
        list($controller, $view) = explode('/', trim($param, '/'));
        $controllerConfigName = strtolower($controller);
        $controllerName = ucwords($controller);
        $classPath = '\\app\\controllers\\'.$controllerName;
        $controller = new $classPath($controllerName, $view);
        $controllerConfig = $this->config['controller'];
        $decorators = array();
        if (isset($controllerConfig[$controllerConfigName]['decorator']))
        {
            $confDecorator = $controllerConfig[$controllerConfigName]['decorator'];
            foreach($confDecorator as $class)
            {
                $decorators[] = new $class;
            }
        }
        foreach($decorators as $decorator)
        {
            $decorator->beforeRequest($controller);
        }
        $return_value = $controller->$view();
        foreach($decorators as $decorator)
        {
            $decorator->afterRequest($return_value);
        }
    }


    // 设置时区
    public function settingTimeZone()
    {
        $config = App::getInstance()->config['system'];
        if (isset($config['timezone'])) {
            date_default_timezone_set($config['timezone']);
        } else{
            date_default_timezone_set('Asia/Shanghai');
        }
    }
}