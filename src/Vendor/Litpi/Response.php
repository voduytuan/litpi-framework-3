<?php

namespace Litpi;

/**
 */

class Response extends \Symfony\Component\HttpFoundation\Response
{
    private static $alreadySent = false;

    public function send()
    {
        if (self::$alreadySent == false) {
            self::$alreadySent = true;
            parent::send();

        } else {
            //This response has already sent
        }
    }
}
