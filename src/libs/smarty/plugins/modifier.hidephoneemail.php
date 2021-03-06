<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty codau2khongdau modifier plugin
 * 
 * Day la 1 modifier giup convert cac ky tu co dau cua Tieng Viet thanh khogn dau
 * De lam SEO.
 * -Su dung ham static cua class Help de tien hanh xu ly, ko implement funciton o
 * day
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
function smarty_modifier_hidephoneemail($string, $templateDir)
{
    $obfuscatePhoneImage = '<img src="'.$templateDir . 'images/app/flow/mosaic_phone.png" />';
    $string = preg_replace('/[0-9\(\). -]{9,}/', $obfuscatePhoneImage, $string);

    $obfuscateEmailImage = '<img src="'.$templateDir . 'images/app/flow/mosaic_email.png" />';
    $string = preg_replace('/([a-zA-Z0-9_\-\.]*@\\S+\\.\\w+)/', $obfuscateEmailImage, $string);

    return $string;
    
}
