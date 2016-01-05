<?php

namespace Libs\SQL;

class Query
{

    static function make_instance()
    {
        return new static;
    }

    /**
     * 
     * @param type $fields
     * @param type $override default FALSE, TRUE sẽ xóa hết select cũ
     */
    function select($fields, $override = true)
    {
        if ($override)
        {
            $this->_select = array($fields);
        }
        else
        {
            $this->_select[] = $fields;
        }
        return $this;
    }

    /**
     * Tên bảng chính
     * @param type $table
     */
    function from($table)
    {
        $this->_from = $table;
        return $this;
    }

    /**
     * 
     * @param type $table
     * @param type $key
     */
    function join($table, $key = null)
    {
        if ($key === null)
        {
            $this->_join[] = "JOIN $table";
        }
        else
        {
            $this->_join[$key] = $table;
        }
        return $this;
    }

    /**
     * 
     * @param type $table
     * @param type $key
     */
    function inner_join($table, $key = null)
    {
        if ($key === null)
        {
            $this->_join[] = "INNER JOIN $table";
        }
        else
        {
            $this->_join[$key] = "INNER JOIN $table";
        }
        return $this;
    }

    /**
     * 
     * @param type $table
     * @param type $key
     */
    function left_join($table, $key = null)
    {
        if ($key === null)
        {
            $this->_join[] = "LEFT JOIN $table";
        }
        else
        {
            $this->_join[$key] = "LEFT JOIN $table";
        }
        return $this;
    }

    /**
     * 
     * @param type $table
     * @param type $key
     */
    function full_join($table, $key = null)
    {
        if ($key === null)
        {
            $this->_join[] = "FULL JOIN $table";
        }
        else
        {
            $this->_join[$key] = "FULL JOIN $table";
        }
        return $this;
    }

    /**
     * 
     * @param type $table
     * @param type $key
     */
    function right_join($table, $key = null)
    {
        if ($key === null)
        {
            $this->_join[] = "RIGHT JOIN $table";
        }
        else
        {
            $this->_join[$key] = "RIGHT JOIN $table";
        }
        return $this;
    }

    /**
     * 
     * @param type $statement
     * @param type $_where_key
     */
    function where($statement, $_where_key = null)
    {
        if ($_where_key OR $_where_key === 0 OR $_where_key === '0')
        {
            if ($statement !== null && $statement !== false)
            {
                $this->_where[$_where_key] = $statement;
            }
            elseif (isset($this->_where[$_where_key]))
            {
                unset($this->_where[$_where_key]);
            }
        }
        else
        {
            $this->_where[] = $statement;
        }
        return $this;
    }

    /**
     * 
     * @param type $fields
     */
    function order_by($fields)
    {
        $this->_order_by = $this->escape_string($fields);
        return $this;
    }

    function group_by($field)
    {
        $this->_group_by = $this->escape_string($field);
        return $this;
    }

    /**
     * 
     * @param type $limit
     * @param type $offset
     */
    function limit($limit, $offset = null)
    {
        $this->_limit = (int) $limit;
        $this->_offset = (int) $offset;
        return $this;
    }

    /**
     * 
     * @param type $offset
     */
    function offset($offset)
    {
        $this->_offset = (int) $offset;
        return $this;
    }

    /**
     * 
     * @param bool $if_expression
     * @return static
     */
    function when($if_expression)
    {
        return $this->decorate(new If_Logic_Decorator($if_expression));
    }

    /**
     * 
     * @return string
     */
    function __toString()
    {
        $select = empty($this->_select) ? '*' : implode(',', $this->_select);
        $sql = "SELECT $select FROM {$this->_from}";
        if (!empty($this->_join))
        {
            $sql .= "\n" . implode("\n", $this->_join);
        }
        if (!empty($this->_where))
        {
            $sql .= "\nWHERE " . implode("\n AND ", $this->_where);
        }

        if (!empty($this->_group_by))
        {
            $sql .= "\nGROUP BY {$this->_group_by}";
        }
        if (!empty($this->_having))
        {
            $sql .= "\nHAVING {$this->_having}";
        }
        if (!empty($this->_order_by))
        {
            $sql .= "\nORDER BY {$this->_order_by}";
        }
        if (!empty($this->_limit))
        {
            $sql .= "\nLIMIT {$this->_limit}";
            if (!empty($this->_offset))
            {
                $sql .= "\nOFFSET {$this->_offset}";
            }
        }
        return $sql;
    }

    function escape_string($str)
    {
        $arr_search = array('&', '<', '>', '"', "'", '/', "\\", "\\");
        $arr_replace = array();
        foreach ($arr_search as $v)
        {
            $arr_replace[] = htmlentities($v);
        }
        return str_replace($arr_search, $arr_replace, $str);
    }

}
