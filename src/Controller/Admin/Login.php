<?php

namespace Controller\Admin;

class Login extends BaseController
{
    public function indexAction()
    {
        $error = $warning = $formData = array();

        $redirectUrl = $this->registry->request->query->get('redirect');//base64 encoded

        $isLoginSuccess = false;

        if ($this->registry->request->request->has('fsubmit')) {
            $formData = array_merge($formData, $this->registry->request->request->all());

            $myUser = \Model\User::getByEmail($formData['femail']);

            if ($myUser->id > 0 && $myUser->password == \Litpi\ViephpHashing::hash($formData['fpassword'])) {

                $isLoginSuccess = true;

                $redirectUrl = $this->doLogin($myUser->id, $formData['fpassword'], $redirectUrl);

                $this->doRedirect($redirectUrl);

            } else {
                $error[] = $this->registry->lang['controller']['errAccountInvalid'];
            }
        }

        if (!$isLoginSuccess) {

            $this->registry->smarty->assign(array(
                'formData' => $formData,
                'error' => $error,
                'redirectUrl' => $redirectUrl,
            ));

            $contents = $this->registry->smarty->fetch($this->registry->smartyController . 'index.tpl');
            $this->registry->response->setContent($contents);

        }
    }
}
