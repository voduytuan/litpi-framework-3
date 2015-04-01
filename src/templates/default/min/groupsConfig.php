<?php
/**
 * Groups configuration for default Minify implementation
 * @package Minify
 */

/**
 * You may wish to use the Minify URI Builder app to suggest
 * changes. http://yourdomain/min/builder/
 **/

return array(
    'js' => array(
        '../js/site/main.js',
    ),
    'jsadmin' => array(
        '../js/admin/jquery.cookie.js',
        '../js/admin/bootbox.js',
        '../js/admin/bootstrap-confirmation.js',
        '../js/admin/admin.js',
    ),

    'jquery' => array(
        '../js/jquery.js',
    ),
    'css' => array(
        '../css/site/main.css',
    ),
    'cssadmin' => array(
        '../css/admin/mystyle.css',
        '../css/admin/spf.css',
    ),
);
