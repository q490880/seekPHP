<?php
namespace vendor\seek\Database;

class MySQL extends ADatabase
{
    public function connect($host, $user, $passwd, $dbname, $port = 3306)
    {
        $conn = mysql_connect($host, $user, $passwd);
        mysql_select_db($dbname, $conn);
        $this->conn = $conn;
    }

    public function query($sql)
    {
        $res = mysql_query($sql, $this->conn);
        return $res;
    }

    public function fetchAll()
    {
        // TODO: Implement fetchAll() method.
    }

    public function fetchOne()
    {
        // TODO: Implement fetchOne() method.
    }

    public function close()
    {
        mysql_close($this->conn);
    }
}