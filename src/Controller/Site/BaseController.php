<?php

namespace Controller\Site;

abstract class BaseController extends \Controller\BaseController
{
    public function __construct($registry)
    {
        parent::__construct($registry);
    }

    protected function notfound()
    {
        $this->registry->response->setStatusCode(404);
        $this->registry->response->setContent(file_get_contents('./404.html'));
    }
}
