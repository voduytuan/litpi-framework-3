<?php

namespace Litpi;

class ViephpHashing
{
    //never change this string, if not, all account will invalid.
    // Need to change for each project
    public static $secretString = 'lkjhhfu484629fhdfgsgfjk3937dhksh';

    //if change this value, user password cookie will invalid.
    public static $salt = '94729fjhgadhwkdxwueodjd893729';

    public static $saltSeperator = ':';
    public function __construct()
    {

    }

    /**
     * Ham dung de hash 1 password va co su dung salt va secret string de securepasword
     *
     * @param  string  $input : password
     * @param  boolean $full  : if $full == true : create full hashing string: used to create password; if $full == false: only return password hashing prefix(used for remember me to save cookie)
     */
    public static function hash($input, $full = true)
    {
        if ($full) {
            return md5($input . self::$salt) . self::$saltSeperator . base64_encode(md5(self::$secretString) . self::$salt);
        } else {
            return md5($input . self::$salt);
        }
    }

    /**
     * ham dung de encoding 1 string bang base64_encode
     *  - nhung dung ket hop 1 so ky thuat, de secure result
     *
     * @param string $input
     */
    public static function superBase64Encode($input)
    {
        $output = $input;
        $output = base64_encode($output);
        $output = strrev($output);
        $output = base64_encode($output);
        $output = base64_encode($output);
        $output = strrev($output);

        return $output;
    }

    /**
     * Decode cho ham superBase64Encode
     *
     * @param  unknown_type $input
     * @return unknown
     */
    public static function superBase64Decode($input)
    {
        $output = $input;
        $output = strrev($output);
        $output = base64_decode($output);
        $output = base64_decode($output);
        $output = strrev($output);
        $output = base64_decode($output);

        return $output;
    }

    /**
     * Dung de authenticate password
     *
     *
     * @param string $source : password can so sanh
     * @param string $dest   : password duoc luu tru trong db, co format; {HASH}{SALT_SEPERATOR}{ENCODEDSALT} (HASH = MD5({password}{$SALT}), ENCODEDSALT = BASE64ENCODE(MD5(SECRETSTRING).{$SALT}))
     */
    public static function authenticate($source, $dest)
    {
        //get salt from destination
        $group = explode(self::$saltSeperator, $dest);
        $hash = $group[0];

        $oldSalt = substr(base64_decode($group[1]), 32);

        return $hash == md5($source . $oldSalt);
    }

    /**
     * Ham dung de convert tu 1 chuoi da duoc hashing ngan (de luu vao cookie)
     *  sang chuoi password day du, dung de so sanh voi password goc
     *
     * @param  string $input
     * @return string
     */
    public static function convertToFullString($input)
    {
        return $input . self::$saltSeperator . base64_encode(md5(self::$secretString) . self::$salt);
    }

    /**
     * Ham dung de tao cookie vao luu xuong pc cua user, dung cho chuc nang "remember me"
     *
     * @param  int    $userid
     * @param  string $password
     * @return string
     */
    public static function cookiehashing($userid, $password)
    {
        return self::superBase64Encode($userid) . self::$saltSeperator . self::superBase64Encode(self::hash($password, false));
    }

    public static function cookiehasingParser($cookieHashing)
    {
        $group = explode(self::$saltSeperator, $cookieHashing);
        $rememberMeInfo = array();
        $rememberMeInfo['userid'] = self::superBase64Decode($group[0]);
        $rememberMeInfo['shortPasswordString'] = self::superBase64Decode($group[1]);

        return $rememberMeInfo;
    }

    public static function authenticateCookiehashing($shortPasswordString, $fullPasswordString)
    {
        $group = explode(self::$saltSeperator, $fullPasswordString);

        return ($group[0] == $shortPasswordString);
    }
}
