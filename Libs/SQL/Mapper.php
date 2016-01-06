<?php

namespace Libs\SQL;

abstract class Mapper extends Query
{

    protected $db;
    protected $params = array();
    protected $pageSize = 20;
    protected $mapperSet;

    function __construct()
    {
        $this->db = DB::getInstance();
        $this->select($this->tableAlias() . '.*')
                ->from($this->tableName() . ' ' . $this->tableAlias());
    }

    static function makeInstance()
    {
        return new static();
    }

    abstract function tableName();

    abstract function tableAlias();

    abstract function makeEntity($rawData);

    function setParam($value, $key)
    {
        $this->params[$key] = $value;
        return $this;
    }

    function setParams($arr)
    {
        if (!is_array($arr))
        {
            $arr = array($arr);
        }
        $this->params += $arr;
        return $this;
    }

    function setPage($pageNo, $pageSize = null)
    {
        $pageSize = $pageSize ? $pageSize : $this->pageSize;
        $offset = ($pageNo - 1) * $pageSize;

        $this->limit($pageSize, $offset);
        return $this;
    }

    function count(&$totalRecord)
    {
        $mapper = clone $this;
        $mapper->select('COUNT(*)')
                ->limit(1)
                ->offset(0)
                ->order_by(null);
        $totalRecord = $this->db->GetOne($mapper->__toString(), $this->params);
        return $this;
    }

    function getEntity($callback = null)
    {
        $this->limit(1);
        $row = $this->db->GetRow($this->__toString(), $this->params);
        $entity = $this->makeEntity($row);
        if (is_callable($callback))
        {
            call_user_func($callback, $row, $entity);
        }
        return $entity;
    }

    protected function makeEntitySet($entities = array())
    {
        return new EntitySet($entities);
    }

    /** @return Entity_Iterator */
    function getAll($callback = null)
    {
        $rows = $this->db->GetAll($this->__toString(), $this->params);
        $rows = $rows ? $rows : array();
        $entities = array();

        foreach ($rows as $row)
        {
            $entity = $this->makeEntity($row);
            if (is_callable($callback))
            {
                call_user_func($callback, $row, $entity);
            }
            $entities[] = $entity;
        }

        return $this->makeEntitySet($entities);
    }

    function getOne()
    {
        $this->limit(1);
        return $this->db->GetOne($this->__toString(), $this->params);
    }

    function getCol()
    {
        $this->limit(1);
        return $this->db->GetCol($this->__toString(), $this->params);
    }

    function getAssoc()
    {
        return $this->db->GetAssoc($this->__toString(), $this->params);
    }

}
