<?php
namespace vendor\seek\Database;

class MySQLi extends ADatabase
{
    public function connect($host, $user, $passwd, $dbname, $port = 3306)
    {
        $conn = mysqli_connect($host, $user, $passwd, $dbname, $port) or die(iconv('gbk', 'utf-8', mysqli_connect_error()));
        mysqli_query($conn, "set names utf8");
        $this->conn = $conn;
    }

    public function query($sql,$params = [])
    {
        $this->resource = mysqli_query($this->conn, $sql);
        return $this;
    }

    public function fetchAll()
    {
        $result = [];
        if (!$this->resource) {
            return $result;
        }
        while ($data = $this->resource->fetch_array(MYSQLI_ASSOC)) {
            $result[] = $data;
        }
        return $result;
    }

    public function fetchOne()
    {
        if (!$this->resource) {
            return [];
        }
        return $this->resource->fetch_row();
    }

    public function close()
    {
        mysqli_close($this->conn);
    }
}