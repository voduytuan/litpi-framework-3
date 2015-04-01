<?php

function rewriteruleParsing($route)
{
    $registry = \Litpi\Registry::getInstance();

    $setting = $registry->get('setting');
    $conf = $registry->get('conf');

    $partTmps = explode('/', $route);

    //Refine empty part from parts
    $parts = array();
    foreach ($partTmps as $part) {
        $part = trim($part);
        if ($part != '') {
            $parts[] = $part;
        }
    }

    // Fallback for frontend site
    if ($route == '') {
        $route = 'site/index/index';
    }

    ////////////////////////////
    // Parsing Route to get MODULE, CONTROLLER & ACTION
    $parts = explode('/', $route);
    $module = '';
    $controller = '';
    $action = '';

    if ($parts[0]) {
        $module = $parts[0];
    }

    if (!empty($parts[1])) {
        $controller = $parts[1];
        if (!empty($parts[2])) {
            $action = $parts[2];
        } else {
            $action = 'index';
            $route = $module . '/' . $controller . '/' . 'index';
        }
    } else {
        $controller = 'index';
        $action = 'index';
        $route = $module . '/' . 'index' . '/' . 'index';
    }

    $registry->set('module', $module);
    $registry->set('controller', $controller);
    $registry->set('action', $action);
    $registry->set('route', $route);

    return $route;
}
