<?php

use Libs\MvcContext;

$routes['/'] = new MvcContext('GET', "Apps\\Cores\\Controllers\\HomeCtrl", 'index');


$routes['/admin/user'] = new MvcContext('GET', "Apps\\Cores\\Controllers\\UserCtrl", 'index');

$routes['/admin/list'] = new MvcContext('GET', "Apps\\Cores\\Controllers\\ListCtrl", 'index');
