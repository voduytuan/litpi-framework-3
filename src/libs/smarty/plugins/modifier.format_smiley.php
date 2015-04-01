<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty format smiley modifier plugin
 *
 * Type:     modifier<br>
 * Name:     replace<br>
 * Purpose:  simple search/replace
 * @link http://smarty.php.net/manual/en/language.modifier.replace.php
 *          replace (Smarty online manual)
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @param string
 * @param string
 * @param string
 * @return string
 */
function smarty_modifier_format_smiley($string)
{
	global $smarty;
	$imageFolder = Registry::$base_dir . 'uploads/smiley/';
	$smiley = $smarty->_tpl_vars['smiley'];
	$string = htmlspecialchars($string);
	foreach ($smiley['replaceArr'] as $key=>$value)
	{
		$string = str_ireplace($key,'<img src='.$imageFolder.$value.'>', $string);
	}
    return $string;
    
}

/* vim: set expandtab: */

?>
