<?php
namespace vendor\seek;

abstract class Model
{
    public $where;
    public $orderBy;
    public $limit = 0;
    public $offset = 0;
    public $groupBy;
    public $select = '*';

    public function where($where)
    {
        $this->where = $where;
        return $this;
    }

    public function select($select)
    {
        $this->select = $select;
        return $this;
    }

    public function orderBy($order)
    {
        $this->order = $order;
        return $this;
    }

    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    public function groupBy($group)
    {
        $this->groupBy = $group;
        return $this;
    }

    public function tableName()
    {
        return __CLASS__;
    }


    public function getSql($sqlType)
    {
        $sql = '';
        $params = [];
        if ($sqlType == 'select') {
            $sql = "SELECT {$this->select} FROM {$this->tableName()}";
            if ($this->where) {
                $where = '';
                if (is_array($this->where)) {
                    # where条件是数组
                    foreach ($this->where as $key => $value) {
                        $params[] = (string)$value;
                        if (empty($reformWhere)) {
                            $where = "{$key} = ?";
                        } else {
                            $where = " and {$key} = ?";
                        }
                    }
                } else {
                    # where条件是字符串
                    $where = $this->where;
                }
                $sql .= " WHERE {$where}";
            }
            if ($this->orderBy) {
                $sql .= " {$this->orderBy}";
            }
            if ($this->groupBy) {
                $sql .= " {$this->groupBy}";
            }
            if ($this->limit > 0) {
                $sql .= " limit {$this->offset},{$this->limit}";
            }
        }
        return [$sql,$params];
    }

    public static function find()
    {
        $childClassName = get_called_class();
        $class = new $childClassName();
        return $class;
    }

    public function one()
    {
        list($sql,$params) = $this->getSql("select");
        $result = App::getInstance()->getDb()->query($sql,$params)->fetchOne();
        return $result;
    }

    public function all()
    {
        list($sql,$params) = $this->getSql("select");
        $result = App::getInstance()->getDb()->query($sql,$params)->fetchAll();
        return $result;
    }
}