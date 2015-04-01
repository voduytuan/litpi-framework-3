<?php

/*
* Smarty {case}{/case} block plugin
*
* Type: block function<br>
* Name: case<br>
* Purpose: element inside {switch}...{/switch} block
* author: messju mohr <messju@lammfellpuschen.de>
* slightly Modified & expanded by: dasher <dasher@inspiredthinking.co.uk>
* @param array
* Params: expr: string|numeric to be tested
* @param string contents of the block
* @param Smarty
* @return string
*/
function smarty_block_case($params, $content, &$smarty, &$repeat) {

   if (is_null($content)) {
      /* handle block open tag */

      /* find corresponding {switch}-block */
      for ($i=count($smarty->_tag_stack)-1; $i>=0; $i--) {
         list($tag_name, $tag_params) = $smarty->_tag_stack[$i];
         if ($tag_name=='switch') break;
      }

      if ($i<0) {
         /* {switch} not found */
         $smarty->tigger_error("{case}: block not inside switch}-context");
         return;
      }

      if (isset($tag_params['_done']) && $tag_params['_done']) {
         /* another {case} was already found */
         $repeat = false;
         return;
      }
      
      // $tab_params['expr'] & $params['expr'] needs to be evaluated
      // For now - only worry about the expression passed by the case statement
      
      $testExpression = smarty_block_case_eval($params['expr']);

      if (isset($params['expr']) && ($testExpression!=$tag_params['expr'])) {
         /* page doesn't match */
         $repeat = false;
         return;
      }

      /* page found */
      $smarty->_tag_stack[$i][1]['_done'] = true;
      return;

   } else {
         /* handle block close tag */
         return $content;
   }

}


function smarty_block_case_eval($expression='') {
   // Evaluates the expression
   
   $wrapper = "echo {expression} ;";
   $testExpression = str_ireplace("{expression}", $expression, $wrapper);

   ob_start();
   eval(trim($testExpression));
   $content = ob_get_contents();
   ob_end_clean();
   return $content;
   
}

?>