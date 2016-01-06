<?php

namespace Libs;

class Json
{

    static function encode($val)
    {
        if (is_object($val))
        {
            $val = json_decode(json_encode($val), true);
        }
        if (is_array($val))
        {
            array_walk_recursive($val, function(&$val)
            {
                if ($val instanceof EntitySet)
                {
                    $val = $val->var;
                }
            });
        }

        return json_encode($val);
    }

    static function decode($val)
    {
        return json_decode($val, true);
    }

}
