<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty lower modifier plugin
 *
 * Type:     modifier<br>
 * Name:     lower<br>
 * Purpose:  convert string to lowercase
 * @link http://smarty.php.net/manual/en/language.modifier.lower.php
 *          lower (Smarty online manual)
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @param string
 * @return string
 */
function smarty_modifier_flowfilenamedisplay($string)
{
    $out = '';

    if (preg_match('/\/\d+-\d+-(\d+)-(.*)$/', $string, $match)) {
        $size = $match[1];
        $name = $match[2];

        $sizestring = \Litpi\Helper::formatFileSize($size);
        $out = $name . ' ( ' . $sizestring . ')';
    } else {
        $out = 'Download';
    }

    return $out;
}
