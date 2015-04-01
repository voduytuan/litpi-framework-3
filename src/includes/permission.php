<?php

define('GROUPID_GUEST', 0);
define('GROUPID_ADMIN', 1);
define('GROUPID_MODERATOR', 2);
define('GROUPID_DEVELOPER', 3);
define('GROUPID_EMPLOYEE', 5);
define('GROUPID_PARTNER', 10);
define('GROUPID_DEPARTMENT', 15);
define('GROUPID_GROUP', 16);
define('GROUPID_MEMBER', 20);
define('GROUPID_MEMBERBANNED', 25);


//format: $p[groupid] = array('{module}' => array ('{controller}_{action}'));
$groupPermisson[GROUPID_GUEST] = array (
    'site'  => array (
        'index_*',
        'install_*',
        'notfound_*',
        'logout_*',
    ),
    'admin' => array (
        'login_*',
        'forgotpass_*',
    )
);

$groupPermisson[GROUPID_ADMIN] = array (
    'site'  => array (
        'index_*',
        'notfound_*',
        'logout_*',
    ),
    'admin' => array (
        'forgotpass_*',
        'utility_*',
        'index_*',
        'codegenerator_*',
        'user_*',
        'null_*',
    )
);
