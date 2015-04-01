<?php

namespace Controller\Site;

class Index extends BaseController
{
    public function indexAction()
    {
        $contents = $this->registry->smarty->fetch($this->registry->smartyController.'index.tpl');

        $this->registry->smarty->assign(array('contents' => $contents));

        $contents = $this->registry->smarty->fetch($this->registry->smartyModule.'index.tpl');
        $this->registry->response->setContent($contents);

    }
}
