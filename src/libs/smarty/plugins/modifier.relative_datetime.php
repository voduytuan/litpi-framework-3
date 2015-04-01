<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */
 
/**
 * Smarty relative date / time plugin
 *
 * Type:     modifier<br>
 * Name:     relative_datetime<br>
 * Date:     March 18, 2009
 * Purpose:  converts a date to a relative time
 * Input:    date to format
 * Example:  {$datetime|relative_datetime}
 * @author   Eric Lamb <eric@ericlamb.net>
 * @version 1.0
 * @param string
 * @return string
 */
function smarty_modifier_relative_datetime($timestamp)
{
	global $lang;
	
	if(!$timestamp){
		return 'N/A';
	}
 
	$difference = time() - $timestamp;
	$periods = array($lang['global']['timeSec'], $lang['global']['timeMin'], $lang['global']['timeHour'], $lang['global']['timeDay'], $lang['global']['timeWeek'],$lang['global']['timeMonth'], $lang['global']['timeYear']);
	$lengths = array("60","60","24","7","4.35","12");
	$total_lengths = count($lengths);
 
	if ($difference > 0) { // this was in the past
		$ending = $lang['global']['timeAgo'];
	} else if($difference == 0)
	{
		return $lang['global']['timeSeveralSec'];
	}
	else { // this was in the future
		$difference = -$difference;
		$ending = $lang['global']['timeFromNow'];
	}
	//return;
 
	for($j = 0; $difference > $lengths[$j] && $total_lengths > $j; $j++) {
		$difference /= $lengths[$j];
	}
 
	$difference = round($difference);
	if($difference != 1) {
		$periods[$j].= $lang['global']['timePlural'];
	}
 
	$text = "$difference $periods[$j] $ending";
 
	return $text;
}