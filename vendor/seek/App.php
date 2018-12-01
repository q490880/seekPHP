<?php
namespace vendor\seek;

use ErrorException;
use Exception;
use vendor\seek\Database\MySQL;
use vendor\seek\Database\MySQLi;
use vendor\seek\database\PDO;
use vendor\seek\decorator\Template;

class App
{
    protected static $instance;
    public $log;
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
        $this->settingDebug();
        $this->settingLog();
        $this->settingTimeZone();
        $this->requestUrl();
    }

    /*
     * 获取URL并跳转到对应的Controller
     * */
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
        $systemConfig = $this->config['system'];
        $decorators = array();
        if (isset($systemConfig['decorator']))
        {
            $confDecorator = $systemConfig['decorator'];
            if (!isset($confDecorator['response'])) {
                $decorators[] = new Template();
            } else {
                $decorators[] = new $confDecorator['response'];
            }
            if (isset($confDecorator['extend'])) {
                foreach($confDecorator['extend'] as $class)
                {
                    $decorators[] = new $class;
                }
            }
        } else {
            $decorators[] = new Template();
        }
        foreach($decorators as $decorator)
        {
            $decorator->before($controller);
        }
        $controller->beforeAction();
        $returnValue = $controller->$view();
        $controller->afterAction();
        foreach($decorators as $decorator)
        {
            $decorator->after($returnValue);
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

    // 错误捕获配置
    public function settingDebug()
    {
        if (DEBUG == true) {
            $whoops = new \Whoops\Run();
            $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());
            $whoops->register();
        } else {
            // 配置全局异常捕获
            set_exception_handler(function (Exception $e){
                print_r($e->getMessage());
            });
            // 配置全部错误捕获
            set_error_handler(function ($errno, $errstr, $errfile, $errline) {
                throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
            });
        }
    }

    /*
     * 设置日志组件
     * */
    public function settingLog()
    {
        $config = App::getInstance()->config['system'];
        if (!isset($config['logs'])) {
            return;
        }
        $this->log = new \Monolog\Logger('app');
        foreach ($config['logs'] as $logConfig) {
            if (isset($logConfig['logStatus']) && $logConfig['logStatus'] == 0) {
                continue;
            }
            $logFileName = date('Y-m-d');
            $this->log->pushHandler(new \Monolog\Handler\StreamHandler(BASEDIR . "/{$logConfig['logPath']}/{$logFileName}.log",$logConfig['level']));
        }
    }
}