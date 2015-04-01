<?php

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     modifier
 * Name:     money_format
 * Purpose:  format currency amount via strfmon
 * Input:    string: money value
 *           format: strfmon format for output
 *           locale: locale string
 * Author:   Piotr Klaban <makler@man.torun.pl>
 * Date:     Wed, Nov 20 2002
 * -------------------------------------------------------------
 * E.g.:
 * {$x|money_format:"%i"} (international format) => USD 12,333.34
 * {$x|money_format:"%n":"pl"} (local country format for Poland) => 12.333,34 z³
 */

function smarty_modifier_money_format($num, $format = "%n", $locale = null)
{
        $curr = false;
        $savelocale = null;
        
        $savelocale = setlocale(LC_MONETARY, null);

        // use en_US if current locale is unrecognized
        if ((!isset($locale) || $locale == '') &&
            ($savelocale == '' || $savelocale == 'C'))
                $locale = 'en_US';
        
        if (isset($locale) && $locale != '') {
                setlocale(LC_MONETARY, $locale);
        }

        if (function_exists('money_format')) {
                $curr = money_format($format, $num);
        } else {
                // function would be available in PHP 4.3
                //return $num . ' (money_format would be available in PHP 4.3)';
                
                //if not PHP 4.3 , using number format
                $curr = number_format($num,0,'.',',');
        }
        
        if (isset($locale) && $locale != '') {
                setlocale(LC_MONETARY, $savelocale);
        }

        $curr = trim($curr);
        return $curr;
}

/* vim: set expandtab: */

?>
