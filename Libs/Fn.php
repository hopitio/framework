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

define('XPATH_STRING', 1);
define('XPATH_ARRAY', 2);
define('XPATH_DOM', 3);

/**
 * 
 * @param SimpleXmlElement $dom
 * @param type $xpath
 * @param type $method
 * @return SimpleXmlElement
 */
function xpath($dom, $xpath, $method = 1)
{
    if ($dom instanceof SimpleXMLElement == false)
    {
        switch ($method) {
            case XPATH_STRING:
                return '';
            case XPATH_ARRAY:
                return array();
            case XPATH_DOM:
                return null;
        }
    }

    $r = $dom->xpath($xpath);
    switch ($method) {
        case XPATH_ARRAY:
            return $r;
        case XPATH_DOM:
            return $r[0];
        case XPATH_STRING:
        default:
            return count($r) ? (string) $r[0] : null;
    }
}

/**
 * Lấy config từ thư mục Config/
 * @param string $fileName
 * @return mixed
 */
function getConfig($fileName)
{
    $file = BASE_DIR . '/Config/' . $fileName . '.config.php';
    require $file;
    if (!isset($exports))
    {
        throw new Exception($file . ' phải có biến $exports');
    }
    return $exports;
}
