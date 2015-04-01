<?php
// Here you can initialize variables that will be available to your tests
require_once(\Codeception\Configuration::projectDir() . 'src/includes/autoload.php');
require_once(\Codeception\Configuration::projectDir() . 'src/includes/classmap.php');
require_once(\Codeception\Configuration::projectDir() . 'src/Vendor/autoload.php');
spl_autoload_register('autoloadlitpi');

include_once(\Codeception\Configuration::projectDir() . 'src/libs/smarty/Smarty.class.php');
