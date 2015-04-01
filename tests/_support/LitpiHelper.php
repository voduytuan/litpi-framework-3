<?php
namespace Codeception\Module;

/**
 * This module provides integration with [Litpi Framework](http://litpiframework.com/) v3.
 * Functional tests can be run inside Litpi.
 * All commands of this module are just the same as in other modules that share Framework interface.
 *
 * ## Status
 *
 * * Maintainer: **Vo Duy Tuan**
 * * Stability: **alpha**
 * * Contact: tuanmaster2012@gmail.com
 *
 * ### Installation
 *
 * Module is created by [Vo Duy Tuan](tuanmaster2012@gmail.com)
 *
 */

use Codeception\Util\Fixtures;

class LitpiHelper extends \Codeception\Lib\Framework
{
    protected $config = array(
        'remoteaddr' => '',
    );

    public $registry = null;
    public $route = '';



    public function _initialize()
    {

    }

    public function _before(\Codeception\TestCase $test)
    {

        require_once(\Codeception\Configuration::projectDir() . 'src/includes/autoload.php');
        require_once(\Codeception\Configuration::projectDir() . 'src/includes/classmap.php');
        require_once(\Codeception\Configuration::projectDir() . 'src/Vendor/autoload.php');
        spl_autoload_register('autoloadlitpi');

        include_once(\Codeception\Configuration::projectDir() . 'src/libs/smarty/Smarty.class.php');

        //Overwrite remoteaddr
        $_SERVER['REMOTE_ADDR'] = $this->config['remoteaddr'];

        //INIT REGISTRY VARIABLE - MAIN STORAGE OF APPLICATION
        $registry = \Litpi\Registry::getInstance();
        $request = \Litpi\Request::createFromGlobals();
        $response = new \Litpi\Response();
        $session = new \Litpi\Session();
        $registry->set('request', $request);
        $registry->set('response', $response);
        $registry->set('session', $session);

        require_once(\Codeception\Configuration::projectDir() . 'src/includes/conf.php');
        require_once(\Codeception\Configuration::projectDir() . 'src/includes/config.php');
        require_once(\Codeception\Configuration::projectDir() . 'src/includes/setting.php');
        $registry->set('conf', $conf);
        $registry->set('setting', $setting);
        $registry->set('https', (PROTOCOL == 'https' ? true : false));

        require_once(\Codeception\Configuration::projectDir() . 'src/includes/permission.php');
        $registry->set('groupPermisson', $groupPermisson);

        require_once(\Codeception\Configuration::projectDir() . 'src/includes/rewriterule.php');
        require_once(\Codeception\Configuration::projectDir() . 'src/includes/startup.php');

        $this->registry = $registry;

        $this->client = new \Codeception\Lib\Connector\LitpiConnectorHelper();
        $this->client->setRegistry($this->registry);
    }

    public function _after(\Codeception\TestCase $test)
    {
        $_SESSION = array();
        $_GET = array();
        $_POST = array();
        $_COOKIE = array();
        parent::_after($test);
    }
}