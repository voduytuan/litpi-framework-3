<html>
	<head>
		<title>Installation :: Litpi Framework</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<script type="text/javascript" src="{$currentTemplate}js/jquery.js"></script>
		{literal}
		<style type="text/css">
			body{background:#777;font-family:Helvetica, Arial, Verdana, Sans serif;font-size:12px;}
			#wrapper{width:460px;margin: 100px auto; border-radius:8px; -webkit-border-radius:8px; background:#fff;padding:20px;}
			h1{text-align:center; font-size:28px;}
			.intro{font-size:14px; color:#7A7D7D; line-height:18px; text-align:center;}
			.btnwrapper{text-align:center;margin:50px;}
			.installbtn{border-width:0; cursor:pointer;padding:10px 30px; font-size:14px; border-radius:4px; -webkit-border-radius:4px; color:#fff; font-weight:bold;; background:#ccc; text-decoration:none;background: #4096ee;}
			.installbtn:hover{color:#000;}
			#footer{text-align:center; border-top:1px dotted #ccc;margin-top:30px;padding-top:10px; color:#ccc; font-size:11px;}
			#footer a{color:#09f;}
			#footer a:hover{color:#f90;}

			#installform{background:#f5f5f5; border-radius:4px; -webkit-border-radius:4px;width:400px; padding:20px; margin:20px auto; border:1px solid #eee;}
			h2{margin:0;padding-bottom:30px;text-align:center;}
			.fitem{clear:both; font-size:14px;padding-top:5px;}
			.fitem .label{float:left; width:130px; text-align:right;padding:8px 10px 0 0;}
			.fitem .input{float:left;}
			.fitem .tbx{padding:6px; font-size:16px;border:1px solid #ccc;width:240px;}


			/*	NOTIFY BAR	*/
			.notify-bar{padding:10px;border-radius: 8px; -moz-border-radius: 8px; -webkit-border-radius: 8px;margin-bottom:10px;}
			.notify-bar-success{background:#eaffa5;color:#6c8c00;}
			.notify-bar-error{background:#ffcfce;color:#9e3737;}
			.notify-bar-text{padding:0 20px 0 10px; line-height:1.5;}
			.notify-bar-text-sep{border-top:1px solid #eee;margin:10px 0; display:none;}
		</style>
		{/literal}
	</head>
	<body>
		<div id="wrapper">
			<h1>INSTALLATION</h1>
			<p class="intro">There is no user table &amp; no administrator account. <br />Press Install button to start creating tables &amp; first account.</p>


			<table width="60%" style="margin:auto; font-size:12px; background:#eee;" cellpadding="5">
				<tr><td colspan="2"><p style="text-align:center;">Current Database Config (<code>/includes/conf.php</code>)</p></td></tr>
				<tr><td>Database Server: </td><td><code>{$conf.db.host}</code></td></tr>
				<tr><td>Database Username: </td><td><code>{$conf.db.user}</code></td></tr>
				<tr><td>Database Password: </td><td><code>{$conf.db.pass}</code></td></tr>
				<tr><td>Database Name: </td><td><code>{$conf.db.name}</code></td></tr>
			</table>

			{if $formData.fsubmit == ''}
			<div class="btnwrapper"><a href="javascript:void(0)" onclick="$('#installform').toggle();$(this).hide();" class="installbtn" title="Click here to start installation">START!</a></div>
			{/if}

			<form action="" method="post">
			<div id="installform" {if $formData.fsubmit == ''}style="display:none;"{/if}>
				{if $error|@count > 0}
					<div class="notify-bar notify-bar-error">
						<div class="notify-bar-text">
							{foreach item=notifyErrorItem from=$error name="notifyerror"}
								<p>{$notifyErrorItem}</p>
								{if !$smarty.foreach.notifyerror.last}<div class="notify-bar-text-sep"></div>{/if}
							{/foreach}
						</div>
					</div>
				{/if}

				{if $success|@count > 0}
					<div class="notify-bar notify-bar-success">
						<div class="notify-bar-text">
							<p>{$success[0]}</p>
						</div>
					</div>

					<div class="notify-bar notify-bar-warning">
						<div class="notify-bar-text">
							<p>For Security, please REMOVE Controller Install (/Controller/Site/Install.php)</p>
						</div>
					</div>

					<div class="btnwrapper"><a href="{$conf.rooturl_admin}login" class="installbtn" title="Login to Dashboard">Login to Administrator Panel</a></div>
				{else}
					<div class="fitem">
						<div class="label">Fullname</div>
						<div class="input"><input type="text" class="tbx" name="ffullname" value="{$formData.ffullname}" /></div>
					</div>
					<div class="fitem">
						<div class="label">Email</div>
						<div class="input"><input type="text" class="tbx" name="femail" value="{$formData.femail}" /></div>
					</div>
					<div class="fitem">
						<div class="label">Password</div>
						<div class="input"><input type="password" class="tbx" name="fpassword" value="" /></div>
					</div>
					<div class="fitem">
						<div class="label">Retype Password</div>
						<div class="input"><input type="password" class="tbx" name="fpassword2" value="" /></div>
					</div>
					<div class="fitem">
						<div class="label">&nbsp;</div>
						<div class="input"><input type="submit" name="fsubmit" value="INSTALL" class="installbtn" /></div>
					</div>
				{/if}

				<div style="clear:both;"></div>
			</div><!-- end #installform -->
			</form>
			<div id="footer">
				Copyright 2015 &copy; <a href="https://github.com/voduytuan/litpi-framework-3" title="Go to litpi Github page" target="_blank">https://github.com/voduytuan/litpi-framework-3</a>. All rights reserved.
			</div>
		</div><!-- end #wrapper -->
	</body>
</html>