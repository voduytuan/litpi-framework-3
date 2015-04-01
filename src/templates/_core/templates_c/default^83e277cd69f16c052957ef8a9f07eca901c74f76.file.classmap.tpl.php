<?php /* Smarty version Smarty-3.0.7, created on 2015-03-31 06:43:26
         compiled from "/Library/WebServer/Documents/www/litpiproject/github/litpi-framework-3/src/templates/default/_controller/admin/codegenerator/classmap.tpl" */ ?>
<?php /*%%SmartyHeaderCode:14750988745519df9ed63d44-08363102%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '83e277cd69f16c052957ef8a9f07eca901c74f76' => 
    array (
      0 => '/Library/WebServer/Documents/www/litpiproject/github/litpi-framework-3/src/templates/default/_controller/admin/codegenerator/classmap.tpl',
      1 => 1418794209,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14750988745519df9ed63d44-08363102',
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
				<span class="breadcrumb"><a href="<?php echo $_smarty_tpl->getVariable('conf')->value['rooturl_admin'];?>
<?php echo $_smarty_tpl->getVariable('controller')->value;?>
" title="">Code Generator</a> /</span>
				Classmap Generating
			</h1>
			<div class="header-right pull-right">
				<a class="btn btn-default" href="<?php echo $_smarty_tpl->getVariable('conf')->value['rooturl_admin'];?>
<?php echo $_smarty_tpl->getVariable('controller')->value;?>
">Cancel</a>
			</div>
		</header>
	</div>
</div>

<div class="col-md-12">
	<p>Because Litpi follow PSR-0, so Classname of Controller must map the controller filename. So that, we need Classmap to mapping route url to correct controller file name. [<a href="https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md" target="_blank">Learn about PSR-0</a>]</p>
</div>

<?php $_template = new Smarty_Internal_Template("notify.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->assign('notifyError',$_smarty_tpl->getVariable('error')->value);$_template->assign('notifySuccess',$_smarty_tpl->getVariable('success')->value);$_template->assign('notifyWarning',$_smarty_tpl->getVariable('warning')->value); echo $_template->getRenderedTemplate();?><?php unset($_template);?>

<div class="col-md-12">


	<textarea class="textarea" rows="20" style="font-family: Courier New, mono-space; font-size:12px;"><?php echo $_smarty_tpl->getVariable('classmapFiledata')->value;?>
</textarea>

</div>