<?php

namespace Controller\Admin;

class Utility extends BaseController
{
    public function indexAction()
    {
        $redirectUrl = $this->registry->conf['rooturl_admin'] . 'utility/passwordgenerator';
        $this->doRedirect($redirectUrl);
    }

    public function passwordGeneratorAction()
    {
        $encodedPass = '';

        if ($this->postBag->get('fpassword') != '') {
            $myHasher = new \Litpi\ViephpHashing();
            $encodedPass = $myHasher->hash($this->postBag->get('fpassword'));
        }

        $this->registry->smarty->assign(array('encodedPass' => $encodedPass));

        $contents = $this->registry->smarty->fetch($this->registry->smartyController . 'passwordgenerator.tpl');
        $this->registry->smarty->assign(array(
            'menu' => 'passwordgenerator',
            'pageTitle' => 'Password Generator',
            'contents' => $contents
        ));

        $contents = $this->registry->smarty->fetch($this->registry->smartyModule . 'index.tpl');
        $this->registry->response->setContent($contents);
    }
}
