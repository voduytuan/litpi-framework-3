<?php /* Smarty version Smarty-3.0.7, created on 2015-03-31 06:43:18
         compiled from "/Library/WebServer/Documents/www/litpiproject/github/litpi-framework-3/src/templates/default/_controller/admin/user/edit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:17099541425519df962f7051-04259762%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7017e56bb5a8b5d6624851c53ff93dc027ca1bbf' => 
    array (
      0 => '/Library/WebServer/Documents/www/litpiproject/github/litpi-framework-3/src/templates/default/_controller/admin/user/edit.tpl',
      1 => 1418794566,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17099541425519df962f7051-04259762',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_modifier_date_format')) include '/Library/WebServer/Documents/www/litpiproject/github/litpi-framework-3/src/libs/smarty/plugins/modifier.date_format.php';
?><div class="header-row">
	<div class="header-row-wrapper">
		<header>
			<h1 class="header-main" id="page-header" rel="menu_user_list">
				<i class="fa fa-users"></i>
				<span class="breadcrumb"><a href="<?php echo $_smarty_tpl->getVariable('conf')->value['rooturl_admin'];?>
<?php echo $_smarty_tpl->getVariable('controller')->value;?>
" title="">Users</a> /</span>
				Edit
			</h1>
			<div class="header-right pull-right">
				<a class="btn btn-default" href="<?php echo $_smarty_tpl->getVariable('conf')->value['rooturl_admin'];?>
<?php echo $_smarty_tpl->getVariable('controller')->value;?>
">Cancel</a>
				<a class="btn btn-success" href="javascript:void(0)" onclick="$('#myform').submit();"><?php echo $_smarty_tpl->getVariable('lang')->value['default']['formUpdateSubmit'];?>
</a>
			</div>
		</header>
	</div>
</div>


<form action="" id="myform" method="post" name="myform" class="form-horizontal">
<input type="hidden" name="ftoken" value="<?php echo $_smarty_tpl->getVariable('session')->value->get('userEditToken');?>
" />

	<?php $_template = new Smarty_Internal_Template("notify.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->assign('notifyError',$_smarty_tpl->getVariable('error')->value);$_template->assign('notifySuccess',$_smarty_tpl->getVariable('success')->value);$_template->assign('notifyWarning',$_smarty_tpl->getVariable('warning')->value); echo $_template->getRenderedTemplate();?><?php unset($_template);?>

	<div class="row section">
		<div class="col-md-3 section-summary">
			<h1>User Overview</h1>
			<p>General Information about User</p>
		</div>
		<div class="col-md-9 section-content">
			<div class="col-md-6 ssb clear">
				<label for="fgroupid">Group</label>
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

			<div class="col-md-6 ssb clear">
				<label for="femail">Email</label>
				<input type="text" name="femail" id="femail" disabled="disabled" value="<?php echo htmlspecialchars($_smarty_tpl->getVariable('formData')->value['femail']);?>
" />
			</div>

			<div class="col-md-6 ssb clear">
				<label for="ffullname">Full Name</label>
				<input type="text" name="ffullname" id="ffullname" value="<?php echo htmlspecialchars($_smarty_tpl->getVariable('formData')->value['ffullname']);?>
" />
			</div>

			<div class="col-md-6 ssb clear">
				<label for="fgender">Gender</label>
				<select id="fgender" name="fgender">
					<option value="">- - - -</option>
					<option value="1" <?php if ($_smarty_tpl->getVariable('formData')->value['fgender']=='1'){?>selected="selected"<?php }?>>Male</option>
	                <option value="2" <?php if ($_smarty_tpl->getVariable('formData')->value['fgender']=='2'){?>selected="selected"<?php }?>>Female</option>
				</select>
			</div>
		</div>
	</div>


	<div class="row section">
		<div class="col-md-3 section-summary">
			<h1>More Information</h1>
			<p>Information about contact, introduction</p>
		</div>
		<div class="col-md-9 section-content">
			<div class="col-md-6 ssb clear">
				<label for="fbirthday">Birthday</label>
				<input type="text" name="fbirthday" id="fbirthday" value="<?php echo htmlspecialchars($_smarty_tpl->getVariable('formData')->value['fbirthday']);?>
" class="">
			</div>

			<div class="col-md-6 ssb clear">
				<label for="fphone">Phone</label>
				<input type="text" name="fphone" id="fphone" value="<?php echo htmlspecialchars($_smarty_tpl->getVariable('formData')->value['fphone']);?>
" class="">
			</div>

			<div class="col-md-12 ssb clear">
				<div class="col-md-8 inner-left">
					<label for="faddress">Address</label>
					<input type="text" name="faddress" id="faddress" value="<?php echo htmlspecialchars($_smarty_tpl->getVariable('formData')->value['faddress']);?>
">
				</div>
				<div class="col-md-4 inner-right">
					<label for="fregion">Region</label>
					<select  name="fregion" id="fregion" class="col-md-12">
						<option value="0">- - - Choose a Region - - -</option>
						<?php  $_smarty_tpl->tpl_vars['region'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['regionid'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('setting')->value['region']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['region']->key => $_smarty_tpl->tpl_vars['region']->value){
 $_smarty_tpl->tpl_vars['regionid']->value = $_smarty_tpl->tpl_vars['region']->key;
?>
						<option <?php if ($_smarty_tpl->tpl_vars['regionid']->value==$_smarty_tpl->getVariable('formData')->value['fregion']){?>selected="selected" <?php }?> value="<?php echo $_smarty_tpl->tpl_vars['regionid']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['region']->value;?>
</option>
						<?php }} ?>
					</select>
					<input type="hidden" name="fcountry" id="fcountry" size="2" value="<?php echo htmlspecialchars($_smarty_tpl->getVariable('formData')->value['fcountry']);?>
">
				</div>
			</div>

			<div class="col-md-12 ssb clear">
				<label for="fwebsite">Website</label>
				<input type="text" name="fwebsite" id="fwebsite" value="<?php echo htmlspecialchars($_smarty_tpl->getVariable('formData')->value['fwebsite']);?>
" />
			</div>

			<div class="col-md-12 clear">
				<label for="fbio">Bio</label>
				<div class="expanding-textarea expanded">
					<pre><span><br /></span></pre>
					<textarea name="fbio" row="6" cols="80" class="col-md-12"><?php echo $_smarty_tpl->getVariable('formData')->value['fbio'];?>
</textarea>
				</div>
			</div>
		</div>
	</div>

	<div class="row section">
		<div class="col-md-3 section-summary">
			<h1>Date & Time</h1>
			<p>Date time tracking</p>
		</div>
		<div class="col-md-9 section-content">
			<div class="col-md-12 ssb clear">
				<label>Date Registered: <span class="note"><?php echo smarty_modifier_date_format($_smarty_tpl->getVariable('myUser')->value->datecreated,"%H:%M:%S - %A, %B %e, %Y");?>
 (IP AddressL <?php echo $_smarty_tpl->getVariable('myUser')->value->ipaddress;?>
)</span></label>
			</div>

			<div class="col-md-12 ssb clear">
				<label>Date Modified: <span class="note"><?php if ($_smarty_tpl->getVariable('myUser')->value->datemodified>0){?><?php echo smarty_modifier_date_format($_smarty_tpl->getVariable('myUser')->value->datemodified,"%H:%M:%S - %A, %B %e, %Y");?>
<?php }else{ ?>n/a<?php }?></span></label>
			</div>

			<div class="col-md-12 ssb clear">
				<label>Date Last Login : <span class="note"><?php if ($_smarty_tpl->getVariable('myUser')->value->datelastlogin>0){?><?php echo smarty_modifier_date_format($_smarty_tpl->getVariable('myUser')->value->datelastlogin,"%H:%M:%S - %A, %B %e, %Y");?>
<?php }else{ ?>n/a<?php }?></span></label>
			</div>
		</div>
	</div>



	<?php if ($_smarty_tpl->getVariable('myUser')->value->avatar!=''){?>
	<div class="control-group">
		<label class="control-label">Avatar</label>
		<div class="controls">
			<a href="<?php echo $_smarty_tpl->getVariable('conf')->value['rooturl'];?>
<?php echo $_smarty_tpl->getVariable('setting')->value['avatar']['imageDirectory'];?>
<?php echo $_smarty_tpl->getVariable('myUser')->value->avatar;?>
" target="_blank"><img src="<?php echo $_smarty_tpl->getVariable('conf')->value['rooturl'];?>
<?php echo $_smarty_tpl->getVariable('setting')->value['avatar']['imageDirectory'];?>
<?php echo $_smarty_tpl->getVariable('myUser')->value->thumbImage();?>
" width="100" border="0" /></a><input type="checkbox" name="fdeleteimage" value="1" />Delete<br />
		</div>
	</div>
	<?php }?>


	<div class="row section">
		<div class="col-md-3 section-summary">
			<h1>Open Authentication</h1>
			<p>Login from other service via OAuth (Ex: facebook, google...)</p>
		</div>
		<div class="col-md-9 section-content">
			<div class="col-md-12">
				<div class="col-md-6 inner-left">
					<label for="foauthpartner">Service</label>
					<select name="foauthpartner" id="foauthpartner" class="col-md-12">
						<option value="0">- - Not used - -</option>
						<option value="1" <?php if ($_smarty_tpl->getVariable('formData')->value['foauthpartner']==1){?>selected="selected"<?php }?>>Facebook</option>
						<option value="2" <?php if ($_smarty_tpl->getVariable('formData')->value['foauthpartner']==2){?>selected="selected"<?php }?>>Yahoo</option>
						<option value="3" <?php if ($_smarty_tpl->getVariable('formData')->value['foauthpartner']==3){?>selected="selected"<?php }?>>Google</option>
					</select>
				</div>

				<div class="col-md-6 inner-right">
					<label for="foauthuid">OAuth ID</label>
					<input type="text" name="foauthuid"  id="foauthuid" value="<?php echo $_smarty_tpl->getVariable('formData')->value['foauthuid'];?>
">
				</div>
			</div>
		</div>
	</div>

	<div class="row section buttons">
		<span class="pull-left"><span class="star_require">*</span> : <?php echo $_smarty_tpl->getVariable('lang')->value['default']['formRequiredLabel'];?>
</span>

		<input type="hidden" name="fsubmit" value="1" />
		<input type="submit" value="<?php echo $_smarty_tpl->getVariable('lang')->value['default']['formUpdateSubmit'];?>
" class="btn btn-large btn-success" />
		<a href="javascript:void(0)" id="resetpasswordbutton" onclick="resetpasswordHandler('<?php echo $_smarty_tpl->getVariable('conf')->value['rooturl_admin'];?>
user/resetpass/id/<?php echo $_smarty_tpl->getVariable('myUser')->value->id;?>
')" class="btn btn-warning btn-pull-right">Reset Password</a>
	</div>

</form>

