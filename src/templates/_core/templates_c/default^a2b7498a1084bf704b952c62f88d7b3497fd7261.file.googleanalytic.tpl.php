<?php /* Smarty version Smarty-3.0.7, created on 2015-03-31 06:14:58
         compiled from "/Library/WebServer/Documents/www/litpiproject/github/litpi-framework-3/src/templates/default/googleanalytic.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2524316285519d8f2d43cf0-66950744%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a2b7498a1084bf704b952c62f88d7b3497fd7261' => 
    array (
      0 => '/Library/WebServer/Documents/www/litpiproject/github/litpi-framework-3/src/templates/default/googleanalytic.tpl',
      1 => 1427757149,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2524316285519d8f2d43cf0-66950744',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_smarty_tpl->getVariable('setting')->value['site']['googleanalyticid']!=''){?>


<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '<?php echo $_smarty_tpl->getVariable('setting')->value['site']['googleanalyticid'];?>
']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>



<?php }?>