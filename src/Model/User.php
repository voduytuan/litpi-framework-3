<?php

namespace Model;

use Litpi\Helper as Helper;
use Litpi\ImageResizer as ImageResizer;
use Litpi\Cacher as Cacher;

class User extends BaseModel
{
    const OAUTH_PARTNER_EMPTY = 0;
    const OAUTH_PARTNER_FACEBOOK = 1;
    const OAUTH_PARTNER_YAHOO = 2;
    const OAUTH_PARTNER_GOOGLE = 3;

    const GENDER_UNKNOWN = 0;
    const GENDER_MALE = 1;
    const GENDER_FEMALE = 2;

    public $id = 0;
    public $screenname = '';
    public $fullname = '';
    public $avatar = '';
    public $avatarCurrent = '';
    public $groupid = 0;
    public $gender = 'unknown';
    public $region = 0;

    public $view = 0;
    public $datelastaction = 0;
    public $email = '';
    public $password = '';
    public $birthday = '0000-00-00';
    public $phone = '';
    public $address = '';
    public $city = '';
    public $country = 'VN';
    public $website = '';
    public $bio = '';
    public $activatedcode = '';
    public $datecreated = 0;
    public $datemodified = 0;
    public $datelastlogin = 0;
    public $parentid = 0;
    public $skype = '';

    public $oauthPartner = 0;
    public $oauthUid = 0;

    public $ipaddress = '';    //dia chi IP register user

    public $newpass = '';
    public $sessionid = '';
    public $userpath = '';

    // dynamic
    public $internalid = "";
    public $department = "";

    public function __construct($id = 0, $loadFromCache = false)
    {
        parent::__construct();
        $this->sessionid = $this->getSessionId();

        if ($id > 0) {
            if ($loadFromCache) {
                $this->copy(self::cacheGet($id));
            } else {
                $this->getData($id);
            }
        }
    }

    public function checkPerm($registry, &$notfound = false, &$notlogin = false)
    {
        $groupPermisson = $registry->groupPermisson;
        $conf = $registry->conf;

        $module = strtolower($registry->module);
        $controller = strtolower($registry->controller);
        $action = strtolower($registry->action);

        $result = false;
        if (!isset($groupPermisson[$this->groupid][$module]) ||
            (!in_array($controller . '_' . $action, $groupPermisson[$this->groupid][$module])
                && !in_array($controller . '_*', $groupPermisson[$this->groupid][$module]))
        ) {
            if ($this->id > 0) {
                $notfound = true;
            } else {
                $notlogin = true;
            }
        } else {
            $result = true;
        }

        return $result;
    }

    public function getFullProfileCache()
    {
        $registry = \Litpi\Registry::getInstance();

        $myCacher = new Cacher(self::cacheBuildKeystringDetail($this->id));
        $userInfo = $myCacher->get();
        if (!$userInfo || $registry->get('request')->query->has('live')) {
            $sql = 'SELECT * FROM lit_ac_user u
                    INNER JOIN lit_ac_user_profile up ON u.u_id = up.u_id
                    WHERE u.u_id = ?';
            $userInfo = $this->db->query($sql, array($this->id))->fetch();
            $myCacher->set($userInfo, 3600);
        }

        $this->getDataByArray($userInfo);
    }

    /**
     * Lay thong tin user tu session (danh cho user da login hoac su dung remember me
     *
     */
    public function updateFromSession($registry)
    {
        $setting = $registry->setting;


        if ($registry->session->has('userLogin') && $registry->session->get('userLogin') > 0) {

            //New way
            $userid = (int)$registry->session->get('userLogin');

            $myCacher = new Cacher(self::cacheBuildKeystringDetail($userid));
            $userInfo = $myCacher->get();
            if (!$userInfo || $registry->request->query->has('live')) {
                $sql = 'SELECT * FROM lit_ac_user u
                        INNER JOIN lit_ac_user_profile up ON u.u_id = up.u_id
                        WHERE u.u_id = ?';
                $userInfo = $this->db->query($sql, array($userid))->fetch();
                $myCacher->set($userInfo, 3600);
            }

            $this->getDataByArray($userInfo);
        } elseif ($registry->request->cookies->has('myHashing')
            && strlen($registry->request->cookies->get('myHashing')) > 0
        ) {


            $cookieRememberMeInfo = \Litpi\ViephpHashing::cookiehasingParser(
                $registry->request->cookies->get('myHashing')
            );

            $myUserTmp = new self();
            $myUserTmp->getData($cookieRememberMeInfo['userid']);

            if (\Litpi\ViephpHashing::authenticateCookiehashing(
                $cookieRememberMeInfo['shortPasswordString'],
                $myUserTmp->password
            )
            ) {
                $registry->session->migrate();

                $this->copy($myUserTmp);

                ////////////////////////////////////////////////////////////////////////////////////
                //UPDATE LAST LOGIN TIME
                $sql = 'UPDATE ' . TABLE_PREFIX . 'ac_user_profile
                        SET up_datelastlogin = ?
                        WHERE u_id = ?
                        LIMIT 1';
                $this->db->query($sql, array(time(), $this->id));

                $registry->session->set('userLogin', $this->id);
                $registry->session->set('loginauto', 1);
            }

        }
    }

    public function addData()
    {
        $registry = \Litpi\Registry::getInstance();
        $setting = $registry->get('setting');

        $this->datecreated = time();

        $sql = 'INSERT INTO ' . TABLE_PREFIX . 'ac_user (
                    u_screenname,
                    u_fullname,
                    u_avatar,
                    u_groupid,
                    u_region,
                    u_gender,
                    u_parentid,
                    u_skype
                )
                VALUES(?, ?, ?, ?, ?, ?, ?, ?)';

        $this->db->query($sql, array(
            (string)$this->screenname,
            (string)$this->fullname,
            '',
            (int)$this->groupid,
            (int)$this->region,
            (int)$this->gender,
            (int)$this->parentid,
            (string)$this->skype,
        ));

        $this->id = $this->db->lastInsertId();

        if ($this->id > 0) {
            $sql = 'INSERT INTO ' . TABLE_PREFIX . 'ac_user_profile (
                        u_id,
                        up_email,
                        up_password,
                        up_birthday,
                        up_phone,
                        up_address,
                        up_city,
                        up_country,
                        up_website,
                        up_bio,
                        up_activatedcode,
                        up_datecreated,
                        up_oauth_partner,
                        up_oauth_uid,
                        up_ipaddress
                    )
                    VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? , ?, ?)';
            $this->db->query($sql, array(
                (int)$this->id,
                (string)$this->email,
                (string)$this->password,
                (string)$this->birthday,
                (string)$this->phone,
                (string)$this->address,
                (string)$this->city,
                (string)strtoupper($this->country),
                (string)$this->website,
                (string)$this->bio,
                (string)$this->activatedcode,
                (int)$this->datecreated,
                (int)$this->oauthPartner,
                (string)$this->oauthUid,
                Helper::getIpAddress(true),
            ));


            if (strpos($this->avatar, 'https') === 0) {//set to download from remote image

                $originalImagePath = $this->avatar;
                $curDateDir = Helper::getCurrentDateDirName();
                $extPart = substr(strrchr($originalImagePath, '.'), 1);
                $namePart = $this->id . time();
                $name = $namePart . '.' . $extPart;
                $fullpath = $setting['avatar']['imageDirectory'] . $curDateDir . $name;

                //check existed directory
                if (!file_exists($setting['avatar']['imageDirectory'] . $curDateDir)) {
                    mkdir($setting['avatar']['imageDirectory'] . $curDateDir, 0777, true);
                }

                if (Helper::saveExternalFile($originalImagePath, $fullpath, 'image')) {

                    //Resize big image if needed
                    $myImageResizer = new ImageResizer(
                        $setting['avatar']['imageDirectory'] . $curDateDir,
                        $name,
                        $setting['avatar']['imageDirectory'] . $curDateDir,
                        $name,
                        $setting['avatar']['imageMaxWidth'],
                        $setting['avatar']['imageMaxHeight'],
                        '1:1',
                        $setting['avatar']['imageQuality']
                    );
                    $myImageResizer->output();
                    unset($myImageResizer);

                    //Create medium image
                    $nameMediumPart = substr($name, 0, strrpos($name, '.'));
                    $nameMedium = $nameMediumPart . '-medium.' . $extPart;
                    $myImageResizer = new ImageResizer(
                        $setting['avatar']['imageDirectory'] . $curDateDir,
                        $name,
                        $setting['avatar']['imageDirectory'] . $curDateDir,
                        $nameMedium,
                        $setting['avatar']['imageMediumWidth'],
                        $setting['avatar']['imageMediumHeight'],
                        '1:1',
                        $setting['avatar']['imageQuality']
                    );
                    $myImageResizer->output();
                    unset($myImageResizer);

                    //Create thumb image
                    $nameThumbPart = substr($name, 0, strrpos($name, '.'));
                    $nameThumb = $nameThumbPart . '-small.' . $extPart;
                    $myImageResizer = new ImageResizer(
                        $setting['avatar']['imageDirectory'] . $curDateDir,
                        $name,
                        $setting['avatar']['imageDirectory'] . $curDateDir,
                        $nameThumb,
                        $setting['avatar']['imageThumbWidth'],
                        $setting['avatar']['imageThumbHeight'],
                        '1:1',
                        $setting['avatar']['imageQuality']
                    );
                    $myImageResizer->output();
                    unset($myImageResizer);

                    //update database
                    $this->avatar = $curDateDir . $name;
                    $this->updateAvatar();
                } else {
                    $this->avatar = '';
                }
            }//end download avatar
        }

        return $this->id;
    }

    public function updateData($moreFields = array(), &$error = array())
    {
        $registry = \Litpi\Registry::getInstance();
        $setting = $registry->get('setting');

        $this->datemodified = time();


        if ((isset($moreFields['fullname']) && strcmp($this->fullname, $moreFields['fullname']) != 0) ||
            (isset($moreFields['screenname']) && strcmp($this->screenname, $moreFields['screenname']) != 0) ||
            (isset($moreFields['groupid']) && $this->groupid != $moreFields['groupid']) ||
            (isset($moreFields['region']) && $this->region != $moreFields['region']) ||
            (isset($moreFields['gender']) && $this->gender != $moreFields['gender']) ||
            (isset($moreFields['parentid']) && $this->parentid != $moreFields['parentid']) ||
            (isset($moreFields['skype']) && $this->skype != $moreFields['skype']) ||
            (isset($moreFields['datelastaction']) && $this->datelastaction != $moreFields['datelastaction'])
        ) {

            if (isset($moreFields['screenname'])) {
                $this->screenname = strtolower($moreFields['screenname']);
            }

            if (isset($moreFields['fullname'])) {
                $this->fullname = $moreFields['fullname'];
            }

            if (isset($moreFields['groupid'])) {
                $this->groupid = (int)$moreFields['groupid'];
            }

            if (isset($moreFields['region'])) {
                $this->region = (int)$moreFields['region'];
            }

            if (isset($moreFields['gender'])) {
                $this->gender = (int)$moreFields['gender'];
            }

            if (isset($moreFields['datelastaction'])) {
                $this->datelastaction = (int)$moreFields['datelastaction'];
            }

            if (isset($moreFields['parentid'])) {
                $this->parentid = (int)$moreFields['parentid'];
            }

            if (isset($moreFields['skype'])) {
                $this->skype = (string)$moreFields['skype'];
            }

            $sql = 'UPDATE ' . TABLE_PREFIX . 'ac_user
                    SET u_screenname = ?,
                        u_fullname = ?,
                        u_groupid = ?,
                        u_region = ?,
                        u_gender = ?,
                        u_datelastaction = ?,
                        u_parentid = ?,
                        u_skype = ?
                    WHERE u_id = ?
                    LIMIT 1';
            $this->db->query($sql, array(
                (string)$this->screenname,
                (string)$this->fullname,
                (int)$this->groupid,
                (int)$this->region,
                (int)$this->gender,
                (int)$this->datelastaction,
                (int)$this->parentid,
                (string)$this->skype,
                $this->id
            ));
        }

        $bindParams = array();
        $moreupdate = '';
        if (strlen($this->newpass) > 0) {
            $moreupdate = 'up_password = ? ,';
            $bindParams[] = \Litpi\ViephpHashing::hash($this->newpass);
        }

        $bindParams = array_merge($bindParams, array(
            (string)$this->email,
            (string)$this->birthday,
            (string)$this->phone,
            (string)$this->address,
            (string)$this->city,
            (string)strtoupper($this->country),
            (string)$this->website,
            (string)$this->bio,
            (string)$this->activatedcode,
            (int)$this->datemodified,
            (int)$this->oauthPartner,
            (int)$this->oauthUid,
            (int)$this->id
        ));

        $sql = 'UPDATE ' . TABLE_PREFIX . 'ac_user_profile
                SET ' . $moreupdate . '
                    up_email = ?,
                    up_birthday = ?,
                    up_phone = ?,
                    up_address = ?,
                    up_city = ?,
                    up_country = ?,
                    up_website = ?,
                    up_bio = ?,
                    up_activatedcode = ?,
                    up_datemodified = ? ,
                    up_oauth_partner = ?,
                    up_oauth_uid = ?
                WHERE u_id = ?';

        $stmt = $this->db->query($sql, $bindParams);

        if ($stmt->rowCount() > 0) {

            if (strpos($this->avatar, 'https') === 0) {//set to download from remote image

                $originalImagePath = $this->avatar;
                $curDateDir = Helper::getCurrentDateDirName();
                $extPart = substr(strrchr($originalImagePath, '.'), 1);
                $namePart = $this->id . time();
                $name = $namePart . '.' . $extPart;
                $fullpath = $setting['avatar']['imageDirectory'] . $curDateDir . $name;

                //check existed directory
                if (!file_exists($setting['avatar']['imageDirectory'] . $curDateDir)) {
                    mkdir($setting['avatar']['imageDirectory'] . $curDateDir, 0777, true);
                }

                if (Helper::saveExternalFile($originalImagePath, $fullpath, 'image')) {
                    //Resize big image if needed
                    $myImageResizer = new ImageResizer(
                        $setting['avatar']['imageDirectory'] . $curDateDir,
                        $name,
                        $setting['avatar']['imageDirectory'] . $curDateDir,
                        $name,
                        $setting['avatar']['imageMaxWidth'],
                        $setting['avatar']['imageMaxHeight'],
                        '1:1',
                        $setting['avatar']['imageQuality']
                    );
                    $myImageResizer->output();
                    unset($myImageResizer);

                    //Create medium image
                    $nameMediumPart = substr($name, 0, strrpos($name, '.'));
                    $nameMedium = $nameMediumPart . '-medium.' . $extPart;
                    $myImageResizer = new ImageResizer(
                        $setting['avatar']['imageDirectory'] . $curDateDir,
                        $name,
                        $setting['avatar']['imageDirectory'] . $curDateDir,
                        $nameMedium,
                        $setting['avatar']['imageMediumWidth'],
                        $setting['avatar']['imageMediumHeight'],
                        '1:1',
                        $setting['avatar']['imageQuality']
                    );
                    $myImageResizer->output();
                    unset($myImageResizer);

                    //Create thumb image
                    $nameThumbPart = substr($name, 0, strrpos($name, '.'));
                    $nameThumb = $nameThumbPart . '-small.' . $extPart;
                    $myImageResizer = new ImageResizer(
                        $setting['avatar']['imageDirectory'] . $curDateDir,
                        $name,
                        $setting['avatar']['imageDirectory'] . $curDateDir,
                        $nameThumb,
                        $setting['avatar']['imageThumbWidth'],
                        $setting['avatar']['imageThumbHeight'],
                        '1:1',
                        $setting['avatar']['imageQuality']
                    );
                    $myImageResizer->output();
                    unset($myImageResizer);

                    //update database
                    $this->avatar = $curDateDir . $name;
                    $this->updateAvatar();
                } else {
                    $this->avatar = '';
                }
            }//end download avatar

            //Clear detail cache
            self::cacheDeleteDetail($this->id);

            return true;
        } else {

            $error[] = 'Unknown error.';
            return false;
        }

    }

    /**
     * Resize avatar goc hoac tao cac phien ban thu nho cua avatar
     *
     */
    public function postProcessingAvatar($curDateDir, $name)
    {
        $registry = \Litpi\Registry::getInstance();
        $setting = $registry->get('setting');

        $extPart = Helper::fileExtension($name);

        //Resize big image if needed
        $myImageResizer = new ImageResizer(
            $setting['avatar']['imageDirectory'] . $curDateDir,
            $name,
            $setting['avatar']['imageDirectory'] . $curDateDir,
            $name,
            $setting['avatar']['imageMaxWidth'],
            $setting['avatar']['imageMaxHeight'],
            '',
            $setting['avatar']['imageQuality']
        );
        $myImageResizer->output();
        unset($myImageResizer);

        //Create medium image
        $nameMediumPart = substr($name, 0, strrpos($name, '.'));
        $nameMedium = $nameMediumPart . '-medium.' . $extPart;
        $myImageResizer = new ImageResizer(
            $setting['avatar']['imageDirectory'] . $curDateDir,
            $name,
            $setting['avatar']['imageDirectory'] . $curDateDir,
            $nameMedium,
            $setting['avatar']['imageMediumWidth'],
            $setting['avatar']['imageMediumHeight'],
            '',
            $setting['avatar']['imageQuality']
        );
        $myImageResizer->output();
        unset($myImageResizer);

        //Create thum image
        $nameThumbPart = substr($name, 0, strrpos($name, '.'));
        $nameThumb = $nameThumbPart . '-small.' . $extPart;
        $myImageResizer = new ImageResizer(
            $setting['avatar']['imageDirectory'] . $curDateDir,
            $name,
            $setting['avatar']['imageDirectory'] . $curDateDir,
            $nameThumb,
            $setting['avatar']['imageThumbWidth'],
            $setting['avatar']['imageThumbHeight'],
            '1:1',
            $setting['avatar']['imageQuality']
        );
        $myImageResizer->output();
        unset($myImageResizer);
    }

    /**
     * Apply medium and thumb image after crop image
     *
     */
    public function postCroppingAvatar()
    {
        $registry = \Litpi\Registry::getInstance();
        $setting = $registry->get('setting');

        //generate new image filename
        $curImage = $this->avatar;

        $extPart = Helper::fileExtension($this->avatar);
        $namePart = Helper::codau2khongdau($this->fullname, true) . '-' . $this->id . '-' . time();
        $newAvatar = $namePart . '.' . $extPart;

        $newAvatarMedium = $namePart . '-medium.' . $extPart;
        $newAvatarSmall = $namePart . '-small.' . $extPart;
        $currentDir = Helper::getCurrentDateDirName();
        if (!file_exists($setting['avatar']['imageDirectory'] . $currentDir)) {
            mkdir($setting['avatar']['imageDirectory'] . $currentDir, 0777);
        }

        //rename originalimage to new location
        if (rename(
            $setting['avatar']['imageDirectory'] . $this->avatar,
            $setting['avatar']['imageDirectory'] . $currentDir . $newAvatar
        )) {
            //Create medium image
            $myImageResizer = new ImageResizer(
                $setting['avatar']['imageDirectory'],
                $this->mediumImage(),
                $setting['avatar']['imageDirectory'],
                $currentDir . $newAvatarMedium,
                $setting['avatar']['imageMediumWidth'],
                $setting['avatar']['imageMediumHeight'],
                '',
                $setting['avatar']['imageQuality']
            );
            $myImageResizer->output();
            unset($myImageResizer);

            //Create thum image
            $myImageResizer = new ImageResizer(
                $setting['avatar']['imageDirectory'],
                $this->mediumImage(),
                $setting['avatar']['imageDirectory'],
                $currentDir . $newAvatarSmall,
                $setting['avatar']['imageThumbWidth'],
                $setting['avatar']['imageThumbHeight'],
                '1:1',
                $setting['avatar']['imageQuality']
            );
            $myImageResizer->output();
            unset($myImageResizer);

            //delete current old medium image
            @unlink($setting['avatar']['imageDirectory'] . $this->mediumImage());
            @unlink($setting['avatar']['imageDirectory'] . $this->thumbImage());

            $this->avatar = $currentDir . $newAvatar;
            $sql = 'UPDATE ' . TABLE_PREFIX . 'ac_user SET u_avatar = ? WHERE u_id = ?';
            $this->db->query($sql, array($this->avatar, $this->id));

            //Clear cache of detail
            self::cacheDeleteDetail($this->id);

        } else {
            //something wrong with copy, keep original info
            //Create medium image
            $myImageResizer = new ImageResizer(
                $setting['avatar']['imageDirectory'],
                $this->mediumImage(),
                $setting['avatar']['imageDirectory'],
                $this->mediumImage(),
                $setting['avatar']['imageMediumWidth'],
                $setting['avatar']['imageMediumHeight'],
                '',
                $setting['avatar']['imageQuality']
            );
            $myImageResizer->output();
            unset($myImageResizer);

            //Create thum image
            $myImageResizer = new ImageResizer(
                $setting['avatar']['imageDirectory'],
                $this->mediumImage(),
                $setting['avatar']['imageDirectory'],
                $this->thumbImage(),
                $setting['avatar']['imageThumbWidth'],
                $setting['avatar']['imageThumbHeight'],
                '1:1',
                $setting['avatar']['imageQuality']
            );
            $myImageResizer->output();
            unset($myImageResizer);

            //clear cache of detail
            self::cacheDeleteDetail($this->id);
        }
    }

    public function updateAvatar()
    {
        //update profile table
        $sql = 'UPDATE ' . TABLE_PREFIX . 'ac_user
                SET u_avatar = ?
                WHERE u_id = ?';

        $this->db->query($sql, array(
            (string)$this->avatar,
            (string)$this->id
        ));

        //Clear detail cache
        self::cacheDeleteDetail($this->id);
    }

    public function deleteImage($imagepath = '')
    {
        $registry = \Litpi\Registry::getInstance();
        $setting = $registry->get('setting');

        //delete current image
        if ($imagepath == '') {
            $deletefile = $this->avatar;
        } else {
            $deletefile = $imagepath;
        }

        if (strlen($deletefile) > 0) {
            $file = $setting['avatar']['imageDirectory'] . $deletefile;
            if (file_exists($file) && is_file($file)) {
                @unlink($file);

                //delete thumb image
                $extPart = substr(strrchr($deletefile, '.'), 1);
                $nameThumbPart = substr($deletefile, 0, strrpos($deletefile, '.'));
                $nameThumb = $nameThumbPart . '-small.' . $extPart;
                $filethumb = $setting['avatar']['imageDirectory'] . $nameThumb;
                if (file_exists($filethumb) && is_file($filethumb)) {
                    @unlink($filethumb);
                }

                //delete medium image
                $nameMedium = $nameThumbPart . '-medium.' . $extPart;
                $filemedium = $setting['avatar']['imageDirectory'] . $nameMedium;
                if (file_exists($filemedium) && is_file($filemedium)) {
                    @unlink($filemedium);
                }
            }

            //delete current image
            if ($imagepath == '') {
                $this->avatar = '';
            }

            $this->updateAvatar();
        }
    }

    /**
     * Ngoai viec login thanh cong,
     * tien hanh cap nhat cac thong tin stat cua user nay
     *
     */
    public function updateLastLogin()
    {
        //update profile table
        $sql = 'UPDATE ' . TABLE_PREFIX . 'ac_user_profile
                SET up_datelastlogin = ?
                WHERE u_id = ?';

        $stmt = $this->db->query($sql, array(time(), $this->id));

        if ($stmt) {
            return true;
        } else {
            return false;
        }
    }

    public function getData($id)
    {
        $id = (int)$id;

        $sql = 'SELECT * FROM ' . TABLE_PREFIX . 'ac_user u
                INNER JOIN ' . TABLE_PREFIX . 'ac_user_profile up ON u.u_id = up.u_id
                WHERE u.u_id = ?';
        $row = $this->db->query($sql, array($id))->fetch();
        $this->getDataByArray($row);
    }

    public function getDataByArray($row)
    {
        $this->id = $row['u_id'];
        $this->screenname = $row['u_screenname'];
        $this->fullname = $row['u_fullname'];
        $this->avatar = $row['u_avatar'];
        $this->groupid = $row['u_groupid'];
        $this->region = $row['u_region'];
        $this->gender = $row['u_gender'];
        $this->view = $row['u_view'];
        $this->datelastaction = $row['u_datelastaction'];
        $this->parentid = $row['u_parentid'];
        $this->skype = $row['u_skype'];

        $this->email = $row['up_email'];
        $this->password = $row['up_password'];
        $this->birthday = $row['up_birthday'];
        $this->phone = $row['up_phone'];
        $this->address = $row['up_address'];
        $this->city = $row['up_city'];
        $this->country = $row['up_country'];
        $this->website = Helper::paddingWebsitePrefix($row['up_website']);
        $this->bio = $row['up_bio'];
        $this->activatedcode = $row['up_activatedcode'];
        $this->datecreated = $row['up_datecreated'];
        $this->datemodified = $row['up_datemodified'];
        $this->datelastlogin = $row['up_datelastlogin'];
        $this->oauthPartner = $row['up_oauth_partner'];
        $this->oauthUid = $row['up_oauth_uid'];
        $this->ipaddress = long2ip($row['up_ipaddress']);
    }

    public static function getByEmail($email)
    {
        $db = self::getDb();
        $myUser = new self();

        if (Helper::validateEmail($email)) {
            $sql = 'SELECT * FROM ' . TABLE_PREFIX . 'ac_user u
                    INNER JOIN ' . TABLE_PREFIX . 'ac_user_profile up ON u.u_id = up.u_id
                    WHERE up_email = ?
                    LIMIT 1';
            $row = $db->query($sql, array($email))->fetch();
            if ($row['u_id'] > 0) {
                $myUser->getDataByArray($row);
            }
        }

        return $myUser;
    }

    public static function getByOauthId($oauthpartner, $oauthuid)
    {
        $db = self::getDb();
        $myUser = new self();

        $sql = 'SELECT * FROM ' . TABLE_PREFIX . 'ac_user u
                INNER JOIN ' . TABLE_PREFIX . 'ac_user_profile up ON u.u_id = up.u_id
                WHERE up_oauth_partner = ? AND up_oauth_uid = ?
                LIMIT 1';
        $row = $db->query($sql, array((int)$oauthpartner, (string)$oauthuid))->fetch();
        if ($row['u_id'] > 0) {
            $myUser->getDataByArray($row);
        }

        return $myUser;
    }

    public function delete()
    {
        $sql = 'DELETE FROM ' . TABLE_PREFIX . 'ac_user
                WHERE u_id = ?	';
        $this->db->query($sql, array($this->id));

        $sql = 'DELETE FROM ' . TABLE_PREFIX . 'ac_user_profile
                WHERE u_id = ?';
        $this->db->query($sql, array($this->id));

        $this->deleteImage();

        self::cacheDeleteDetail($this->id);

        return true;
    }

    /**
     * Gan cac gia tri chinh cho 1 tai khoan user
     * Thuong su dung khi JOIN voi cac chuc nang khac va gan data chinh (id, fullname, avatar)
     * vao actor cho cac model khac
     *
     * @param array $info
     */
    public function initMainInfo($info = array())
    {
        $this->id = isset($info['u_id']) ? $info['u_id'] : 0;
        $this->screenname = isset($info['u_screenname']) ? $info['u_screenname'] : '';
        $this->fullname = isset($info['u_fullname']) ? $info['u_fullname'] : '';
        $this->avatar = isset($info['u_avatar']) ? $info['u_avatar'] : '';
        $this->groupid = isset($info['u_groupid']) ? $info['u_groupid'] : '';
        $this->region = isset($info['u_region']) ? $info['u_region'] : '';
        $this->gender = isset($info['u_gender']) ? $info['u_gender'] : '';
        $this->view = isset($info['u_view']) ? $info['u_view'] : 0;
        $this->datelastaction = isset($info['u_datelastaction']) ? $info['u_datelastaction'] : 0;
        $this->parentid = isset($info['u_parentid']) ? $info['u_parentid'] : '';
        $this->skype = isset($info['u_skype']) ? $info['u_skype'] : '';

    }


    public function getGroupName()
    {
        return self::groupname($this->groupid);
    }

    public static function groupname($groupid)
    {
        $registry = \Litpi\Registry::getInstance();
        $lang = $registry->get('lang');

        if ($groupid == GROUPID_ADMIN) {
            $groupname = 'Administrator';
        } elseif ($groupid == GROUPID_MODERATOR) {
            $groupname = 'Moderator';
        } elseif ($groupid == GROUPID_DEVELOPER) {
            $groupname = 'Developer';
        } elseif ($groupid == GROUPID_EMPLOYEE) {
            $groupname = 'Employee';
        } elseif ($groupid == GROUPID_PARTNER) {
            $groupname = 'Partner';
        } elseif ($groupid == GROUPID_DEPARTMENT) {
            $groupname = 'Department';
        } elseif ($groupid == GROUPID_GROUP) {
            $groupname = 'Group';
        } elseif ($groupid == GROUPID_MEMBER) {
            $groupname = 'Member';
        } elseif ($groupid == GROUPID_MEMBERBANNED) {
            $groupname = 'Banned Member';
        } else {
            $groupname = 'Guest';
        }

        return $groupname;
    }

    public static function getGroupnameList()
    {
        $registry = \Litpi\Registry::getInstance();
        $lang = $registry->get('lang');

        $groupnameList = array();

        $groupnameList[GROUPID_ADMIN] = $lang['default']['groupnameAdmin'];
        $groupnameList[GROUPID_MODERATOR] = $lang['default']['groupnameModerator'];
        $groupnameList[GROUPID_DEVELOPER] = $lang['default']['groupnameDeveloper'];
        $groupnameList[GROUPID_EMPLOYEE] = $lang['default']['groupnameEmployee'];
        $groupnameList[GROUPID_PARTNER] = $lang['default']['groupnamePartner'];
        $groupnameList[GROUPID_DEPARTMENT] = $lang['default']['groupnameDepartment'];
        $groupnameList[GROUPID_GROUP] = $lang['default']['groupnameGroup'];
        $groupnameList[GROUPID_MEMBER] = $lang['default']['groupnameMember'];
        $groupnameList[GROUPID_MEMBERBANNED] = $lang['default']['groupnameMemberbanned'];

        //$groupnameList[GROUPID_GUEST] = $lang['default']['groupnameGuest'];

        return $groupnameList;
    }

    public function checkGroupname($name)
    {
        $name = strtolower($name);

        return ($this->groupid == GROUPID_ADMIN && $name == 'administrator' ||
            $this->groupid == GROUPID_MODERATOR && $name == 'moderator' ||
            $this->groupid == GROUPID_DEVELOPER && $name == 'developer' ||
            $this->groupid == GROUPID_EMPLOYEE && $name == 'employee' ||
            $this->groupid == GROUPID_PARTNER && $name == 'partner' ||
            $this->groupid == GROUPID_DEPARTMENT && $name == 'department' ||
            $this->groupid == GROUPID_GROUP && $name == 'group' ||
            $this->groupid == GROUPID_MEMBER && $name == 'member' ||
            $this->groupid == GROUPID_MEMBERBANNED && $name == 'memberbanned'
        );
    }

    private static function countList($where, $joinString, $bindParams)
    {
        $db = self::getDb();

        $sql = 'SELECT COUNT(*) FROM ' . TABLE_PREFIX . 'ac_user u
                ' . $joinString . '';

        if ($where != '') {
            $sql .= ' WHERE ' . $where;
        }

        return $db->query($sql, $bindParams)->fetchColumn(0);
    }

    private static function getList($where, $joinString, $order, $limit, $bindParams)
    {
        $db = self::getDb();

        $outputList = array();
        $sql = 'SELECT * FROM ' . TABLE_PREFIX . 'ac_user u
                ' . $joinString . '';

        if ($where != '') {
            $sql .= ' WHERE ' . $where;
        }

        if ($order != '') {
            $sql .= ' ORDER BY ' . $order;
        }

        if ($limit != '') {
            $sql .= ' LIMIT ' . $limit;
        }

        $stmt = $db->query($sql, $bindParams);

        while ($row = $stmt->fetch()) {
            $myUser = new self();
            $myUser->getDataByArray($row);
            $outputList[] = $myUser;
        }

        return $outputList;
    }

    public static function getUsers(
        $formData,
        $sortby = 'id',
        $sorttype = 'DESC',
        $limit = '',
        $countOnly = false,
        $getUserDetail = true
    ) {
        $whereString = '';
        $joinString = '';
        $bindParams = array();

        if ($getUserDetail) {
            $joinString = ' INNER JOIN ' . TABLE_PREFIX . 'ac_user_profile up ON u.u_id = up.u_id ';
        }

        if ($formData['fid'] > 0) {
            $whereString .= ($whereString != '' ? ' AND ' : '') . 'u.u_id = ? ';
            $bindParams[] = (int)$formData['fid'];
        }

        if (strlen($formData['fscreenname']) > 0) {
            $whereString .= ($whereString != '' ? ' AND ' : '') . 'u.u_screenname = ? ';
            $bindParams[] = Helper::plaintext($formData['fscreenname']);
        }

        if ($formData['fgroupid'] > 0) {
            $whereString .= ($whereString != '' ? ' AND ' : '') . 'u.u_groupid = ? ';
            $bindParams[] = (int)$formData['fgroupid'];
        }

        if ($formData['fgender'] > 0) {
            $whereString .= ($whereString != '' ? ' AND ' : '') . 'u.u_gender = ? ';
            $bindParams[] = (int)$formData['fgender'];
        }

        if ($formData['fregion'] > 0) {
            $whereString .= ($whereString != '' ? ' AND ' : '') . 'u.u_region = ? ';
            $bindParams[] = (int)$formData['fregion'];
        }

        if (isset($formData['fauthoauthpartner'])) {
            $whereString .= ($whereString != '' ? ' AND ' : '') . 'up.up_oauth_partner = ? ';
            $bindParams[] = (int)$formData['foauthpartner'];
        }

        if (isset($formData['femail'])) {
            $whereString .= ($whereString != '' ? ' AND ' : '') . 'up.up_email =  ? ';
            $bindParams[] = Helper::plaintext($formData['femail']);
        }

        if (isset($formData['foauthUid'])) {
            $whereString .= ($whereString != '' ? ' AND ' : '') . 'up.up_oauth_uid = ? ';
            $bindParams[] = $formData['foauthUid'];
        }

        if (strlen($formData['fkeywordFilter']) > 0) {

            $formData['fkeywordFilter'] = Helper::plaintext($formData['fkeywordFilter']);

            if ($formData['fsearchKeywordIn'] == 'email') {
                $whereString .= ($whereString != '' ? ' AND ' : '') . 'up.up_email LIKE ? ';
                $bindParams[] = '%' . $formData['fkeywordFilter'] . '%';

            } elseif ($formData['fsearchKeywordIn'] == 'screenname') {
                $whereString .= ($whereString != '' ? ' AND ' : '') . 'u.u_screenname LIKE ? ';
                $bindParams[] = '%' . $formData['fkeywordFilter'] . '%';

            } elseif ($formData['fsearchKeywordIn'] == 'fullname') {
                $whereString .= ($whereString != '' ? ' AND ' : '') . 'u.u_fullname LIKE ?';
                $bindParams[] = '%' . $formData['fkeywordFilter'] . '%';

            } else {
                $whereString .= ($whereString != '' ? ' AND ' : '')
                    . '( (up.up_email LIKE ?) OR (u.u_screenname LIKE ?) '
                    . 'OR (u.u_fullname LIKE ?) OR (up.up_oauth_uid LIKE ?) )';
                $bindParams[] = '%' . $formData['fkeywordFilter'] . '%';
                $bindParams[] = '%' . $formData['fkeywordFilter'] . '%';
                $bindParams[] = '%' . $formData['fkeywordFilter'] . '%';
                $bindParams[] = '%' . $formData['fkeywordFilter'] . '%';
            }
        }

        //checking sort by & sort type
        if ($sorttype != 'DESC' && $sorttype != 'ASC') {
            $sorttype = 'DESC';
        }

        if ($sortby == 'email') {
            $orderString = ' up.up_email ' . $sorttype;
        } elseif ($sortby == 'group') {
            $orderString = ' u.u_groupid ' . $sorttype;
        } elseif ($sortby == 'datelastaction') {
            $orderString = ' u.u_datelastaction ' . $sorttype;
        } else {
            $orderString = ' u.u_id ' . $sorttype;
        }

        if ($countOnly) {
            return self::countList($whereString, $joinString, $bindParams);
        } else {
            return self::getList($whereString, $joinString, $orderString, $limit, $bindParams);
        }
    }

    public function thumbImage()
    {
        $pos = strrpos($this->avatar, '.');
        $extPart = substr($this->avatar, $pos + 1);
        $namePart = substr($this->avatar, 0, $pos);
        $filesmall = $namePart . '-small.' . $extPart;

        return $filesmall;
    }

    public function mediumImage()
    {
        $pos = strrpos($this->avatar, '.');
        $extPart = substr($this->avatar, $pos + 1);
        $namePart = substr($this->avatar, 0, $pos);
        $filesmall = $namePart . '-medium.' . $extPart;

        return $filesmall;
    }

    public function getRegionName($showUnknown = true)
    {
        $registry = \Litpi\Registry::getInstance();
        $setting = $registry->get('setting');

        if ($this->region > 0) {
            return $setting['region'][$this->region];
        } elseif ($showUnknown) {
            return 'n/a';
        } else {
            return '';
        }
    }

    public function getGenderText()
    {
        if ($this->gender == self::GENDER_MALE) {
            return 'Male';
        } elseif ($this->gender == self::GENDER_FEMALE) {
            return 'Female';
        } else {
            return '';
        }
    }

    public function resetpass($newpass)
    {
        $sql = 'UPDATE ' . TABLE_PREFIX . 'ac_user_profile
                SET up_password = ?
                WHERE u_id = ?
                LIMIT 1';
        $stmt = $this->db->query($sql, array(\Litpi\ViephpHashing::hash($newpass), $this->id));
        if ($stmt) {
            return true;
        } else {
            return false;
        }
    }

    public function getImage()
    {
        $registry = \Litpi\Registry::getInstance();
        $setting = $registry->get('setting');
        $conf = $registry->get('conf');

        $avatarPath = '';
        if ($this->avatar == '') {
            $avatarPath = $registry->get('currentTemplate') . 'images/noavatar.png';
        } else {
            $avatarPath = $conf['rooturl'] . $setting['avatar']['imageDirectory'];
            $avatarPath .= $this->mediumImage();
        }

        return $avatarPath;
    }


    public function getSmallImage($isMedium = false)
    {
        $registry = \Litpi\Registry::getInstance();
        $setting = $registry->get('setting');
        $conf = $registry->get('conf');

        $avatarPath = '';
        if ($this->avatar == '') {
            $avatarPath = $registry->get('currentTemplate') . 'images/noavatar.png';
        } else {
            $avatarPath = $conf['rooturl'] . $setting['avatar']['imageDirectory'];

            if ($isMedium) {
                $avatarPath .= $this->mediumImage();
            } else {
                $avatarPath .= $this->thumbImage();
            }
        }

        return $avatarPath;
    }



    /**
     * Cap nhat field thoi gian hoat dong cuoi cung
     *
     */
    public function updateDateLastaction()
    {
        $sql = 'UPDATE ' . TABLE_PREFIX . 'ac_user
                SET u_datelastaction = ?
                WHERE u_id = ?
                LIMIT 1';
        $this->db->query($sql, array(time(), $this->id));

    }

    /**
     * Kiem tra user nay co phai la group nay ko
     *
     * Dua vao name
     * $groupname: administrator, moderator, member, membervip, memberbanned, bookstore, publisher, guest
     *
     * @param mixed $groupname
     */
    public function isGroup($groupname)
    {
        if ($groupname == 'administrator') {
            return $this->groupid == GROUPID_ADMIN;
        } elseif ($groupname == 'moderator') {
            return $this->groupid == GROUPID_MODERATOR;
        } elseif ($groupname == 'developer') {
            return $this->groupid == GROUPID_DEVELOPER;
        } elseif ($groupname == 'employee') {
            return $this->groupid == GROUPID_EMPLOYEE;
        } elseif ($groupname == 'partner') {
            return $this->groupid == GROUPID_PARTNER;
        } elseif ($groupname == 'department') {
            return $this->groupid == GROUPID_DEPARTMENT;
        } elseif ($groupname == 'group') {
            return $this->groupid == GROUPID_GROUP;
        } elseif ($groupname == 'member') {
            return $this->groupid == GROUPID_MEMBER;
        } elseif ($groupname == 'memberbanned') {
            return $this->groupid == GROUPID_MEMBERBANNED;
        } else {
            return false;
        }

    }

    public function getOAuthPartnerName()
    {
        $name = '';

        switch ($this->oauthPartner) {
            case self::OAUTH_PARTNER_EMPTY:
                $name = 'Not use OAuth';
                break;
            case self::OAUTH_PARTNER_FACEBOOK:
                $name = 'FACEBOOK';
                break;
            case self::OAUTH_PARTNER_GOOGLE:
                $name = 'GOOGLE';
                break;
            case self::OAUTH_PARTNER_YAHOO:
                $name = 'YAHOO';
                break;
        }

        return $name;
    }

    public function canChangePassword()
    {
        return ($this->email != '' && $this->password != '');
    }

    ////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////
    //	CACHE MAIN INFO
    /**
     * Kiem tra xem 1 userid da duoc cache chua
     *
     * @param mixed $userid
     */
    public static function cacheCheck($userid)
    {
        $cacheKeystring = self::cacheBuildKeystring($userid);

        $myCacher = new Cacher($cacheKeystring);
        $row = $myCacher->get();

        if (!empty($row)) {
            return $row;
        } else {
            return false;
        }

    }

    /**
     * Lay thong tin user tu he thong cache
     * danh cho he thong ko phai join voi table user
     *
     * Chua trien khai nen truy xuat toi database luon ^^
     */
    public static function cacheGet($userid, &$cacheSuccess = false, $forceStore = false)
    {
        $registry = \Litpi\Registry::getInstance();
        $db = self::getDb();

        $cacheKeystring = self::cacheBuildKeystring($userid);

        $myUser = new User();

        //get current cache
        $myCacher = new Cacher($cacheKeystring);
        $storerow = $myCacher->get();

        //var_dump($row);

        //force to store new value
        if (!$storerow || $registry->get('request')->query->has('live') || $forceStore) {
            $sql = 'SELECT * FROM ' . TABLE_PREFIX . 'ac_user
                    WHERE u_id = ? ';
            $row = $db->query($sql, array($userid))->fetch();
            if ($row['u_id'] > 0) {
                $myUser->initMainInfo($row);

                $storerow = array(
                    $row['u_id'],
                    $row['u_screenname'],
                    str_replace(',', '&#44;', $row['u_fullname']),
                    $row['u_avatar'],
                    $row['u_groupid'],
                    $row['u_region'],
                    $row['u_gender'],
                    $row['u_count_following'],
                    $row['u_count_follower'],
                    $row['u_count_blog'],
                    $row['u_view'],
                    $row['u_datelastaction'],
                    $row['u_skype'],
                );

                $storerow = implode(',', $storerow);
                $cacheSuccess = self::cacheSet($userid, $storerow);
            }
        } else {
            $storerow = explode(',', $storerow);
            $row = array(
                'u_id' => $storerow[0],
                'u_screenname' => $storerow[1],
                'u_fullname' => str_replace('&#44;', ',', $storerow[2]),
                'u_avatar' => $storerow[3],
                'u_groupid' => $storerow[4],
                'u_region' => $storerow[5],
                'u_gender' => $storerow[6],
                'u_count_following' => $storerow[7],
                'u_count_follower' => $storerow[8],
                'u_count_blog' => $storerow[9],
                'u_view' => $storerow[10],
                'u_datelastaction' => $storerow[11],
                'u_skype' => $storerow[12],
            );

            $myUser->initMainInfo($row);
        }

        return $myUser;
    }

    /**
     * Luu thong tin vao cache
     *
     */
    public static function cacheSet($userid, $value)
    {
        $registry = \Litpi\Registry::getInstance();
        $setting = $registry->get('setting');

        $myCacher = new Cacher(self::cacheBuildKeystring($userid));

        return $myCacher->set($value, $setting['site']['apcUserCacheTimetolive']);
    }

    /**
     * Xoa 1 key khoi cache
     *
     * @param mixed $userid
     */
    public static function cacheDelete($userid)
    {
        $myCacher = new Cacher(self::cacheBuildKeystring($userid));

        return $myCacher->clear();
    }

    /**
     * Ham tra ve key de cache
     *
     * @param mixed $userid
     */
    public static function cacheBuildKeystring($userid)
    {
        $registry = \Litpi\Registry::getInstance();
        $setting = $registry->get('setting');

        return $setting['site']['cacheKeyPrefix'] . 'user_' . $userid;
    }

    public static function cacheBuildKeystringDetail($userid)
    {
        $registry = \Litpi\Registry::getInstance();
        $setting = $registry->get('setting');

        return $setting['site']['cacheKeyPrefix'] . 'userdetail_' . $userid;
    }

    public static function cacheDeleteDetail($userid)
    {
        $myCacher = new Cacher(self::cacheBuildKeystringDetail($userid));

        return $myCacher->clear();
    }

    //	end -- CACHE MAIN INFO
    ////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////



    public function getNameicon()
    {
        $icon = '';

        return $icon;
    }

    public function getSessionId()
    {
        $sid = Helper::getSessionId();

        return $sid;
    }

}
