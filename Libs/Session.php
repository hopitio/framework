<?php

namespace Libs;

use \Slim\Helper\Set;

class Session extends Set
{

    function __construct()
    {
        parent::__construct($_SESSION);
    }

    function set($key, $value)
    {
        parent::set($key, $value);
        $_SESSION[$key] = $value;
    }

}
