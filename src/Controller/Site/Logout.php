<?php

namespace Controller\Site;

use Litpi\Response;
use Litpi\Cookie;

class Logout extends BaseController
{

    public function indexAction()
    {
        $this->doLogout();

        if ($this->registry->request->query->get('from') == 'admin') {
            $this->registry->response->headers->set('Location', $this->registry->conf['rooturl_admin'] . 'login');
        }

    }
}
