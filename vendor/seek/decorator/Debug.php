<?php
namespace vendor\seek\decorator;

class Debug implements IDecorator
{
    protected $controller;
    protected $startTime;
    protected $startMemory;
    public function before($controller)
    {
        $this->beginMemory = memory_get_usage();
        $this->startTime = microtime(true);
    }

    public function after($value)
    {
        $endTime = microtime(true);
        $endNemory = memory_get_usage();
        $usedMemory = ($endNemory - $this->startMemory) / 1024;
        echo '<br>耗时:' . round($endTime - $this->startTime,3).'秒';
        echo '<br>内存:' . $usedMemory . 'KB';
    }
}