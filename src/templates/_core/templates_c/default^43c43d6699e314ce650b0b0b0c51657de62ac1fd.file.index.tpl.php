<?php /* Smarty version Smarty-3.0.7, created on 2015-03-31 06:42:58
         compiled from "/Library/WebServer/Documents/www/litpiproject/github/litpi-framework-3/src/templates/default/_controller/admin/codegenerator/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:17275944805519df82b950e8-58354738%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '43c43d6699e314ce650b0b0b0c51657de62ac1fd' => 
    array (
      0 => '/Library/WebServer/Documents/www/litpiproject/github/litpi-framework-3/src/templates/default/_controller/admin/codegenerator/index.tpl',
      1 => 1418794209,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17275944805519df82b950e8-58354738',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<div class="header-row">
	<div class="header-row-wrapper">
		<header>
			<h1 class="header-main" id="page-header" rel="menu_codegenerator">
				<i class="fa fa-magic"></i>
				Code Generator
			</h1>
			<div class="header-right pull-right">
				<a class="btn btn-default" href="<?php echo $_smarty_tpl->getVariable('conf')->value['rooturl_admin'];?>
utility/passwordgenerator"><i class="fa fa-asterisk"></i> Generate Password</a>
				<a class="btn btn-default" href="<?php echo $_smarty_tpl->getVariable('conf')->value['rooturl_admin'];?>
<?php echo $_smarty_tpl->getVariable('controller')->value;?>
/classmap"><i class="fa fa-folder"></i> Generate Classmap</a>
			</div>
		</header>
	</div>
</div>

<div class="col-md-12">
	<table class="table table-hover" cellpadding="5" width="100%">
		<thead>
			<tr>
				<th>Select a Table to generate</th>
			</tr>
		</thead>

		<?php if (count($_smarty_tpl->getVariable('tables')->value)>0){?>
			<tbody>
			<?php  $_smarty_tpl->tpl_vars['table'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('tables')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['table']->key => $_smarty_tpl->tpl_vars['table']->value){
?>
				<tr>
					<td><a title="Code generate for <?php echo $_smarty_tpl->tpl_vars['table']->value;?>
" href="<?php echo $_smarty_tpl->getVariable('conf')->value['rooturl_admin'];?>
codegenerator/generate/table/<?php echo $_smarty_tpl->tpl_vars['table']->value;?>
/redirect/<?php echo $_smarty_tpl->getVariable('redirectUrl')->value;?>
" class="btn"><i class="icon-circle-arrow-right icon-white"></i> <?php echo $_smarty_tpl->tpl_vars['table']->value;?>
</a></td>
				</tr>
			<?php }} ?>
			</tbody>
		<?php }else{ ?>
			<tbody>
				<tr>
					<td>There is no table in current database to generate Model.</td>
				</tr>

			</tbody>
		<?php }?>


	</table>
</div>







