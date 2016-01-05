<?php

namespace Libs\SQL;

abstract class Entity
{

    function __construct($rawData = null)
    {
        foreach ($rawData as $k => $v)
        {
            $this->{$k} = $v;
        }
    }

}
