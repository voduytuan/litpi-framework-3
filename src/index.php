<?php

namespace Litpi;

require 'includes/autoload.php';
require 'includes/classmap.php';
require 'Vendor/autoload.php';

spl_autoload_register('autoloadlitpi');

include('libs/smarty/Smarty.class.php');

//INIT REGISTRY VARIABLE - MAIN STORAGE OF APPLICATION
$registry = Registry::getInstance();

$request = \Litpi\Request::createFromGlobals();
$response = new \Litpi\Response();
$session = new \Litpi\Session();

$registry->set('request', $request);
$registry->set('response', $response);
$registry->set('session', $session);

require 'includes/conf.php';
require 'includes/config.php';
require 'includes/setting.php';

$registry->set('conf', $conf);
$registry->set('setting', $setting);
$registry->set('https', (PROTOCOL == 'https' ? true : false));
require 'includes/permission.php';
$registry->set('groupPermisson', $groupPermisson);

require 'includes/rewriterule.php';
require 'includes/startup.php';
include 'libs/pqp/classes/PhpQuickProfiler.php';

$myDosDetector = new DosDetector();
$myDosDetector->run($conf['rooturl'] . 'accessdeny.html');

if ($request->query->has('xprofiler')) {
    $pqpProfiler = new \PhpQuickProfiler(\PhpQuickProfiler::getMicroTime(), SITE_PATH . 'libs/pqp/');
}


# Load router
$registry->set('me', new \Model\User());
$router = new Router($registry);
$registry->set('router', $router);
$router->delegate();

//Send response
$registry->get('response')->send();


if ($request->query->has('xprofiler')) {
    $pqpProfiler->display();
}
