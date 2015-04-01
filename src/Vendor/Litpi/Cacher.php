<?php

namespace Litpi;

class Cacher
{
    const STORAGE_FILE = 1;
    const STORAGE_REDIS = 5;
    const STORAGE_MEMCACHED = 7;

    public $storage;
    public $key = '';
    public static $redis = null;
    public static $memcached = null;

    /**
     * @param $storageEngineConfig array: include 2 elements: array('ip' => SERVER_IP_ADDRESS, 'port' => PORT_NUMBER)
     */
    public function __construct($key, $storageEngine = 0, $storageEngineConfig = null)
    {
        //Set default Storage Engine for cache
        if ($storageEngine == 0) {
            $storageEngine = self::STORAGE_MEMCACHED;
        }

        if ($storageEngine != self::STORAGE_FILE
            && $storageEngine != self::STORAGE_REDIS
            && $storageEngine != self::STORAGE_MEMCACHED) {
            $this->storageException();
        } else {
            if ($storageEngine == self::STORAGE_REDIS) {
                if (self::$redis == null) {
                    self::$redis = self::getRedisInstance($storageEngineConfig);
                }
            } elseif ($storageEngine == self::STORAGE_MEMCACHED) {
                if (self::$memcached == null) {
                    self::$memcached = self::getMemcachedInstance($storageEngineConfig);
                }
            }

            $this->storage = $storageEngine;
            $this->key = $key;
        }
    }

    public static function getRedisInstance($storageEngineConfig = null)
    {
        $serverIp = '';
        $serverPort = '';

        if (!is_null($storageEngineConfig)) {
            $serverIp = $storageEngineConfig['ip'];
            $serverPort = $storageEngineConfig['port'];

        } else {
            $registry = \Litpi\Registry::getInstance();
            $conf = $registry->get('conf');
            $serverIp = $conf['redis'][0]['ip'];
            $serverPort = $conf['redis'][0]['port'];
        }

        $output = null;

        try {
            $redis = new \Redis();
            $redis->connect($serverIp, $serverPort);
            $output = $redis;
        } catch (\Exception $e) {
            //Can not connect to Redis Server
            echo('Can not connect to Redis Server');
        }

        return $output;
    }

    public static function getMemcachedInstance($storageEngineConfig = null)
    {
        $serverIp = '';
        $serverPort = '';

        if (!is_null($storageEngineConfig)) {
            $serverIp = $storageEngineConfig['ip'];
            $serverPort = $storageEngineConfig['port'];

        } else {
            $registry = \Litpi\Registry::getInstance();
            $conf = $registry->get('conf');
            $serverIp = $conf['memcached'][0]['ip'];
            $serverPort = $conf['memcached'][0]['port'];
        }

        $output = null;
        try {
            $memcached = null;

            if (class_exists('Memcached', false)) {
                $memcached = new \Memcached();
                $memcached->addServer($serverIp, $serverPort);

            } else {
                //Wrapper memcache in Windows
                $memcached = new \Litpi\Memcached();
                $memcached->addServer($serverIp, $serverPort);
            }

            $output = $memcached;
        } catch (\Exception $e) {
            //Can not connect to Memcache Server
            echo('Can not connect to Memcache Server');
        }

        return $output;
    }

    /**
     * Get a value saved in cache
     */
    public function get()
    {
        $output = null;
        if ($this->storage == self::STORAGE_FILE) {
            //Not implemented
        } elseif ($this->storage == self::STORAGE_REDIS) {
            if (self::$redis != null) {
                $output = self::$redis->get($this->key);
            }
        } elseif ($this->storage == self::STORAGE_MEMCACHED) {
            if (self::$memcached != null) {
                $output = self::$memcached->get($this->key);
            }
        } else {
            $this->storageException();
        }

        return $output;
    }

    /**
     * Set a value to a key
     */
    public function set($value, $duration = 9000)
    {
        $output = null;

        if ($this->storage == self::STORAGE_FILE) {
            //Not implemented
        } elseif ($this->storage == self::STORAGE_REDIS) {
            if (self::$redis != null) {
                self::$redis->set($this->key, $value, $duration);
            }
        } elseif ($this->storage == self::STORAGE_MEMCACHED) {
            if (self::$memcached != null) {
                self::$memcached->set($this->key, $value, $duration);
            }
        } else {
            $this->storageException();
        }

        return $output;
    }

    /**
     * Check a key is existed or not
     */
    public function check()
    {
        $output = false;

        if ($this->storage == self::STORAGE_FILE) {
            //Not implemented
        } elseif ($this->storage == self::STORAGE_REDIS && self::$redis != null) {
            $output = self::$redis->exists($this->key);
        } elseif ($this->storage == self::STORAGE_MEMCACHED && self::$memcached != null) {
            $output = self::$memcached->get($this->key) !== false;
        } else {
            $this->storageException();
        }

        return $output;
    }

    /**
     * Clear a cache
     */
    public function clear()
    {
        $output = null;

        if ($this->storage == self::STORAGE_FILE) {
            //Not implemented
        } elseif ($this->storage == self::STORAGE_REDIS && self::$redis != null) {
            self::$redis->delete($this->key);

        } elseif ($this->storage == self::STORAGE_MEMCACHED && self::$memcached != null) {
            self::$memcached->delete($this->key);

        } else {
            $this->storageException();
        }

        return $output;
    }

    private function storageException()
    {
        throw new \Exception('Storage Engine is not valid');
    }
}
