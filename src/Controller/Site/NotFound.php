<?php

namespace Controller\Site;

class NotFound extends BaseController
{
    public function indexAction()
    {
        $this->registry->response->setStatusCode(404);
        $this->registry->response->setContent(file_get_contents('./404.html'));
    }
}
