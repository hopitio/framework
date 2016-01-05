<?php

define('BASE_DIR', dirname(__DIR__));

require_once BASE_DIR . '/Config/enviroment.php';
require_once BASE_DIR . '/Libs/Slim/Slim.php';
require_once BASE_DIR . '/Libs/Fn.php';

//autoload
foreach (Config::autoload() as $path)
{
    spl_autoload_register(function($class) use($path)
    {
        $parts = explode('/', $path . '/' . str_replace("\\", '/', $class) . '.php');
        $file = '';
        foreach ($parts as $part)
        {
            $file .= ucfirst($part) . '/';
        }
        $file = rtrim($file, '/');

        if (file_exists($file))
        {
            require_once $file;
        }
    });
}

//khoi tao ung dung
$application = new \Libs\Bootstrap();

