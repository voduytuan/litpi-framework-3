<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */
 
 
/**
 * Smarty truncate modifier plugin
 *
 * Type:     modifier<br>
 * Name:     truncateperiod<br>

 */
function smarty_modifier_truncateperiod($string, $limit = 80, $pad = '...', $break='.')
{
	return Helper::truncateperiod($string, $limit, $pad, $break);
	/*
	$string = strip_tags($string);
	
    // return with no change if string is shorter than $limit 
    if(strlen($string) <= $limit) return $string; 
    // is $break present between $limit and the end of the string? 
    if(false !== ($breakpoint = strpos($string, $break, $limit))) 
    { 
    	if($breakpoint < strlen($string) - 1) 
    	{ 
    		$string = substr($string, 0, $breakpoint) . $pad; 
    	} 
    } 
    return $string; 
    */
}
 
/* vim: set expandtab: */
?>