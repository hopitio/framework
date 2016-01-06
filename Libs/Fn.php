<?php

use Libs\Bootstrap;
use Libs\SQL\EntitySet;

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

/**
 * Chuyển mảng về tham số get
 * @param array $arr
 * @return string
 */
function encodeForm($arr)
{
    $ret = '';
    foreach ($arr as $k => $v)
    {
        $ret .= $ret ? "&{$k}={$v}" : "{$k}={$v}";
    }
    return $ret;
}
