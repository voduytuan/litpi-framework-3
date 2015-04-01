<?php

namespace Vendor\Other;

/**
 * Exception handler for PHPMailer
 * @package PHPMailer
 */
class PhpMailerException extends \Exception
{
    /**
    * Prettify error message output
    * @return string
    */
    public function errorMessage()
    {
        $errorMsg = '<strong>' . $this->getMessage() . "</strong><br />\n";
        return $errorMsg;
    }
}
