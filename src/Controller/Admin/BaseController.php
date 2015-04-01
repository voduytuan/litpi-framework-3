<?php

namespace Controller\Admin;

abstract class BaseController extends \Controller\BaseController
{
    public function __construct($registry)
    {
        parent::__construct($registry);

        if (is_null($registry->myUser)) {
            $registry->myUser = $registry->me;
            $registry->smarty->assign('myUser', $registry->me);
        }

    }
}
