<?php /* Smarty version Smarty-3.0.7, created on 2015-03-31 06:42:55
         compiled from "/Library/WebServer/Documents/www/litpiproject/github/litpi-framework-3/src/templates/default/_controller/admin/header_leftmenu.tpl" */ ?>
<?php /*%%SmartyHeaderCode:20608552555519df7fc148d6-23934262%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '61456c85261f58f61e538800ca0c4cef0de93d66' => 
    array (
      0 => '/Library/WebServer/Documents/www/litpiproject/github/litpi-framework-3/src/templates/default/_controller/admin/header_leftmenu.tpl',
      1 => 1427758974,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '20608552555519df7fc148d6-23934262',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<div id="navbar">
    <div class="well sidebar-nav">
		<h2>ADMINISTRATOR</h2>
        <ul class="nav nav-list" id="administrator" <?php if ($_smarty_tpl->getVariable('request')->value->cookies->get('navActive')=='administrator'){?>style="overflow: hidden; display: block;"<?php }?>>
			<li id="menu_codegenerator"><a class="sidebar-link" href="<?php echo $_smarty_tpl->getVariable('conf')->value['rooturl_admin'];?>
codegenerator"><i class="fa fa-magic"></i> Code Generator</a></li>
			<li id="menu_user_list"><a class="sidebar-link" href="<?php echo $_smarty_tpl->getVariable('conf')->value['rooturl_admin'];?>
user"><i class="fa fa-users"></i> Users</a></li>
        </ul>

    </div><!--/.well -->
</div><!--/span-->
