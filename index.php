<?php

use vendor\seek\App;

define('BASEDIR', __DIR__);
define('DEBUG', true);

require (BASEDIR . '/vendor/autoload.php');
require (BASEDIR . '/vendor/seek/Loader.php');
App::getInstance()->start();
//$a->critical('critical');
//$a->warning('warning');
//$a->alert('alert');
//$a->emerg('emerg');
//class Event extends \seek\event\EventGenerator
//{
//    public function trigger()
//    {
//        echo "Event</br>\n";
//        $this->notify();
//    }
//}
//
//class Observer1 implements \seek\event\ObServer
//{
//    public function update($eventInfo = null)
//    {
//        echo "逻辑1<br>\n";
//    }
//}
////$e = new Event();
////$e->addObserver(new Observer1());
////$e->trigger();
//
//class Action
//{
//    public $decorators = [];
//    public function afterRun()
//    {
//        $decorators = array_reverse($this->decorators);
//        foreach ($decorators as $decorator) {
//            $decorator->afterRun();
//        }
//    }
//
//    public function beforeRun()
//    {
//        foreach ($this->decorators as $decorator) {
//            $decorator->beforeRun();
//        }
//    }
//
//    public function addDecorator(\seek\IDecorator $decorator)
//    {
//        $this->decorators[] = $decorator;
//    }
//
//    public function run()
//    {
//        $this->beforeRun();
//        echo "run <br/>\n";
//        $this->afterRun();
//    }
//}
//
//class D implements \seek\IDecorator
//{
//    public function beforeRun()
//    {
//        echo "D before <br/>\n";
//    }
//
//    public function afterRun()
//    {
//        echo "D after <br/>\n";
//    }
//}
//
//class C implements \seek\IDecorator
//{
//    public function beforeRun()
//    {
//        echo "C before <br/>\n";
//    }
//
//    public function afterRun()
//    {
//        echo "C after <br/>\n";
//    }
//}
//
////$a = new Action();
////$a->addDecorator(new D);
////$a->addDecorator(new C);
////$a->run();
//
//class AllUser implements Iterator
//{
//
//    public $index;
//    public $data;
//    public function __construct()
//    {
//        $this->data = [1,2,3,4,5,6,7,8,9];
//    }
//
//    public function current()
//    {
//        $value = $this->data[$this->index];
//        return $value;
//    }
//
//    public function next()
//    {
//        $this->index++;
//    }
//
//    public function valid()
//    {
//        return $this->index < count($this->data);
//    }
//
//    public function rewind()
//    {
//        $this->index = 0;
//    }
//
//    public function key()
//    {
//        return $this->index;
//    }
//}

//$user = new AllUser();
//foreach ($user as $key => $value) {
//    print_r($value."<br/>");
//}
