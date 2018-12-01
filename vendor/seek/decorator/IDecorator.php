<?php
namespace vendor\seek\decorator;

interface IDecorator
{
    public function before($controller);
    public function after($value);
}