<?php

namespace Controller\Site;

class Install extends BaseController
{
    public function indexAction()
    {
        if (!$this->checkinstallrequirement()) {
            die('Install Error. First User Account Already Existed.
              You can remove this install script from Site Controller');
        } else {
            $success = $error = $formData = array();

            if (isset($_POST['fsubmit'])) {
                $formData = array_merge($formData, $_POST);

                if ($this->installValidator($formData, $error)) {

                    $tableExisted = false;
                    if ($this->usertablesExists()) {
                        $tableExisted = true;
                    } else {

                        try {

                            //Create Table ac_user
                            $sql = '
                                    CREATE TABLE IF NOT EXISTS `'.TABLE_PREFIX.'ac_user` (
                                      `u_id` int(11) NOT NULL AUTO_INCREMENT,
                                      `u_screenname` varchar(32) NOT NULL,
                                      `u_fullname` varchar(50) NOT NULL,
                                      `u_avatar` varchar(128) NOT NULL,
                                      `u_groupid` smallint(2) NOT NULL DEFAULT 0,
                                      `u_region` int(11) NOT NULL DEFAULT 0,
                                      `u_gender` smallint(1) NOT NULL DEFAULT 0,
                                      `u_view` int(11) NOT NULL DEFAULT 0,
                                      `u_datelastaction` int(10) NOT NULL DEFAULT 0,
                                      `u_parentid` int(11) NOT NULL DEFAULT 0,
                                      `u_skype` varchar(50) NOT NULL,
                                      PRIMARY KEY (`u_id`),
                                      KEY `u_screenname` (`u_screenname`),
                                      KEY `u_fullname` (`u_fullname`),
                                      KEY `u_groupid` (`u_groupid`),
                                      KEY `u_region` (`u_region`),
                                      KEY `u_gender` (`u_gender`),
                                      KEY `u_view` (`u_view`),
                                      KEY `u_datelastaction` (`u_datelastaction`),
                                      KEY `u_parentid` (`u_parentid`)
                                    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;';
                            $stmt = $this->registry->db->query($sql);
                            if ($stmt) {
                                $sql = '
                                        CREATE TABLE IF NOT EXISTS `'.TABLE_PREFIX.'ac_user_profile` (
                                          `u_id` int(11) NOT NULL,
                                          `up_email` varchar(50) NOT NULL,
                                          `up_password` text NOT NULL,
                                          `up_birthday` date NOT NULL,
                                          `up_phone` varchar(20) NOT NULL,
                                          `up_address` varchar(255) NOT NULL,
                                          `up_city` varchar(32) NOT NULL,
                                          `up_country` varchar(2) NOT NULL,
                                          `up_website` varchar(50) NOT NULL,
                                          `up_bio` varchar(255) NOT NULL,
                                          `up_activatedcode` varchar(32) NOT NULL,
                                          `up_datecreated` int(10) NOT NULL DEFAULT 0,
                                          `up_datemodified` int(10) NOT NULL DEFAULT 0,
                                          `up_datelastlogin` int(10) NOT NULL DEFAULT 0,
                                          `up_oauth_partner` smallint(2) NOT NULL DEFAULT 0,
                                          `up_oauth_uid` varchar(50) NOT NULL DEFAULT 0,
                                          `up_ipaddress` int(11) NOT NULL DEFAULT 0,
                                          PRIMARY KEY (`u_id`),
                                          KEY `up_email` (`up_email`),
                                          KEY `up_country` (`up_country`),
                                          KEY `up_oauth_partner` (`up_oauth_partner`),
                                          KEY `up_oauth_uid` (`up_oauth_uid`),
                                          KEY `up_ipaddress` (`up_ipaddress`)
                                        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;';

                                $stmt = $this->registry->db->query($sql);
                                if ($stmt) {
                                    //two table create ok
                                    $tableExisted = true;
                                }
                            }
                        } catch (\PDOException $e) {
                            $error[] = 'Error while creating main user tables. <br />(Error Code: ' .
                                $e->getCode() . ', <br />Error Message: ' . $e->getMessage() . ')';
                        }
                    }

                    if ($tableExisted) {
                        //begin create new account
                        $myUser = new \Model\User();
                        $myUser->fullname = $formData['ffullname'];
                        $myUser->email = $formData['femail'];
                        $myUser->password = \Litpi\ViephpHashing::hash($formData['fpassword']);
                        $myUser->groupid = GROUPID_ADMIN;

                        if ($myUser->addData()) {
                            $success[] = 'Administrator Account had been created.';
                            $adminRedirectUrl = base64_encode($this->registry->conf['rooturl_admin']);
                            $this->registry->smarty->assign(array(
                                'adminRedirectUrl' => $adminRedirectUrl,
                            ));
                        } else {
                            $error[] = 'Error while creating Administrator Account. Please try again.';
                        }

                    }
                }

                $this->registry->smarty->assign(array(
                    'error' => $error,
                    'success' => $success,
                    'formData' => $formData
                ));
            }

            $contents = $this->registry->smarty->fetch($this->registry->smartyController.'index.tpl');
            $this->registry->response->setContent($contents);
        }
    }

    private function installValidator($formData, &$error)
    {
        $pass = true;

        if (strlen($formData['ffullname']) == 0) {
            $pass = false;
            $error[] = 'Administrator Fullname is required.';
        }

        if (!\Litpi\Helper::validateEmail($formData['femail'])) {
            $pass = false;
            $error[] = 'Administrator Email is not valid.';
        }

        if (strlen($formData['fpassword']) == 0) {
            $pass = false;
            $error[] = 'Administrator Password is required.';
        }

        if (strcmp($formData['fpassword'], $formData['fpassword2']) != 0) {
            $pass = false;
            $error[] = 'Password and confirm password is not match.';
        }

        return $pass;
    }

    private function usertablesExists()
    {
        $tables = array();
        $stmt = $this->registry->db->query('SHOW TABLES');
        while ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            $tables[] = $row[0];
        }

        return in_array(TABLE_PREFIX . 'ac_user', $tables) && in_array(TABLE_PREFIX . 'ac_user_profile', $tables);

    }

    /**
     * Check if there is no user in system before run install
     */
    private function checkinstallrequirement()
    {

        $needInstall = false;

        //Check User tables exists
        if (!$this->usertablesExists()) {
            $needInstall = true;
        } else {
            $userCount = \Model\User::getUsers(array(), '', '', '', true);

            if ($userCount > 0) {
                $needInstall = false;
            } else {
                $needInstall = true;
            }
        }

        return $needInstall;
    }
}
