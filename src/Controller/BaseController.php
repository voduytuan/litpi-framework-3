<?php

namespace Controller;

use Litpi\Helper as Helper;
use Litpi\Cookie;

abstract class BaseController extends \Litpi\Controller\BaseController
{
    protected $registry;
    protected $getBag;
    protected $postBag;

    abstract public function indexAction();

    public function __construct($registry)
    {
        $this->getBag = $registry->get('request')->query;
        $this->postBag = $registry->get('request')->request;

        /////////////////////////////////////////////
        //  LANGUAGE DETECTING
        if ($registry->request->query->has('language')) {
            $registry->session->set('language', $registry->request->query->get('language'));

            $myCookie = new Cookie('language', $registry->request->query->get('language'), time() + 240 * 3600, '/');
            $registry->response->headers->setCookie($myCookie);

        } elseif ($registry->request->request->has('language')) {
            $registry->session->set('language', $registry->request->request->get('language'));

            $myCookie = new Cookie('language', $registry->request->request->get('language'), time() + 240 * 3600, '/');
            $registry->response->headers->setCookie($myCookie);
        }

        if ($registry->session->has('language')) {
            $langCode = $registry->session->get('language');

        } elseif ($registry->request->cookies->has('language')) {
            $langCode = $registry->request->cookies->get('language');

        } else {

            //Check language from Header (Accept-Language)
            $headerLangCode = Helper::getBrowserLanguage();

            if ($headerLangCode == 'vi' || $headerLangCode == 'vn') {
                $langCode = 'vn';

            } else {
                $langCode = $registry->conf['defaultLang'];   //en
            }
        }

        //Ensure langCode always two character, ex: vn, en, fr...
        $langCode = substr($langCode, 0, 2);

        //declare language variable
        $lang = array();
        $lang['global'] = Helper::getLangContent(
            'language' . DIRECTORY_SEPARATOR . $langCode . DIRECTORY_SEPARATOR,
            'global'
        );

        $defaultDirectoryLang = strtolower($registry->module);

        $lang['default'] = Helper::GetLangContent(
            'language' . DIRECTORY_SEPARATOR . $langCode
            . DIRECTORY_SEPARATOR . $defaultDirectoryLang . DIRECTORY_SEPARATOR,
            'default'
        );


        $lang['controller'] = Helper::GetLangContent(
            'language' . DIRECTORY_SEPARATOR . $langCode . DIRECTORY_SEPARATOR
            . strtolower($registry->module) . DIRECTORY_SEPARATOR,
            strtolower($registry->controller)
        );

        //init for used in registry and template
        $registry->set('lang', $lang);
        $registry->set('langCode', $langCode);


        ///////////////////////////////////////////
        //  MOBILE DETECTING
        $registry->mobiledetect = new \Vendor\Other\MobileDetect();


        //////////////////
        // Init smarty
        $smarty = new \Smarty();

        $currentTemplate = 'default';
        $smartyRootPath = SITE_PATH . 'templates' . DIRECTORY_SEPARATOR;
        $smarty->template_dir = $smartyRootPath . $currentTemplate;
        $smarty->compile_dir = $smartyRootPath . '_core' . DIRECTORY_SEPARATOR . 'templates_c' . DIRECTORY_SEPARATOR;
        $smarty->config_dir = $smartyRootPath . '_core' . DIRECTORY_SEPARATOR . 'configs' . DIRECTORY_SEPARATOR;
        $smarty->cache_dir = $smartyRootPath . '_core' . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR;
        $smarty->compile_id = $currentTemplate;    //seperate compiled template file
        $smarty->error_reporting = E_ALL ^ E_NOTICE;
        $smarty->compile_check = $registry->setting['site']['smartyCompileCheck'];
        $smarty->assign(array(
            'registry' => $registry,
            'conf' => $registry->conf,
            'setting' => $registry->setting,
            'currentTemplate' => $registry->getResourceHost('static'),
            'currentUrl' => \Litpi\Helper::curPageURL(),
            'imageDir' => $registry->imageDir,
            'module' => $registry->module,
            'controller' => $registry->controller,
            'action' => $registry->action,
            'route' => $registry->route,
            'me' => $registry->me,
            'lang' => $lang,
            'langCode' => $langCode,
            'request' => $registry->request,
            'response' => $registry->response,
            'session' => $registry->session,
            'getBag' => $this->getBag,
            'postBag' => $this->postBag,
        ));
        $registry->set('smarty', $smarty);

        //set smarty template container
        $registry->set('smartyControllerRoot', '_controller/');
        $registry->set('smartyModule', '_controller/' . $registry->module . '/');
        $registry->set(
            'smartyController',
            '_controller/' . $registry->module . '/' . $registry->controller . '/'
        );
        $registry->set('smartyMail', '_mail/');

        $registry->smarty->assign(array(
            'smartyControllerRoot' => '_controller/',
            'smartyModule' => '_controller/' . $registry->module . '/',
            'smartyController' => '_controller/' . $registry->module
                . '/' . $registry->controller . '/',
            'smartyMail' => '_mail/',
        ));

        $this->registry = $registry;
    }

    public function __call($name, $args)
    {
        $url = $this->registry->conf['rooturl'] . 'notfound?r=' . base64_encode(\Litpi\Helper::curPageURL());
        $this->doRedirect($url);
    }


    protected function getRedirectUrl()
    {
        return $this->registry->conf['rooturl'] . $this->registry->module . '/' . $this->registry->controller;
    }

    protected function notfound()
    {
        $url = $this->registry->conf['rooturl'] . 'notfound?r=' . base64_encode(\Litpi\Helper::curPageURL());
        $this->doRedirect($url);
    }

    protected function doLogin($userId, $password, $initRedirectUrl)
    {
        $redirectUrl = $initRedirectUrl;

        $this->registry->session->migrate();
        $this->registry->session->set('userLogin', $userId);

        //auto login
        //neu co chon chuc nang remember me
        if ($this->registry->request->request->has('frememberme')) {

            $this->registry->response->headers->setCookie(new Cookie(
                'myHashing',
                \Litpi\ViephpHashing::cookiehashing($userId, $password),
                time() + 24 * 3600 * 14,
                '/'
            ));

        } else {
            $this->registry->response->headers->setCookie(new Cookie(
                'myHashing',
                '',
                time() - 3600,
                '/'
            ));
        }

        ///////////////
        $this->registry->response->headers->setCookie(new Cookie(
            'islogin',
            1,
            time() + 24 * 3600 * 14,
            '/'
        ));

        //tien hanh redirect
        if (strlen($redirectUrl) > 0) {
            $redirectUrl = base64_decode($redirectUrl);
        } elseif ($this->registry->request->query->has('returnurl')) {
            $redirectUrl = urldecode($this->registry->request->query->get('returnurl'));
        } else {
            $redirectUrl = $this->registry->conf['rooturl_' . $this->registry->module] . '';
        }

        return $redirectUrl;
    }

    protected function doLogout()
    {
        $this->registry->session->migrate();
        $this->registry->session->invalidate();

        $this->registry->response->headers->setCookie(new Cookie('myHashing', '', time() - 3600, '/'));
        $this->registry->response->headers->setCookie(new Cookie('islogin', '', time() - 3600, '/'));

        $this->doRedirect($this->registry->conf['rooturl_' . $this->registry->module] . 'login');
    }

    protected function doRedirect($redirectUrl, $statusCode = 302)
    {
        $this->registry->response->setStatusCode($statusCode);
        $this->registry->response->headers->set('Location', $redirectUrl);
    }

    protected function getCurrentPage()
    {
        if ($this->registry->request->request->has('page')) {
            $page = (int)$this->registry->request->request->get('page');
        } else {
            $page = (int)$this->registry->request->query->get('page', 1);
        }

        //page must be larager than 0
        if ($page <= 0) {
            $page = 1;
        }

        return $page;
    }
}
