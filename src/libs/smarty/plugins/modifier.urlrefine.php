<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty url refine modifier plugin
 *
 * Type:     modifier<br>
 * Name:     lower<br>
 * Purpose:  convert a url string to http format
 * @author   Vo duy tuan
 * @param string
 * @return string
 */
function smarty_modifier_urlrefine($string)
{
	if(preg_match('/(http|https|ftp)/i', $string))
		return $string;
	else
		return 'http://'.$string;
}

?>
