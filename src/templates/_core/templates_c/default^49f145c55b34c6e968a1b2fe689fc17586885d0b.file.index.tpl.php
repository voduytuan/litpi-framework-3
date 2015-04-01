<?php /* Smarty version Smarty-3.0.7, created on 2015-03-31 06:43:04
         compiled from "/Library/WebServer/Documents/www/litpiproject/github/litpi-framework-3/src/templates/default/_controller/admin/user/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:4573791625519df88687d77-81293879%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '49f145c55b34c6e968a1b2fe689fc17586885d0b' => 
    array (
      0 => '/Library/WebServer/Documents/www/litpiproject/github/litpi-framework-3/src/templates/default/_controller/admin/user/index.tpl',
      1 => 1418794209,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4573791625519df88687d77-81293879',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_function_html_options')) include '/Library/WebServer/Documents/www/litpiproject/github/litpi-framework-3/src/libs/smarty/plugins/function.html_options.php';
?><div class="header-row">
	<div class="header-row-wrapper">
		<header>
			<h1 class="header-main" id="page-header" rel="menu_user_list">
				<i class="fa fa-users"></i>
				<?php if ($_smarty_tpl->getVariable('formData')->value['search']!=''){?>
					<span class="breadcrumb"><a href="<?php echo $_smarty_tpl->getVariable('conf')->value['rooturl_admin'];?>
<?php echo $_smarty_tpl->getVariable('controller')->value;?>
" title="<?php echo $_smarty_tpl->getVariable('lang')->value['default']['formViewAll'];?>
">Users</a> / </span>
					Search result (<span class="delete-decrease-count"><?php echo number_format($_smarty_tpl->getVariable('total')->value);?>
</span>)
				<?php }else{ ?>
					Users (<span class="delete-decrease-count"><?php echo number_format($_smarty_tpl->getVariable('total')->value);?>
</span>)
				<?php }?>
			</h1>
			<div class="header-right">
				<a class="btn btn-success pull-right" href="<?php echo $_smarty_tpl->getVariable('conf')->value['rooturl_admin'];?>
<?php echo $_smarty_tpl->getVariable('controller')->value;?>
/add"><?php echo $_smarty_tpl->getVariable('lang')->value['controller']['head_add'];?>
</a>
				<div class="formfilterpaginatorwrapper pull-right"></div>
			</div>
		</header>
	</div>
</div>


<div class="col-md-12 filter-form">
	<div class="input-group">
      <div class="input-group-btn">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Filter Users <span class="caret"></span></button>
        <div class="dropdown-menu dropdown-filter-box">
        	<h3>Show all Users where:</h3>
        	<select id="ffilter" class="input-sm" onchange="formfilterToggle()">
        		<option value="">Select a filter..</option>
        		<option value="email"><?php echo $_smarty_tpl->getVariable('lang')->value['controller']['labelEmail'];?>
</option>
				<option value="groupid"><?php echo $_smarty_tpl->getVariable('lang')->value['controller']['labelGroup'];?>
</option>
				<option value="region"><?php echo $_smarty_tpl->getVariable('lang')->value['controller']['labelRegion'];?>
</option>
				<option value="gender"><?php echo $_smarty_tpl->getVariable('lang')->value['controller']['labelGender'];?>
</option>
				<option value="id"><?php echo $_smarty_tpl->getVariable('lang')->value['controller']['labelId'];?>
</option>

        	</select>

        	<input type="text" id="filter_email_more" value="<?php echo $_smarty_tpl->getVariable('formData')->value['femail'];?>
" class="filter_more hideinit input-sm" />
			<select id="filter_groupid_more" class="filter_more hideinit input-sm">
                        <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->getVariable('userGroups')->value,'selected'=>$_smarty_tpl->getVariable('formData')->value['fgroupid']),$_smarty_tpl);?>

                    </select>

			<select  name="fregion" id="filter_region_more" class="filter_more hideinit input-sm">
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


			<select id="filter_gender_more" class="filter_more hideinit input-sm">
					<option value="1" <?php if ($_smarty_tpl->getVariable('formData')->value['fgender']=='1'){?>selected="selected"<?php }?>>Male</option>
	                <option value="2" <?php if ($_smarty_tpl->getVariable('formData')->value['fgender']=='2'){?>selected="selected"<?php }?>>Female</option>
	        </select>

			<input type="text" id="filter_id_more" value="<?php echo $_smarty_tpl->getVariable('formData')->value['fid'];?>
" class="filter_more hideinit input-sm" />


        	&nbsp;
        	<input type="button" onclick="formfilterAdd()" class="btn btn-sm btn-info inline" value="Add Filter" />
        </div>
      </div><!-- /btn-group -->
      <input type="text" id="fkeywordfilter" class="form-control filter-indicator" value="<?php echo $_smarty_tpl->getVariable('formData')->value['keyword'];?>
" placeholder="Start typing to search in <?php echo $_smarty_tpl->getVariable('lang')->value['controller']['labelEmail'];?>
, <?php echo $_smarty_tpl->getVariable('lang')->value['controller']['labelFullname'];?>
..." />
    </div><!-- /input-group -->
</div>

<div class="filter-tags clear">
	<ul class="col-md-12 active-filters horizontal">
		<?php if (count($_smarty_tpl->getVariable('formData')->value['filtertaglist'])>0){?>
			<?php  $_smarty_tpl->tpl_vars['filtertag'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('formData')->value['filtertaglist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['filtertag']->key => $_smarty_tpl->tpl_vars['filtertag']->value){
?>
				<li class="tag closable filtertag" data-filtername="<?php echo $_smarty_tpl->tpl_vars['filtertag']->value['name'];?>
" data-filtervalue="<?php echo $_smarty_tpl->tpl_vars['filtertag']->value['value'];?>
" id="filtertag_<?php echo $_smarty_tpl->tpl_vars['filtertag']->value['name'];?>
"><span><em><?php echo $_smarty_tpl->tpl_vars['filtertag']->value['namelabel'];?>
 is equal to <b><?php echo $_smarty_tpl->tpl_vars['filtertag']->value['value'];?>
</b></em><span class="close" onclick="$('#filtertag_<?php echo $_smarty_tpl->tpl_vars['filtertag']->value['name'];?>
').remove();$('#page').val('1');formfilterRefresh();"><i class="fa fa-times"></i></span></span></li>
			<?php }} ?>
		<?php }?>
	</ul>
</div>

<div class="col-md-12">
	<?php $_template = new Smarty_Internal_Template("notify.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->assign('notifyError',$_smarty_tpl->getVariable('error')->value);$_template->assign('notifySuccess',$_smarty_tpl->getVariable('success')->value); echo $_template->getRenderedTemplate();?><?php unset($_template);?>

	<form action="" method="post" name="manage" class="form-horizontal formfilter-enable">
		<input type="hidden" id="pageurl" value="<?php echo $_smarty_tpl->getVariable('conf')->value['rooturl'];?>
<?php echo $_smarty_tpl->getVariable('module')->value;?>
/<?php echo $_smarty_tpl->getVariable('controller')->value;?>
" />
		<input type="hidden" id="filterurl" value="<?php echo $_smarty_tpl->getVariable('conf')->value['rooturl'];?>
<?php echo $_smarty_tpl->getVariable('module')->value;?>
/<?php echo $_smarty_tpl->getVariable('controller')->value;?>
/jsondata" />
		<input type="hidden" id="token" value="" />
		<input type="hidden" id="page" value="<?php echo (($tmp = @$_smarty_tpl->getVariable('formData')->value['page'])===null||$tmp==='' ? 1 : $tmp);?>
" />
		<input type="hidden" id="sortby" value="<?php echo $_smarty_tpl->getVariable('formData')->value['sortby'];?>
" />
		<input type="hidden" id="sorttype" value="<?php echo $_smarty_tpl->getVariable('formData')->value['sorttype'];?>
" />

		<table class="table table-hover hideinit">
			<thead>
				<tr>
				   <th width="40"><input class="check-all" type="checkbox" /></th>

					<th class="formfilterth is-sortable" id="id"><?php echo $_smarty_tpl->getVariable('lang')->value['controller']['labelId'];?>
</th>
					<th class="formfilterth" id="fullname"><?php echo $_smarty_tpl->getVariable('lang')->value['controller']['labelFullname'];?>
</th>
					<th class="formfilterth" id="email"><?php echo $_smarty_tpl->getVariable('lang')->value['controller']['labelEmail'];?>
</th>
					<th class="formfilterth" id="groupid"><?php echo $_smarty_tpl->getVariable('lang')->value['controller']['labelGroupid'];?>
</th>
					<th class="formfilterth" id="gender"><?php echo $_smarty_tpl->getVariable('lang')->value['controller']['labelGender'];?>
</th>
					<th class="formfilterth" id="region"><?php echo $_smarty_tpl->getVariable('lang')->value['controller']['labelRegion'];?>
</th>
					<th class="formfilterth is-sortable" id="datelastaction"><a data-sortby="datelastaction"><span><?php echo $_smarty_tpl->getVariable('lang')->value['controller']['labelDatelastaction'];?>
</span></a></th>
					<th class="formfilterth" id="datecreated"><?php echo $_smarty_tpl->getVariable('lang')->value['controller']['labelDatecreated'];?>
</th>
					<th width="100"></th>
				</tr>
			</thead>

			<tfoot>
				<tr>
					<td colspan="10">
						<div class="bulk-actions align-left hideinit">
							<input type="hidden" id="bulkActionInvalidWarn" value="<?php echo $_smarty_tpl->getVariable('lang')->value['default']['bulkActionInvalidWarn'];?>
" />
							<input type="hidden" id="bulkItemNoSelected" value="<?php echo $_smarty_tpl->getVariable('lang')->value['default']['bulkItemNoSelected'];?>
" />
							<select name="fbulkaction" id="fbulkaction">
								<option value=""><?php echo $_smarty_tpl->getVariable('lang')->value['default']['bulkActionSelectLabel'];?>
</option>
								<option value="delete"><?php echo $_smarty_tpl->getVariable('lang')->value['default']['bulkActionDeletetLabel'];?>
</option>
							</select>
							<input type="button" name="fsubmitbulk" id="fsubmitbulk" class="inline btn btn-sm btn-primary" value="<?php echo $_smarty_tpl->getVariable('lang')->value['default']['bulkActionSubmit'];?>
" onclick="formbulksubmit('<?php echo $_smarty_tpl->getVariable('conf')->value['rooturl'];?>
<?php echo $_smarty_tpl->getVariable('module')->value;?>
/<?php echo $_smarty_tpl->getVariable('controller')->value;?>
/bulkapply')" />
						</div>
					</td>
				</tr>
			</tfoot>
			<tbody>

			</tbody>
		</table>
	</form>

	<div class="formfilter-empty hideinit">
		<div><i class="fa fa-users"></i></div>
		<p><?php echo $_smarty_tpl->getVariable('lang')->value['controller']['errNotFound'];?>
</p>
	</div>

</div>



