<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * USER DEFINED FUNCTION
 * Hien thi dropdown box number range
 * 
 */
function smarty_function_number_range($params, &$smarty)
{
	$from = $params['from'];
	$to = $params['to'];
	$selected = $params['selected'];
	echo $selected;
	$step = $params['step'];
	if($step == 0)
		$step = 1;
	
	$output = '';
	if($from > $to)
	{
		$a = $from;
		$from = $to;
		$to = $a;
	}
	
	for($i = $from; $i <= $to;)
	{
		$show = $i;
		if($step < 1)
			$show = number_format(floatval($i), 1, '.','');
		$selectString = '';
		
		if(strcmp($i, $selected) == 0)
			$selectString = ' selected ';
		$output .= '<option value="'.$i.'" '.$selectString.'>'.$show.'</option>';
		$i += $step;
	}
	return $output;
}


?>
