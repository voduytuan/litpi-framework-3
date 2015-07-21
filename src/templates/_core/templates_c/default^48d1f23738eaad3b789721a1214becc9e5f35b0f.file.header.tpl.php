<?php /* Smarty version Smarty-3.0.7, created on 2015-03-31 06:11:29
         compiled from "/Library/WebServer/Documents/www/litpiproject/github/litpi-framework-3/src/templates/default/_controller/site/header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:10997594895519d821226f15-60570140%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '48d1f23738eaad3b789721a1214becc9e5f35b0f' => 
    array (
      0 => '/Library/WebServer/Documents/www/litpiproject/github/litpi-framework-3/src/templates/default/_controller/site/header.tpl',
      1 => 1389860380,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '10997594895519d821226f15-60570140',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_modifier_escapequote')) include '/Library/WebServer/Documents/www/litpiproject/github/litpi-framework-3/src/libs/smarty/plugins/modifier.escapequote.php';
?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php if ($_smarty_tpl->getVariable('pageTitle')->value!=''){?><?php echo $_smarty_tpl->getVariable('pageTitle')->value;?>
 - <?php echo $_smarty_tpl->getVariable('setting')->value['site']['heading'];?>
<?php }else{ ?><?php echo $_smarty_tpl->getVariable('setting')->value['site']['defaultPageTitle'];?>
<?php }?></title>
<meta name="keywords" content="<?php echo smarty_modifier_escapequote((($tmp = @$_smarty_tpl->getVariable('pageKeyword')->value)===null||$tmp==='' ? $_smarty_tpl->getVariable('setting')->value['site']['defaultPageKeyword'] : $tmp));?>
" />
<meta name="description" content="<?php echo smarty_modifier_escapequote((($tmp = @$_smarty_tpl->getVariable('pageDescription')->value)===null||$tmp==='' ? $_smarty_tpl->getVariable('setting')->value['site']['defaultPageDescription'] : $tmp));?>
" />
<link rel="shortcut icon" href="<?php echo $_smarty_tpl->getVariable('conf')->value['rooturl'];?>
favicon.ico" type="image/x-icon">
<link rel="icon" href="<?php echo $_smarty_tpl->getVariable('conf')->value['rooturl'];?>
favicon.ico" type="image/x-icon">

<link type="text/css" rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('currentTemplate')->value;?>
min/index.php?g=css&ver=<?php echo $_smarty_tpl->getVariable('setting')->value['site']['cssversion'];?>
" media="screen" />
<script  type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('currentTemplate')->value;?>
js/jquery.js"></script>
<script src="<?php echo $_smarty_tpl->getVariable('currentTemplate')->value;?>
min/index.php?g=js&ver=<?php echo $_smarty_tpl->getVariable('setting')->value['site']['jsversion'];?>
"></script>

<script type="text/javascript">
		var rooturl = "<?php echo $_smarty_tpl->getVariable('conf')->value['rooturl'];?>
";
		var imageDir = "<?php echo $_smarty_tpl->getVariable('imageDir')->value;?>
";
</script>
</head>

<body class="animated bounceInDown">