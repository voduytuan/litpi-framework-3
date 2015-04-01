<?php

namespace Controller\Admin;

class ForgotPass extends BaseController
{

    public function indexAction()
    {
        //Extracting RedirectURL
        $redirectUrl = $this->getBag->get('redirect');//base64 encoded
        if (strlen($redirectUrl) > 0) {
            $redirectUrl = base64_decode($redirectUrl);
        } elseif ($this->getBag->has('returnurl') && $this->getBag->get('returnurl') != '') {
            $redirectUrl = urldecode($this->getBag->get('returnurl'));
        } else {
            $redirectUrl = $this->registry->conf['rooturl'];
        }

        $error = $warning = $formData = $success = array();
        if ($this->postBag->has('fsubmit')) {
            $formData = $this->postBag->all();

            if ($this->submitValidate($formData, $error)) {
                $myUser = \Model\User::getByEmail($formData['femail']);

                if ($myUser->id > 0) {

                    //xu ly de tai activatedcode cho viec change password
                    $code = $myUser->id . $myUser->email . rand(1000, 9999) . time()
                        . \Litpi\ViephpHashing::$secretString;
                    $activatedCode = md5($code);
                    $myUser->activatedcode = $activatedCode;

                    if ($myUser->updateData(array(), $error)) {
                        $this->registry->session->set('forgotpassSpam', time());

                        //tien hanh goi email
                        //send mail to user
                        $this->registry->smarty->assign(array(
                            'activatedCode' => $activatedCode,
                            'myUser' => $myUser
                        ));
                        $mailContents = $this->registry->smarty->fetch(
                            $this->registry->smartyMail . 'forgotpass/user.tpl'
                        );
                        $sender = new \Litpi\SendMail(
                            $this->registry,
                            $myUser->email,
                            $myUser->fullname,
                            'Reset Password Information from ' . $this->registry->conf['host'],
                            $mailContents,
                            'youremail@example.com',
                            'Your Name'
                        );

                        if ($sender->send()) {
                            $success[] = 'Check your email for reset password process.';
                        } else {
                            $error[] = 'Error while sending your email.';
                        }

                    }//end updateData()
                }

            }

        }//end submit

        $this->registry->session->set('forgotpassToken', \Litpi\Helper::getSecurityToken());

        $this->registry->smarty->assign(array(
            'formData' => $formData,
            'error' => $error,
            'success' => $success,
            'warning' => $warning,
            'redirectUrl' => $redirectUrl,
            'redirectUrlEncode' => base64_encode($redirectUrl)
        ));

        $contents = $this->registry->smarty->fetch($this->registry->smartyController . 'index.tpl');
        $this->registry->response->setContent($contents);

    }

    public function resetAction()
    {
        //Extracting RedirectURL
        $redirectUrl = $this->getBag->get('redirect');//base64 encoded
        if (strlen($redirectUrl) > 0) {
            $redirectUrl = base64_decode($redirectUrl);
        } elseif ($this->getBag->has('returnurl') && $this->getBag->get('returnurl') != '') {
            $redirectUrl = urldecode($this->getBag->get('returnurl'));
        } else {
            $redirectUrl = $this->registry->conf['rooturl'];
        }

        $error = $warning = $formData = array();
        $email = $this->getBag->get('email');
        $activatedCode = $this->getBag->get('code');

        //Found user by email
        $myUser = \Model\User::getByEmail(urldecode($email));

        if ($myUser->id > 0) {
            if ($myUser->activatedcode != $activatedCode && false) {
                $this->notfound();
            } else {
                if ($this->postBag->has('fsubmit')) {
                    $formData = $this->postBag->all();

                    if ($formData['fpassword'] != $formData['fpassword2']) {
                        $error[] = 'Passwords are not matched.';
                    } else {
                        if (strlen($formData['fpassword']) < 6) {
                            $error[] = 'Password is at least 6 characters.';
                        } else {
                            $myUser->newpass = $this->postBag->get('fpassword');
                            $myUser->activatedcode = '';

                            if ($myUser->updateData()) {
                                $success[] = 'Your password had been saved.';

                                $successUrl = $this->registry->conf['rooturl']
                                    . 'login?from=forgotpass&email=' . $myUser->email
                                    . '&redirect=' . base64_encode($redirectUrl);
                                $this->doRedirect($successUrl);
                            } else {
                                $error[] = 'Error while changing your password';
                            }
                        }
                    } //end validate form
                } //end submit

                if (empty($success)) {
                    $this->registry->smarty->assign(array(
                        'formData' => $formData,
                        'myUser' => $myUser,
                        'error' => $error,
                        'warning' => $warning,
                        'redirectUrlEncode' => base64_encode($redirectUrl),
                    ));

                    $contents = $this->registry->smarty->display($this->registry->smartyController . 'reset.tpl');
                    $this->registry->response->setContent($contents);
                }

            }
        } else {
            $this->notfound();
        }
    }

    protected function submitValidate($formData, &$error)
    {
        $pass = true;
        //check form token
        if ($formData['ftoken'] != $this->registry->session->get('forgotpassToken')) {
            $pass = false;
            $error[] = $this->registry->lang['default']['securityTokenInvalid'];
        }

        //check spam
        $forgotpassExpire = 10; //seconds
        if ($this->registry->session->has('forgotpassSpam')
            && time() - $this->registry->session->get('forgotpassSpam') < $forgotpassExpire) {
            $error[] = $this->registry->lang['controller']['errSpam'];
            $pass = false;
        }

        //check email length
        if (!\Litpi\Helper::validateEmail($formData['femail'])) {
            $error[] = $this->registry->lang['controller']['errInvalidEmail'];
            $pass = false;
        } else {
            $myUser = \Model\User::getUsers(array('femail' => $formData['femail']));
            if ($myUser[0]->id == 0) {
                $error[] = $this->registry->lang['controller']['errAccountInvalid'];
                $pass = false;
            }
        }

        return $pass;
    }
}
