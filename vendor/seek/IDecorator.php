<?php
namespace vendor\seek;

interface IDecorator
{
    public function beforeRun();
    public function afterRun();
}