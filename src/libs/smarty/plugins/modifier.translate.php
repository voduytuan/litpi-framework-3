<?php

/**
 * Smarty translate modifier plugin
 *
 * Type:     modifier<br>
 * Name:     translate<br>
 * Date:     April 29, 2006
 * Purpose:  provide late binding language translation for a variable
 * Example:  {$var|translate}
 * @author  Glenn Henshaw (Mantis Bug Tracker http://www.mantisbt.org/)
 * @version 1.0
 * @param string
 * @return string
 */
function smarty_modifier_translate($string)
{
	return $string;
    return $GLOBALS['lang']->translate($string);
}


?>