<!DOCTYPE html>
<html>
	<head>
		<title>{$lang.controller.pageTitle}</title>
		<meta name="keywords" content="{$lang.controller.pageKeyword}" />
		<meta name="description" content="{$lang.controller.pageDescription}" />

		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" href="{$currentTemplate}bootstrap/css/bootstrap.min.css" type="text/css"/>
		<link rel="stylesheet" href="{$currentTemplate}bootstrap/css/FortAwesome/css/font-awesome.css" type="text/css" />
		<link type="text/css" rel="stylesheet" href="{$currentTemplate}/min/?g=cssadmin&ver={$setting.site.cssversion}" />
		<script type="text/javascript" src="{$currentTemplate}js/jquery.js"></script>
		{literal}
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
		{/literal}

		<script type="text/javascript">
			$(document).ready(function()
			{
				$('#femail').focus();
			});
		</script
	</head>


	<body class='login_body'>
		<div class="wrap">

		<a title="Go to main site" href="{$conf.rooturl}" target="_blank" class="pull-right"><i class="fa fa-home"></i></a>
			<h2>Reset Password</h2>
			<h4>{$lang.controller.resetHelp}</h4>

			<form action="" method="post" class="validate form-horizontal" style="padding-top:20px;">
				<input type="hidden" name="ftoken" value="{$session->get('forgotpassToken')}" />



				<div class="login">

					{include file="notify.tpl" notifySuccess=$success notifyError=$error}

					<div class="form-group">
						<label for="fpassword" class="col-sm-6 control-label">{$lang.controller.password}</label>
						<div class="col-sm-6">
							<input type="password" id="fpassword" name="fpassword" class="form-control">
						</div>
					</div>

					<div class="form-group">
						<label for="fpassword2" class="col-sm-6 control-label">{$lang.controller.password2}</label>
						<div class="col-sm-6">
							<input type="password" id="fpassword2" name="fpassword2" class="form-control">
						</div>
					</div>




				</div>

				<div class="submit">
					<a style="padding-top:10px;" href="{$conf.rooturl_admin}login" class="pull-left">&laquo; Back to Login</a>
					<input type="submit" name="fsubmit" class="btn btn-success pull-right" value="{$lang.default.formUpdateSubmit}" />
				</div>


			</form>


		</div>
</body>
</html>




