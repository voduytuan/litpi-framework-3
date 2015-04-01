<?php

function classmap($classname)
{
    //Lowercase all part in classname to prevent some weird case name
    $classname = strtolower($classname);

    $classmapList = classmapList();

    $shortclassname = substr($classname, strlen('controller\\'));
    if (isset($classmapList[$shortclassname])) {
        return 'Controller' . DIRECTORY_SEPARATOR . $classmapList[$shortclassname];
    } else {
        return '';
    }
}

function classmapList()
{
    $s = DIRECTORY_SEPARATOR;

    //Create by Generator
    $classmapList = array(
        'admin\basecontroller' => 'Admin' . $s . 'BaseController.php',
        'admin\codegenerator' => 'Admin' . $s . 'CodeGenerator.php',
        'admin\forgotpass' => 'Admin' . $s . 'ForgotPass.php',
        'admin\index' => 'Admin' . $s . 'Index.php',
        'admin\login' => 'Admin' . $s . 'Login.php',
        'admin\null' => 'Admin' . $s . 'Null.php',
        'admin\user' => 'Admin' . $s . 'User.php',
        'admin\utility' => 'Admin' . $s . 'Utility.php',
        'site\index' => 'Site' . $s . 'Index.php',
        'site\install' => 'Site' . $s . 'Install.php',
        'site\logout' => 'Site' . $s . 'Logout.php',
    );

    return $classmapList;
}
