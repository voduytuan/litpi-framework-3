<?php

namespace Litpi;

/**
 */

class Cookie extends \Symfony\Component\HttpFoundation\Cookie
{
    /**
     * Override parent construct httpOnly to false
     * @param string $name
     * @param null $value
     * @param null $expire
     * @param string $path
     * @param null $domain
     * @param bool $secure
     * @param bool $httpOnly
     *
     */
    public function __construct(
        $name,
        $value = null,
        $expire = null,
        $path = '/',
        $domain = null,
        $secure = false,
        $httpOnly = false
    ) {
        parent::__construct($name, $value, $expire, $path, $domain, $secure, $httpOnly);
    }
}
