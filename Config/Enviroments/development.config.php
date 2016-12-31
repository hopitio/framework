<?php

/**
 * 0 = tắt
 * 1 = PHP, Database trừ trên service
 * 10 = tất cả
 */
$exports['debugMode'] = 10;

//kết nối database
$exports['db'] = array(
    'type' => 'mysqli',
    'host' => '172.16.10.90',
    'name' => 'telerad2',
    'user' => 'ehr',
    'pass' => 'ehr'
);

$exports['cryptSecrect'] = 'abM)(*2312';

date_default_timezone_set('Asia/Bangkok');
