<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty safejsname modifier plugin
 *
 * Type:     modifier<br>
 * Name:     safejsname<br>
 * Purpose:  convert fullname to safe string, replace some chars like '
 * @author   voduytuan<tuanmaster2002@yahoo.com>
 * @param string
 * @return string
 */
function smarty_modifier_safejsname($string, $moreslash = false)
{
	if($moreslash)
		$string = str_replace(array("'"), array("\\\'"), $string);
	else
		$string = str_replace(array("'"), array("\\'"), $string);
	
    return $string;
}

?>
