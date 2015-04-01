<?php /* Smarty version Smarty-3.0.7, created on 2015-03-31 06:42:09
         compiled from "/Library/WebServer/Documents/www/litpiproject/github/litpi-framework-3/src/templates/default/_controller/admin/header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:20880119965519df51bc7598-93377720%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5ec932b8403c10ecc3b561b6cf633c2ba98c008e' => 
    array (
      0 => '/Library/WebServer/Documents/www/litpiproject/github/litpi-framework-3/src/templates/default/_controller/admin/header.tpl',
      1 => 1418794209,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '20880119965519df51bc7598-93377720',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html>
<html lang="en">
  <head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

		<title><?php echo $_smarty_tpl->getVariable('pageTitle')->value;?>
</title>

		<!-- Bootstrap Stylesheet -->
		<link rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('currentTemplate')->value;?>
bootstrap/css/bootstrap.min.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('currentTemplate')->value;?>
bootstrap/css/FortAwesome/css/font-awesome.css" type="text/css" media="screen" />

		<!-- Customized Admin Stylesheet -->
		<link type="text/css" rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('currentTemplate')->value;?>
min/index.php?g=cssadmin&ver=<?php echo $_smarty_tpl->getVariable('setting')->value['site']['cssversion'];?>
" media="screen" />

		<!-- jQuery -->
		<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('currentTemplate')->value;?>
js/admin/jquery.js"></script>

		<!-- jQuery History Plugin -->
		<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('currentTemplate')->value;?>
js/admin/jquery.history.js"></script>

		<!-- Bootstrap Js -->
		<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('currentTemplate')->value;?>
bootstrap/js/bootstrap.min.js"></script>

		<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('currentTemplate')->value;?>
js/admin/bootstrap-paginator.js"></script>


		<!-- customized admin -->
		<script src="<?php echo $_smarty_tpl->getVariable('currentTemplate')->value;?>
min/index.php?g=jsadmin&ver=<?php echo $_smarty_tpl->getVariable('setting')->value['site']['jsversion'];?>
"></script>


        <script type="text/javascript">
		var rooturl = "<?php echo $_smarty_tpl->getVariable('conf')->value['rooturl'];?>
";
		var rooturl_admin = "<?php echo $_smarty_tpl->getVariable('conf')->value['rooturl_admin'];?>
";
		var module = "<?php echo $_smarty_tpl->getVariable('module')->value;?>
";
		var currentTemplate = "<?php echo $_smarty_tpl->getVariable('currentTemplate')->value;?>
";

		var delConfirm = "Are You Sure?";
		var delPromptYes = "Type YES to continue";

		var imageDir = "<?php echo $_smarty_tpl->getVariable('imageDir')->value;?>
";
		</script>

	</head>

    <body>


		<div class="navbar navbar-fixed-top" id="topbar">
		   <div class="navbar-inner">
		    <div class="container-fluid">
		      <a class="logo" href="<?php echo $_smarty_tpl->getVariable('conf')->value['rooturl_admin'];?>
" title="Go to Dashboard">Admin Panel</a>

		      <div class="btn-group pull-right">
		        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#" id="profiledropdown">
		          <?php echo $_smarty_tpl->getVariable('me')->value->fullname;?>

		          <span class="caret"></span>
		        </a>
		        <ul class="dropdown-menu">
		          <li><a href="<?php echo $_smarty_tpl->getVariable('conf')->value['rooturl_admin'];?>
profile"><i class="fa fa-user"></i> My Profile</a></li>
		          <li><a href="<?php echo $_smarty_tpl->getVariable('conf')->value['rooturl'];?>
site/logout?from=admin"><i class="fa fa-sign-out"></i> Sign Out</a></li>
		        </ul>
		      </div>

		    </div>
		  </div>
		</div>

		<div>
		  	<div class="row-fluid">
				<?php $_template = new Smarty_Internal_Template("_controller/admin/header_leftmenu.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
	        	<div id="container">
