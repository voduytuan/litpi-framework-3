<?php /* Smarty version Smarty-3.0.7, created on 2015-03-31 06:39:27
         compiled from "/Library/WebServer/Documents/www/litpiproject/github/litpi-framework-3/src/templates/default/_controller/admin/login/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:4415106655519deafa41587-53302686%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c0e40d0e398eda3cbb13a694850fcea8380f452a' => 
    array (
      0 => '/Library/WebServer/Documents/www/litpiproject/github/litpi-framework-3/src/templates/default/_controller/admin/login/index.tpl',
      1 => 1418794208,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4415106655519deafa41587-53302686',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html>
<html>
	<head>
		<title>Authentication</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('currentTemplate')->value;?>
/bootstrap/css/bootstrap.min.css" type="text/css"/>
		<link rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('currentTemplate')->value;?>
/bootstrap/css/FortAwesome/css/font-awesome.css" type="text/css" />
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
			<h2>Administrator</h2>
			<h4>Please Input your account information to continue</h4>
			<form action="" autocomplete="off" method="post" class="validate form-horizontal" style="padding-top:20px;">





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

				<div class="form-group">
					<label for="fpassword" class="col-sm-3 control-label">Password</label>
					<div class="col-sm-9">
						<input type="password" id="fpassword" name="fpassword" class="form-control">

					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-9 col-sm-offset-3 input-group" style="padding-left:15px;">
						<div class="checkbox" style="line-height:1;">
						    <label><input type="checkbox" class="checkbox" value="1" name="frememberme" id="frememberme">Keep me logged in</label>
						</div>
					</div>
				</div>


			</div>

			<div class="submit">
				<a style="padding-top:10px;" href="<?php echo $_smarty_tpl->getVariable('conf')->value['rooturl_admin'];?>
forgotpass" class="pull-left">Forgot Password?</a>
				<input type="submit" name="fsubmit" class="btn btn-success pull-right" value="Login" />
			</div>





			</form>


		</div>
</body>
</html>







