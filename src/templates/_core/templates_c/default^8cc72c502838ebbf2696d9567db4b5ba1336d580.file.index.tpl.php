<?php /* Smarty version Smarty-3.0.7, created on 2015-03-31 06:48:57
         compiled from "/Library/WebServer/Documents/www/litpiproject/github/litpi-framework-3/src/templates/default/_controller/admin/forgotpass/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1596459285519e0e947ccb8-29630999%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8cc72c502838ebbf2696d9567db4b5ba1336d580' => 
    array (
      0 => '/Library/WebServer/Documents/www/litpiproject/github/litpi-framework-3/src/templates/default/_controller/admin/forgotpass/index.tpl',
      1 => 1427759278,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1596459285519e0e947ccb8-29630999',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $_smarty_tpl->getVariable('lang')->value['controller']['pageTitle'];?>
</title>
		<meta name="keywords" content="<?php echo $_smarty_tpl->getVariable('lang')->value['controller']['pageKeyword'];?>
" />
		<meta name="description" content="<?php echo $_smarty_tpl->getVariable('lang')->value['controller']['pageDescription'];?>
" />

		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('currentTemplate')->value;?>
bootstrap/css/bootstrap.min.css" type="text/css"/>
		<link rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('currentTemplate')->value;?>
bootstrap/css/FortAwesome/css/font-awesome.css" type="text/css" />
		<link type="text/css" rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('currentTemplate')->value;?>
/min/?g=cssadmin&ver=<?php echo $_smarty_tpl->getVariable('setting')->value['site']['cssversion'];?>
" />
		<script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('currentTemplate')->value;?>
js/jquery.js"></script>
		
		<style type="text/css">
			.login_body {
			  background: #3498db;
			}
			.login_body h2{font-size:24px;padding-bottom:7px;}
			.login_body h4{font-size:14px; font-weight: normal; color:#555;}
			.login_body .wrap {
				padding:20px;
			  width: 400px;
			  background: #fff;
			  position: absolute;
			  top: 50%;
			  left: 50%;
			  margin: -200px 0 0 -200px;
				-webkit-border-radius: 2px;
				       -moz-border-radius: 2px;
					        border-radius: 2px;
			}

			.login{padding:20px 20px 10px 20px; background:#fff; border-top:1px solid #eee; border-bottom:1px solid #eee; margin-bottom:20px;}


		</style>
		

		<script type="text/javascript">
			$(document).ready(function()
			{
				$('#femail').focus();
			});
		</script
	</head>


	<body class='login_body'>
		<div class="wrap">

		<a title="Go to main site" href="<?php echo $_smarty_tpl->getVariable('conf')->value['rooturl'];?>
" target="_blank" class="pull-right"><i class="fa fa-home"></i></a>
			<h2><?php echo $_smarty_tpl->getVariable('lang')->value['controller']['title'];?>
</h2>
			<h4>Please Input email of your account information to continue</h4>

			<form action="<?php echo $_smarty_tpl->getVariable('conf')->value['rooturl_admin'];?>
forgotpass?redirect=<?php echo $_smarty_tpl->getVariable('redirectUrlEncode')->value;?>
" method="post" class="validate form-horizontal" style="padding-top:20px;">
				<input type="hidden" name="ftoken" value="<?php echo $_smarty_tpl->getVariable('session')->value->get('forgotpassToken');?>
" />



				<div class="login">
					<?php $_template = new Smarty_Internal_Template("notify.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->assign('notifySuccess',$_smarty_tpl->getVariable('success')->value);$_template->assign('notifyError',$_smarty_tpl->getVariable('error')->value); echo $_template->getRenderedTemplate();?><?php unset($_template);?>

					<div class="form-group">
						<label for="femail" class="col-sm-3 control-label">Email</label>
						<div class="col-sm-9">
							<input type="text" id="femail" name="femail" value="<?php echo $_smarty_tpl->getVariable('formData')->value['femail'];?>
" class="form-control">
						</div>
					</div>



				</div>

				<div class="submit">
					<a style="padding-top:10px;" href="<?php echo $_smarty_tpl->getVariable('conf')->value['rooturl_admin'];?>
login" class="pull-left">&laquo; Back to Login</a>
					<input type="submit" name="fsubmit" class="btn btn-success pull-right" value="<?php echo $_smarty_tpl->getVariable('lang')->value['controller']['submitLabel'];?>
" />
				</div>


			</form>


		</div>
</body>
</html>




