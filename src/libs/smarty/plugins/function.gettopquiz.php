<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {counter} function plugin
 *
 * Type:     function<br>
 * Name:     topquiz<br>
 * Purpose:  xuat ra cac quiz duoc choi nhieu nhat tren website
 * @author Monte Ohrt <monte at ohrt dot com>
 * @link http://smarty.php.net/manual/en/language.function.counter.php {counter}
 *       (Smarty online manual)
 * @param array parameters
 * @param Smarty
 * @return string|null
 */
function smarty_function_gettopquiz($params, &$smarty)
{
    $topQuiz = Core_Quiz::getQuizzes(array(), 'play', 'DESC', 10);
     if (!isset($params['var'])) {
        $compiler->_syntax_error("assign: missing 'var' parameter", E_USER_WARNING);
        return;
    }


    return $smarty->assign($params['var'], $topQuiz);
    
}

/* vim: set expandtab: */

?>
