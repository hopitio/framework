<?php

use Libs\MvcContext;

$routes[] = new MvcContext('/', 'GET', "Apps\\Cores\\Controllers\\HomeCtrl", 'index');

$routes[] = new MvcContext('/admin/config.js', 'GET', "Apps\\Cores\\Controllers\\HomeCtrl", 'configJS');

$routes[] = new MvcContext('/admin/user', 'GET', "Apps\\Cores\\Controllers\\UserCtrl", 'index');
$routes[] = new MvcContext('/admin/group', 'GET', "Apps\\Cores\\Controllers\\UserCtrl", 'group');
$routes[] = new MvcContext('/admin/list', 'GET', "Apps\\Cores\\Controllers\\ListCtrl", 'index');



$routes[] = new MvcContext('/rest/department/move', 'PUT', "Apps\\Cores\\Controllers\\Rest\\UserCtrl", 'moveDepartments');
$routes[] = new MvcContext('/rest/department/:id', 'GET', "Apps\\Cores\\Controllers\\Rest\\UserCtrl", 'getDepartment');
$routes[] = new MvcContext('/rest/department/:id', 'PUT', "Apps\\Cores\\Controllers\\Rest\\UserCtrl", 'updateDepartment');
$routes[] = new MvcContext('/rest/department', 'DELETE', "Apps\\Cores\\Controllers\\Rest\\UserCtrl", 'deleteDepartments');

$routes[] = new MvcContext('/rest/group', 'GET', "Apps\\Cores\\Controllers\\Rest\\UserCtrl", 'getGroups');
$routes[] = new MvcContext('/rest/group/:id/user', 'GET', "Apps\\Cores\\Controllers\\Rest\\UserCtrl", 'getGroupUsers');
$routes[] = new MvcContext('/rest/group/:id', 'PUT', "Apps\\Cores\\Controllers\\Rest\\UserCtrl", 'updateGroup');

$routes[] = new MvcContext('/rest/basePermission', 'GET', "Apps\\Cores\\Controllers\\Rest\\UserCtrl", 'getBasePermissions');

$routes[] = new MvcContext('/rest/user/search', 'GET', "Apps\\Cores\\Controllers\\Rest\\UserCtrl", 'search');
$routes[] = new MvcContext('/rest/user/move', 'PUT', "Apps\\Cores\\Controllers\\Rest\\UserCtrl", 'moveUsers');
$routes[] = new MvcContext('/rest/user/checkUniqueAccount', 'POST', "Apps\\Cores\\Controllers\\Rest\\UserCtrl", 'checkUniqueAccount');
$routes[] = new MvcContext('/rest/user/:id', 'PUT', "Apps\\Cores\\Controllers\\Rest\\UserCtrl", 'updateUser');
$routes[] = new MvcContext('/rest/user', 'DELETE', "Apps\\Cores\\Controllers\\Rest\\UserCtrl", 'deleteUsers');





