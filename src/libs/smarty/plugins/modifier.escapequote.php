<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty replace normal quote with htmlspecialchar
 *
 * Type:     modifier<br>
 * Name:     escapequote<br>
 * Purpose:  convert string vietnamese price format
 * @author   lonelyworlf(tuanmaster2002@yahoo.com)
 * @param string
 * @return string
 */
function smarty_modifier_escapequote($string)
{
	return str_replace('"', '&quot;', $string);
}

?>
