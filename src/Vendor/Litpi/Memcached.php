<?php


namespace Litpi;

/**
 * Dump class for Memcached user, especially windows user
 */

class Memcached
{
    private $aliasToRedis = false;

    public function __construct($aliasToRedis = false)
    {
        if (class_exists('Redis', false)) {
            $this->aliasToRedis = $aliasToRedis;
        } else {
            $this->aliasToRedis = false;
        }
    }

    public function addServer($ip = '', $port = '11211')
    {
        $ip = (string)$ip;
        $port = (string)$port;
        return true;
    }

    public function get($key = '')
    {
        if ($this->aliasToRedis) {
            $myCacher = new Cacher($key, Cacher::STORAGE_REDIS);
            return $myCacher->get();

        } else {
            return false;
        }
    }

    public function set($key = '', $value = '', $duration = 0)
    {
        if ($this->aliasToRedis) {
            $myCacher = new Cacher($key, Cacher::STORAGE_REDIS);
            return $myCacher->set($value, $duration);

        } else {
            return false;
        }
    }

    public function __call($name, $args)
    {
    }
}
