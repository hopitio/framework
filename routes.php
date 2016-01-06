<?php

use Libs\MvcContext;

$routes[] = new MvcContext('/', 'GET', "Apps\\Cores\\Controllers\\HomeCtrl", 'index');

$routes[] = new MvcContext('/admin/config.js', 'GET', "Apps\\Cores\\Controllers\\HomeCtrl", 'configJS');

$routes[] = new MvcContext('/admin/user', 'GET', "Apps\\Cores\\Controllers\\UserCtrl", 'index');
$routes[] = new MvcContext('/admin/user', 'POST', "Apps\\Cores\\Controllers\\UserCtrl", 'indexPost');
$routes[] = new MvcContext('/admin/list', 'GET', "Apps\\Cores\\Controllers\\ListCtrl", 'index');

$routes[] = new MvcContext('/rest/department/:id', 'GET', "Apps\\Cores\\Controllers\\Rest\\UserCtrl", 'get');


