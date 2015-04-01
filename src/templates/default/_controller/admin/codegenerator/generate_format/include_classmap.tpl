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
        {{CLASSMAP_ARRAY_ELEMENTS}}
    );

    return $classmapList;
}
