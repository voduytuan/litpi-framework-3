<?php

include('settingcontroller.php');
include('settingtemplate.php');

//format
//$setting['group']['entry'] = value;
$setting['site']['cacheKeyPrefix'] = 'lp_';

$setting['resourcehost']['static'] = 'templates/default/';
$setting['resourcehost']['static_https'] = 'templates/default/';

$setting['mail']['usingAmazonses'] = true;  //this true will disabled smtp
$setting['amazon']['publickey'] = '';
$setting['amazon']['privatekey'] = '';
$setting['smtp']['enable'] = true;  //SMTP TRUE also Will be override by Amazon SES
$setting['smtp']['host'] = '';
$setting['smtp']['username'] = '';
$setting['smtp']['password'] = '';
