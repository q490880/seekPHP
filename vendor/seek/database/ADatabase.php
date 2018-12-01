<?php
namespace vendor\seek\database;

abstract class ADatabase
{
    protected $conn;
    protected $resource;
    abstract public function connect($host, $user, $passwd, $dbname, $port);
    abstract public function query($sql);
    abstract public function close();
    abstract public function fetchOne();
    abstract public function fetchAll();
}