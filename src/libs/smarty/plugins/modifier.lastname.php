<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty lastname modifier plugin
 *
 * Type:     modifier<br>
 * Name:     lower<br>
 * Purpose:  get the last name from the fullname String. Ex: "Vo Duy Tuan" -> "Tuan"
 * @link http://smarty.php.net/manual/en/language.modifier.lower.php
 *          lower (Smarty online manual)
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @param string
 * @return string
 */
function smarty_modifier_lastname($string)
{
    $output = $string;

    if (extension_loaded('mbstring')) {
        $lastSpacePos = mb_strrpos($string, " ");
        if ($lastSpacePos !== false) {
            $output = mb_substr($string, $lastSpacePos);
        }
    } else {
        $lastSpacePos = strrpos($string, " ");
        if ($lastSpacePos !== false) {
            $output = substr($string, $lastSpacePos);
        }
    }

    return $output;
}