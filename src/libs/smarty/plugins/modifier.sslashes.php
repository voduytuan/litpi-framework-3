<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * USER DEFINED MODIFIER
 * Dung de stripslashes data truoc khi xuat ra
 *
 * @param unknown_type $text
 * @return unknown
 */
function smarty_modifier_sslashes($text)
{
    return stripslashes_deep($text);
}

function stripslashes_deep($value)
{
   $value = is_array($value) ?
               array_map('stripslashes_deep', $value) :
               stripslashes($value);

   return $value;
}

/* vim: set expandtab: */

?>
