<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty alphabetonly modifier plugin
 * 
 * Day l modifier, dung de remove cac ky tu khong phai la chu cai, chu so..(alphabetical), va replace thanh dau -, de lam SEO
 * 
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
function smarty_modifier_alphabetonly($string)
{
	return Helper::alphabetonly($string);
    
}

/* vim: set expandtab: */

?>
