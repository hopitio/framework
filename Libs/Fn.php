<?php

use Libs\Bootstrap;

function url($path = '', $params = array())
{
    $app = Bootstrap::getInstance();
    $url = $app->rewriteBase . $path;
    $sep = '?';
    foreach ($params as $k => $v)
    {
        $url .= "{$sep}{$k}={$v}";
        $sep = '&';
    }
    return $url;
}

function urlAbsolute($path = '', $params = array())
{
    $protocol = empty($_SERVER['HTTPS']) ? 'http' : 'https';
    $host = $_SERVER['HTTP_HOST'];
    $port = $_SERVER['SERVER_PORT'] == 80 || $_SERVER['SERVER_PORT'] == 443 ? '' : ':' . $_SERVER['SERVER_PORT'];
    return "{$protocol}://{$host}{$port}/" . static::url($path, $params);
}

function arrData($arr, $key, $default = null)
{
    return isset($arr[$key]) ? $arr[$key] : $default;
}
