<?php
namespace vendor\seek;

abstract class Model implements \Iterator
{
    private $whereParams = [];
    private $where;
    private $orderBy;
    private $limit = 0;
    private $offset = 0;
    private $groupBy;
    private $select = '*';
    private $attribute;
    private $findType;
    private $asArray = 0;
    private $forIndex;
    public function asArray()
    {
        $this->asArray = 1;
        return $this;
    }

    public function where($where)
    {
        if (!empty($where)) {
            if (is_array($where)) {
                # where条件是数组
                foreach ($where as $key => $value) {
                    $this->whereParams[] = (string)$value;
                    if (empty($reformWhere)) {
                        $this->where = "{$key} = ?";
                    } else {
                        $this->where .= " and {$key} = ?";
                    }
                }
            } else {
                $this->where = $where;
            }
        }
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
                $sql .= " WHERE {$this->where}";
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
        } elseif ($sqlType == 'updateOne') {
            $sql = "update {$this->tableName()} set";
            foreach ($this->attribute as $key => $value) {
                if (empty($updateField)) {
                    $updateField = " {$key} = '{$value}'";
                } else {
                    $updateField .= ",{$key} = '{$value}'";
                }
            }
            $sql .= "{$updateField}";
            if ($this->where) {
                $sql .= " where {$this->where}";
            }
            $sql .= " limit 1";
        }elseif ($sqlType == 'update') {
            $sql = "update {$this->tableName()} set";
            foreach ($this->attribute as $key => $value) {
                $sql .= " {$key} = '{$value}'";
            }
            if ($this->where) {
                $sql .= " where {$this->where}";
            }
        } elseif ($sqlType == 'insert') {
            $fields = '';
            $values = '';
            foreach ($this->attribute as $key => $value) {
                if (empty($fields)) {
                    $fields = "{$key}";
                    $values = "'{$value}'";
                } else {
                    $fields .= ",{$key}";
                    $values .= ",'{$value}'";
                }
            }
            $sql = "insert into {$this->tableName()} ({$fields}) values ({$values})";
        }
        return $sql;
    }

    public static function find()
    {
        $childClassName = get_called_class();
        $class = new $childClassName();
        return $class;
    }

    public function one()
    {
        $this->findType = 'one';
        $sql = $this->getSql("select");
        $this->attribute = App::getInstance()->getDb()->query($sql,$this->whereParams)->fetchOne();
        if ($this->asArray || empty($this->attribute)) {
            return $this->attribute;
        }
        return $this;
    }

    public function all()
    {
        $this->findType = 'all';
        $sql = $this->getSql("select");
        $this->attribute = App::getInstance()->getDb()->query($sql,$this->whereParams)->fetchAll();
        if ($this->asArray || empty($this->attribute)) {
            return $this->attribute;
        }
        return $this;
    }

    public function updateAll($attribute,$where)
    {
        if (empty($where) || empty($attribute)) {
            throw new \Exception("参数错误");
        }
        $this->where($where);
        foreach ($attribute as $key => $value) {
            $this->attribute[$key] = $value;
        }
        $sql = $this->getSql('update');
        $result =  App::getInstance()->getDb()->query($sql,$this->whereParams);
        return $result;
    }


    public function save()
    {
        if ($this->findType == 'all') {
            throw new \Exception("查询类型不正确");
        }
        if ($this->findType == '') {
            $sql = $this->getSql('insert');
        } else {
            $sql = $this->getSql('updateOne');
        }
        $result =  App::getInstance()->getDb()->query($sql,$this->whereParams);
        return $result;
    }

    public function __get($name)
    {
        if (isset($this->attribute[$name])) {
            return $this->attribute[$name];
        }
    }

    public function __set($name, $value)
    {
        if (isset($this->attribute[$name])) {
            $this->attribute[$name] = $value;
        }
    }

    public function rewind()
    {
        $this->forIndex = 0;
    }

    public function next()
    {
        $this->forIndex++;
    }

    public function key()
    {
        return $this->forIndex;
    }

    public function valid()
    {
        return $this->forIndex < count($this->attribute);
    }

    public function current()
    {
        $model = clone $this;
        $model->attribute = $this->attribute[$this->forIndex];
        return $model;
    }

}