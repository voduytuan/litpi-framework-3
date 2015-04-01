<?php

namespace Litpi;

class Dosdetector
{
    //IP Address will ignore the process of IDS. Ex: IP of google bot, yahoo bot, internal IP...
    public $ignoreIpAddress = array();

    //Access information will be store in this cacher, using this key
    const PHPIDS_ACCESS_KEY = 'tc_phpids_access_key';

    //IPS banned IP address will be store in this cacher, using this key
    const PHPIDS_BANNEDIP_KEY = 'tc_phpids_bannedip_key';

    //If this ipaddress access in this timestamp over this number
    //The IDS system will alert, base on the alert type, the administrator
    //will receive the notification
    const PHPIDS_QUOTA_IDS_TRIGGER = 40;
    const PHPIDS_QUOTA_IPS_TRIGGER = 400;

    public function __construct()
    {

    }

    public function run($landingpage = '')
    {
        //////////////////////////////////
        //KEY SUFFIX for cache rotation
        // Cache Key rotation in each hour ^^. each means, in one day, there is 24 key for each hour.
        // But, because the expire of key is 3600 (1 hour), so, there are max 2
        //key in mean time (because old keys will be deleted because cache expireds ^^)
        $dateInfo = getdate();
        $suffix = $dateInfo['hours'];

        //detect apc|memcached installed
        if ($this->isMemoryCacheEnable()) {
            //access detail
            $curTime = time();
            $ipaddress = $this->getIpAddress();
            $havecookie = !empty($_COOKIE) ? 1 : 0;
            $useragent = isset($_SERVER['HTTP_USER_AGENT'])
                ? strip_tags(substr($_SERVER['HTTP_USER_AGENT'], 0, 100)) : '';
            $uri = substr($_SERVER["REQUEST_URI"], 0, 100);
            $referer = isset($_SERVER['HTTP_REFERER']) ? substr($_SERVER["HTTP_REFERER"], 0, 100) : '';
            $referer = str_replace(array('http://' . HOST . '/', 'https://' . HOST . '/'), '', $referer);

            //Ignore Bot, Internal access
            if (!in_array($ipaddress, $this->ignoreIpAddress)) {
                //////////////////////////
                //////////////////////////
                // get banned IP
                $myCacher = new Cacher(self::PHPIDS_BANNEDIP_KEY);
                $bannedIpAddress = $myCacher->get();

                if (!$bannedIpAddress) {
                    $bannedIpAddress = array();
                } else {
                    if (in_array($ipaddress, $bannedIpAddress)) {
                        //YOU ARE BANNED;
                        if ($landingpage != '') {
                            header('location: ' . $landingpage);
                        } else {
                            die('Web site under construction. See You tomorrow.');
                        }
                    }
                }

                //thong tin access trong ngay
                $myCacher = new Cacher(self::PHPIDS_ACCESS_KEY . $suffix);
                $accessList = $myCacher->get();
                //$accessList = apc_fetch(self::PHPIDS_ACCESS_KEY . $suffix);

                //Update data
                //If not found this access before, create
                if (!$accessList) {
                    $accessList = array(
                        $ipaddress => array(
                            $curTime => array(
                                1,
                                $havecookie,
                                $useragent,
                                $uri,
                                $referer
                            )
                        )
                    );
                } else {
                    //Neu may da truy cap
                    if (isset($accessList[$ipaddress])) {
                        //Vo cung khung thoi gian
                        if (isset($accessList[$ipaddress][$curTime])) {
                            $accessList[$ipaddress][$curTime][0]++;
                        } else { //vo o timestamp khac
                            $accessList[$ipaddress][$curTime] = array(1, $havecookie, $useragent, $uri, $referer);
                        }

                        ///////////////////////////
                        /////////////////////////
                        // Trigger IDS
                        if ($accessList[$ipaddress][$curTime] > self::PHPIDS_QUOTA_IDS_TRIGGER) {
                            //IDS Start
                            //Request toi URL nao do de notify admin
                            //Todo:
                            //...
                        }

                        ///////////////////////////
                        /////////////////////////
                        // IPS Preventation
                        $rangeToCheck = 5;    //seconds
                        $totalInPastRange = 0;
                        foreach ($accessList[$ipaddress] as $timestamp => $info) {
                            //valid range to get SUM
                            if ($curTime - $timestamp < $rangeToCheck || $curTime == $timestamp) {
                                $totalInPastRange += $info[0];
                            }
                        }

                        //Trigger IPS
                        if ($totalInPastRange > self::PHPIDS_QUOTA_IPS_TRIGGER) {
                            //IPS Start
                            //add to IPS
                            if (!in_array($ipaddress, $bannedIpAddress)) {
                                $bannedIpAddress[] = $ipaddress;

                                $myCacher = new Cacher(self::PHPIDS_BANNEDIP_KEY);
                                $myCacher->set($bannedIpAddress, 0);
                            }
                        }
                    } else {
                        //Neu chua truy cap, thi them vao danh sach da truy cap
                        $accessList[$ipaddress] = array($curTime => array(1, $havecookie, $useragent, $uri, $referer));
                    }
                } //end check key

                //Store the access info
                $myCacher = new Cacher(self::PHPIDS_ACCESS_KEY . $suffix);
                $myCacher->set($accessList, 3600);

            }//end check ignoreIpAddress

        } else {
            //Memory cache (apc, memcached) not found.hehe.
        }
    }

    public function isMemoryCacheEnable()
    {
        return (extension_loaded('memcached'));
    }

    public function getAccessList()
    {
        $accessList = array();
        if ($this->isMemoryCacheEnable()) {
            for ($i = 0; $i < 24; $i++) {
                $myCacher = new Cacher(self::PHPIDS_ACCESS_KEY . $i);
                $listInHour = $myCacher->get();

                if (!empty($listInHour)) {
                    $accessList = array_merge($accessList, $listInHour);
                }
            }
        }

        return $accessList;
    }

    public function getBannedIpList()
    {
        $bannedIpList = array();
        if ($this->isMemoryCacheEnable()) {
            $myCacher = new Cacher(self::PHPIDS_BANNEDIP_KEY);
            $list = $myCacher->get();

            if (!empty($list)) {
                $bannedIpList = $list;
            }
        }

        return $bannedIpList;
    }

    public function banipInsert($ipaddress)
    {
        if ($this->isMemoryCacheEnable()) {
            $myCacher = new Cacher(self::PHPIDS_BANNEDIP_KEY);
            $list = $myCacher->get();

            if (!empty($list) && !in_array($ipaddress, $list)) {
                $list[] = $ipaddress;
            } elseif (empty($list)) {
                $list = array($ipaddress);
            }

            return $myCacher->set($list, 0);
        } else {
            return false;
        }
    }

    public function banipRemove($ipaddress)
    {
        if ($this->isMemoryCacheEnable()) {
            $myCacher = new Cacher(self::PHPIDS_BANNEDIP_KEY);
            $list = $myCacher->get();

            if (empty($list)) {
                return false;
            } else {
                $newlist = array();

                foreach ($list as $ip) {
                    if ($ip != $ipaddress) {
                        $newlist[] = $ip;
                    }
                }

                return $myCacher->set($newlist, 0);
            }
        } else {
            return false;
        }
    }

    /**
     * Get IP Address of current Access
     */
    public function getIpAddress()
    {
        $ip = '';

        if ($_SERVER) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
        } else {
            if (getenv('HTTP_X_FORWARDED_FOR')) {
                $ip = getenv('HTTP_X_FORWARDED_FOR');
            } elseif (getenv('HTTP_CLIENT_IP')) {
                $ip = getenv('HTTP_CLIENT_IP');
            } else {
                $ip = getenv('remote_addr');
            }
        }

        return $ip;
    }
}
