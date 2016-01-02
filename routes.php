<?php

use Libs\MvcContext;

$routes['/'] = new MvcContext('GET', "Apps\\Cores\\Controllers\\HomeCtrl", 'index');

$routes['/en'] = $routes['/vn'] = array(
    '/book' => new MvcContext('GET', "Apps\\Cores\\Controllers\\HomeCtrl", 'book')
);

