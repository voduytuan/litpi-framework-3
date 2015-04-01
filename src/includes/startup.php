<?php

namespace Litpi;

$registry = Registry::getInstance();
$conf = $registry->get('conf');

$db = new MyPdoProxy();
$db->addMaster($conf['db']['host'], $conf['db']['user'], $conf['db']['pass'], $conf['db']['name']);
$db->addSlave($conf['db']['host'], $conf['db']['user'], $conf['db']['pass'], $conf['db']['name']);
$registry->set('db', $db);

//Init session
//session_start();
$registry->get('session')->start();

$registry->set('currentTemplate', $registry->getResourceHost('static'));
$registry->set('imageDir', $registry->getResourceHost('static') . 'images/');
