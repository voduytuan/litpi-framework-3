<?php

$registry = \Litpi\Registry::getInstance();
define('PROTOCOL', \Litpi\Router::getProtocol($registry));

$conf['host'] = HOST;
$conf['rooturl'] = PROTOCOL . '://' . $conf['host'] . '/';
$conf['rooturl_admin'] = PROTOCOL . '://' . $conf['host'] . '/admin/';

ini_set('session.name', 'SHASH');
ini_set('session.use_only_cookies', true);
ini_set('session.use_trans_sid', false);
