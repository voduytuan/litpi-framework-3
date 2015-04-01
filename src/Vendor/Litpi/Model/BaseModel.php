<?php

namespace Litpi\Model;

abstract class BaseModel extends \stdClass
{
    protected $db;

    public function __construct()
    {
        $this->db = self::getDb();
    }

    public function __sleep()
    {
           $this->db = null;

           return $this;
    }

    public function copy(\stdClass $object)
    {
        foreach (get_object_vars($object) as $key => $value) {
            $this->$key = $value;
        }
    }

    public static function getDb()
    {
        $registry = \Litpi\Registry::getInstance();
        return $registry->get('db');

        //return $GLOBALS['db'];
    }

    /**
    * Luu thong tin vao cache
    *
    */
    public static function cacheSet($key, $value)
    {
        global $registry;

        $myCacher = new \Litpi\Cacher($key);

        return $myCacher->set($value, $registry->setting['site']['apcUserCacheTimetolive']);
    }
}
