<?php

namespace Litpi;

class Registry extends \stdClass implements \ArrayAccess
{
    private $vars = array();
    public static $base_dir = '';

    protected function __construct()
    {

    }

    public static function getInstance()
    {
        static $instance = null;
        if (null === $instance) {
            $instance = new static();
        }

        return $instance;
    }

    /**
     * Private clone method to prevent cloning of the instance of the
     * *Singleton* instance.
     *
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * Private unserialize method to prevent unserializing of the *Singleton*
     * instance.
     *
     * @return void
     */
    private function __wakeup()
    {
    }

    public function set($key, $var)
    {
        $this->vars[$key] = $var;

        return true;
    }

    public function get($key)
    {
        if (isset($this->vars[$key]) == false) {
            //return null;
            return (new \stdClass());
        } else {

            return $this->vars[$key];
        }

    }

    public function __set($key, $var)
    {
        $this->vars[$key] = $var;

        return true;
    }

    public function __get($key)
    {
        if (isset($this->vars[$key])) {
            return $this->vars[$key];
        }

        return null;
    }

    public function remove($var)
    {
        unset($this->vars[$var]);
    }

    public function offsetExists($offset)
    {
        return isset($this->vars[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    public function offsetUnset($offset)
    {
        unset($this->vars[$offset]);
    }

    /**
    * Ham xu ly tinh toan de tra ve ROOT URL cua Resource
    *
    * De lam giam tai cho main site
    * Co the xu ly cai tien cho phep su dung CDN voi nhieu domain
    * Hien tai chi co 1 domain la r-img.com duoc cau hinh trong file include/config.php ma thoi
    * nen return ve gia tri nay luon
    *
    * @param string $type: loai resource de co the cau hinh duong dan resource cho tot hon dua vao loai resource
    * - 1 so loai resource la: static (css, img, js cua template), product va user avatar (big,small,medium)
    *
    */
    public function getResourceHost($type = '')
    {
        $registry = self::getInstance();
        $conf = $registry->conf;
        $setting = $registry->setting;


        $path = '';

        if (isset($setting['resourcehost'][$type])) {
            if ($registry->https && isset($setting['resourcehost'][$type . '_https'])) {
                $path = $conf['rooturl'] . $setting['resourcehost'][$type . '_https'];
            } else {
                $path = $conf['rooturl'] . $setting['resourcehost'][$type];
            }
        }

        return $path;
    }
}
