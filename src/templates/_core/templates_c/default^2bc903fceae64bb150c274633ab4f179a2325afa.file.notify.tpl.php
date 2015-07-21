<?php /* Smarty version Smarty-3.0.7, created on 2015-03-31 06:39:27
         compiled from "/Library/WebServer/Documents/www/litpiproject/github/litpi-framework-3/src/templates/default/notify.tpl" */ ?>
<?php /*%%SmartyHeaderCode:14434664315519deafad9e81-13301470%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2bc903fceae64bb150c274633ab4f179a2325afa' => 
    array (
      0 => '/Library/WebServer/Documents/www/litpiproject/github/litpi-framework-3/src/templates/default/notify.tpl',
      1 => 1417574649,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14434664315519deafad9e81-13301470',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (count($_smarty_tpl->getVariable('notifySuccess')->value)>0){?>
<div class="notify-bar notify-bar-success">
	<div class="notify-bar-button<?php if ($_smarty_tpl->getVariable('hidenotifyclose')->value){?> hide<?php }?>"><a href="javascript:void(0);" onclick="javascript:$(this).parent().parent().fadeOut();" title="close"><img src="<?php echo $_smarty_tpl->getVariable('imageDir')->value;?>
notify/close-btn.png" border="0" alt="close" /></a></div>
	<div class="notify-bar-text">
		<?php if (is_array($_smarty_tpl->getVariable('notifySuccess')->value)){?>
			<ul>
			<?php  $_smarty_tpl->tpl_vars['notifySuccessItem'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('notifySuccess')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['notifySuccessItem']->key => $_smarty_tpl->tpl_vars['notifySuccessItem']->value){
?>
				<li><?php echo $_smarty_tpl->tpl_vars['notifySuccessItem']->value;?>
</li>
			<?php }} ?>
			</ul>
		<?php }else{ ?>
			<p><?php echo $_smarty_tpl->getVariable('notifySuccess')->value;?>
</p>
		<?php }?>
	</div>
</div>
<?php }?>

<?php if (count($_smarty_tpl->getVariable('notifyError')->value)>0){?>
<div class="notify-bar notify-bar-error">
	<div class="notify-bar-button<?php if ($_smarty_tpl->getVariable('hidenotifyclose')->value){?> hide<?php }?>"><a href="javascript:void(0);" onclick="javascript:$(this).parent().parent().fadeOut();" title="close"><img src="<?php echo $_smarty_tpl->getVariable('imageDir')->value;?>
notify/close-btn.png" border="0" alt="close" /></a></div>
	<div class="notify-bar-text">
		<?php if (is_array($_smarty_tpl->getVariable('notifyError')->value)){?>
			<ul>
			<?php  $_smarty_tpl->tpl_vars['notifyErrorItem'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('notifyError')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['notifyErrorItem']->key => $_smarty_tpl->tpl_vars['notifyErrorItem']->value){
?>
				<li><?php echo $_smarty_tpl->tpl_vars['notifyErrorItem']->value;?>
</li>
			<?php }} ?>
			</ul>
		<?php }else{ ?>
			<p><?php echo $_smarty_tpl->getVariable('notifyError')->value;?>
</p>
		<?php }?>
	</div>
</div>
<?php }?>

<?php if (count($_smarty_tpl->getVariable('notifyWarning')->value)>0){?>
<div class="notify-bar notify-bar-warning">
	<div class="notify-bar-button<?php if ($_smarty_tpl->getVariable('hidenotifyclose')->value){?> hide<?php }?>"><a href="javascript:void(0);" onclick="javascript:$(this).parent().parent().fadeOut();" title="close"><img src="<?php echo $_smarty_tpl->getVariable('imageDir')->value;?>
notify/close-btn.png" border="0" alt="close" /></a></div>
	<div class="notify-bar-text">
		<?php if (is_array($_smarty_tpl->getVariable('notifyWarning')->value)){?>
			<ul>
			<?php  $_smarty_tpl->tpl_vars['notifyWarningItem'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('notifyWarning')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['notifyWarningItem']->key => $_smarty_tpl->tpl_vars['notifyWarningItem']->value){
?>
				<li><?php echo $_smarty_tpl->tpl_vars['notifyWarningItem']->value;?>
</li>
			<?php }} ?>
			</ul>
		<?php }else{ ?>
			<p><?php echo $_smarty_tpl->getVariable('notifyWarning')->value;?>
</p>
		<?php }?>
	</div>
</div>
<?php }?>

<?php if (count($_smarty_tpl->getVariable('notifyInformation')->value)>0){?>
<div class="notify-bar notify-bar-info">
	<div class="notify-bar-button<?php if ($_smarty_tpl->getVariable('hidenotifyclose')->value){?> hide<?php }?>"><a href="javascript:void(0);" onclick="javascript:$(this).parent().parent().fadeOut();" title="close"><img src="<?php echo $_smarty_tpl->getVariable('imageDir')->value;?>
notify/close-btn.png" border="0" alt="close" /></a></div>
	<div class="notify-bar-text">
		<?php if (is_array($_smarty_tpl->getVariable('notifyInformation')->value)){?>
			<ul>
			<?php  $_smarty_tpl->tpl_vars['notifyInformationItem'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('notifyInformation')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['notifyInformationItem']->key => $_smarty_tpl->tpl_vars['notifyInformationItem']->value){
?>
				<li><?php echo $_smarty_tpl->tpl_vars['notifyInformationItem']->value;?>
</li>
			<?php }} ?>
			</ul>
		<?php }else{ ?>
			<p><?php echo $_smarty_tpl->getVariable('notifyInformation')->value;?>
</p>
		<?php }?>
	</div>
</div>
<?php }?>