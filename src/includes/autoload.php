<?php

function autoloadlitpi($classname)
{
    $filepathFromMapping = classmap($classname);

    $sitepath = realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

    if ($filepathFromMapping == '') {
        //Process Namespace Directoryseparator
        $namepart = explode('\\', $classname);

        //If we not found some default root namespace
        //Make VENDOR is default namespace
        if (!in_array(strtolower($namepart[0]), array('model', 'controller', 'vendor'))) {
            array_unshift($namepart, 'Vendor');
        }

        $filepath = $sitepath;
        for ($i = 0; $i < count($namepart); $i++) {
            $filepath .= trim($namepart[$i]);

            if ($i == count($namepart) - 1) {
                $filepath .= '.php';
            } else {
                $filepath .= DIRECTORY_SEPARATOR;
            }
        }
    } else {
        $filepath = $sitepath . $filepathFromMapping;
    }

    if (is_readable($filepath)) {
        include_once($filepath);

        return true;
    } else {
        return false;
    }
}
