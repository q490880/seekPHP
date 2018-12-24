<?php
namespace vendor\seek\database;

class PDO extends ADatabase
{
    protected $conn;
    protected $resource;
    public function connect($host, $user, $passwd, $dbname, $port = 3306)
    {
        $conn = new \PDO("mysql:host=$host;dbname=$dbname", $user, $passwd);
        $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->conn = $conn;
    }

    public function query($sql,$params = [])
    {
        $this->resource = $this->conn->prepare($sql);
        $this->resource->execute($params);
        if (strtolower(substr($sql,0,6)) != "select") {
            return $this->resource->rowCount();
        }
        return $this;
    }

    public function fetchAll()
    {
        return $this->resource->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function fetchOne()
    {
        return $this->resource->fetch(\PDO::FETCH_ASSOC);
    }

    public function close()
    {
        unset($this->conn);
    }
}