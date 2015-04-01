<?php /* Smarty version Smarty-3.0.7, created on 2015-03-31 06:42:09
         compiled from "/Library/WebServer/Documents/www/litpiproject/github/litpi-framework-3/src/templates/default/_controller/admin/index/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:9642996355519df51b47e01-00760196%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '67339782dc3ecca78de61e800003cde5e58686c5' => 
    array (
      0 => '/Library/WebServer/Documents/www/litpiproject/github/litpi-framework-3/src/templates/default/_controller/admin/index/index.tpl',
      1 => 1418794209,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9642996355519df51b47e01-00760196',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_modifier_date_format')) include '/Library/WebServer/Documents/www/litpiproject/github/litpi-framework-3/src/libs/smarty/plugins/modifier.date_format.php';
?><div class="header-row">
	<div class="header-row-wrapper">
		<header>
			<h1 class="header-main">
				<i class="fa fa-dashboard"></i>
				Dashboard
			</h1>
		</header>
	</div>
</div>


<div class="col-md-12 ">
<table class="table table-striped">
	<thead>
		<tr>
			<th colspan="2"><h1>System Information</h1></th>
		</tr>
	</thead>
	<tr>
		<td width="200" class="td_right">Server IP :</td>
		<td><?php echo $_smarty_tpl->getVariable('formData')->value['fserverip'];?>
</td>
	</tr>
	<tr>
		<td class="td_right">Server Name :</td>
		<td><?php echo $_smarty_tpl->getVariable('formData')->value['fserver'];?>
</td>
	</tr>
	<tr>
		<td class="td_right">PHP Version :</td>
		<td><?php echo $_smarty_tpl->getVariable('formData')->value['fphp'];?>
</td>
	</tr>

	<tr>
		<td class="td_right">Server Time :</td>
		<td><?php echo smarty_modifier_date_format(time(),$_smarty_tpl->getVariable('lang')->value['default']['dateFormatTimeSmarty']);?>
</td>
	</tr>
</table>
</div>