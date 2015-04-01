<?php /* Smarty version Smarty-3.0.7, created on 2015-03-31 06:43:13
         compiled from "/Library/WebServer/Documents/www/litpiproject/github/litpi-framework-3/src/templates/default/_controller/admin/user/add.tpl" */ ?>
<?php /*%%SmartyHeaderCode:9776648745519df911c1a79-47442089%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b583f691f722e99a06544b3202576fd0ab964c28' => 
    array (
      0 => '/Library/WebServer/Documents/www/litpiproject/github/litpi-framework-3/src/templates/default/_controller/admin/user/add.tpl',
      1 => 1418794566,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9776648745519df911c1a79-47442089',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<div class="header-row">
	<div class="header-row-wrapper">
		<header>
			<h1 class="header-main" id="page-header" rel="menu_user_list">
				<i class="fa fa-users"></i>
				<span class="breadcrumb"><a href="<?php echo $_smarty_tpl->getVariable('conf')->value['rooturl_admin'];?>
<?php echo $_smarty_tpl->getVariable('controller')->value;?>
" title="">Users</a> /</span>
				Add new User
			</h1>
			<div class="header-right pull-right">
				<a class="btn btn-default" href="<?php echo $_smarty_tpl->getVariable('conf')->value['rooturl_admin'];?>
<?php echo $_smarty_tpl->getVariable('controller')->value;?>
">Cancel</a>
				<a class="btn btn-success" href="javascript:void(0)" onclick="$('#myform').submit();">Save</a>
			</div>
		</header>
	</div>
</div>


<form action="" id="myform" method="post" name="myform" class="form-horizontal">
<input type="hidden" name="ftoken" value="<?php echo $_smarty_tpl->getVariable('session')->value->get('userAddToken');?>
" />

	<?php $_template = new Smarty_Internal_Template("notify.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->assign('notifyError',$_smarty_tpl->getVariable('error')->value);$_template->assign('notifySuccess',$_smarty_tpl->getVariable('success')->value);$_template->assign('notifyWarning',$_smarty_tpl->getVariable('warning')->value); echo $_template->getRenderedTemplate();?><?php unset($_template);?>


	<div class="row section">
		<div class="col-md-3 section-summary">
			<h1>User Overview</h1>
			<p>General Information about User</p>
		</div>
		<div class="col-md-9 section-content">
			<div class="col-md-6 ssb clear inner-left">
				<label for="fgroupid">Account Group <span class="star_require">*</span></label>
				<select id="fgroupid" name="fgroupid" class="col-md-12">
					<option value="">- - - -</option>
					<?php  $_smarty_tpl->tpl_vars['groupname'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('userGroups')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['groupname']->key => $_smarty_tpl->tpl_vars['groupname']->value){
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['groupname']->key;
?>
							<option value="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" <?php if ($_smarty_tpl->getVariable('formData')->value['fgroupid']==$_smarty_tpl->tpl_vars['key']->value){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['groupname']->value;?>
</option>
					<?php }} ?>
				</select>
			</div>

			<div class="col-md-6 ssb clear inner-left">
				<label for="ffullname">Full Name <span class="star_require">*</span></label>
				<input type="text" name="ffullname" id="ffullname" value="<?php echo htmlspecialchars($_smarty_tpl->getVariable('formData')->value['ffullname']);?>
" class="">
			</div>

			<div class="col-md-6 ssb clear inner-left">
				<label for="femail">Email <span class="star_require">*</span></label>
				<input type="text" name="femail" id="femail" value="<?php echo htmlspecialchars($_smarty_tpl->getVariable('formData')->value['femail']);?>
" class="">
			</div>

			<div class="ssb clear">
				<div class="col-md-6 inner-left">
					<label for="fpassword">Password <span class="star_require">*</span></label>
					<input type="password" name="fpassword" id="fpassword" />
				</div>
				<div class="col-md-6 inner-right">
					<label for="fpassword2">Retype Password <span class="star_require">*</span></label>
					<input type="password" name="fpassword2" id="fpassword2" />
				</div>
			</div>

		</div>
	</div><!-- end .section -->

	<div class="row section buttons">
		<input type="hidden" name="fsubmit" value="1" />
		<input type="submit" value="<?php echo $_smarty_tpl->getVariable('lang')->value['default']['formAddSubmit'];?>
" class="btn btn-success" />
		<span class="pull-left"><span class="star_require">*</span> : <?php echo $_smarty_tpl->getVariable('lang')->value['default']['formRequiredLabel'];?>
</span>
	</div>

</form>

