<?php

//HOST not end with slash
define('HOST', 'localhost/litpiproject/github/litpi-framework-3/src');
define('TABLE_PREFIX', 'lit_');
define('SITE_PATH', \Litpi\Helper::getFileDir(__FILE__));

error_reporting(E_ALL ^ E_NOTICE);
ini_set("display_errors", 1);
set_time_limit(30);
date_default_timezone_set('Asia/Ho_Chi_Minh');

//Init array contains all configuration for website
$conf = array();

$conf['defaultLang'] = 'vn';

//Main Database (Master)
$conf['db']['host'] = 'localhost';
$conf['db']['name'] = 'litpi3';
$conf['db']['user'] = 'root';
$conf['db']['pass'] = 'root';

$conf['redis'][0]['ip'] = '';
$conf['redis'][0]['port'] = '';

$conf['memcached'][0]['ip'] = '127.0.0.1';
$conf['memcached'][0]['port'] = '11211';
