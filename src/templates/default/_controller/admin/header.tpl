<!DOCTYPE html>
<html lang="en">
  <head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

		<title>{$pageTitle}</title>

		<!-- Bootstrap Stylesheet -->
		<link rel="stylesheet" href="{$currentTemplate}bootstrap/css/bootstrap.min.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="{$currentTemplate}bootstrap/css/FortAwesome/css/font-awesome.css" type="text/css" media="screen" />

		<!-- Customized Admin Stylesheet -->
		<link type="text/css" rel="stylesheet" href="{$currentTemplate}min/index.php?g=cssadmin&ver={$setting.site.cssversion}" media="screen" />

		<!-- jQuery -->
		<script type="text/javascript" src="{$currentTemplate}js/admin/jquery.js"></script>

		<!-- jQuery History Plugin -->
		<script type="text/javascript" src="{$currentTemplate}js/admin/jquery.history.js"></script>

		<!-- Bootstrap Js -->
		<script type="text/javascript" src="{$currentTemplate}bootstrap/js/bootstrap.min.js"></script>

		<script type="text/javascript" src="{$currentTemplate}js/admin/bootstrap-paginator.js"></script>


		<!-- customized admin -->
		<script src="{$currentTemplate}min/index.php?g=jsadmin&ver={$setting.site.jsversion}"></script>


        <script type="text/javascript">
		var rooturl = "{$conf.rooturl}";
		var rooturl_admin = "{$conf.rooturl_admin}";
		var module = "{$module}";
		var currentTemplate = "{$currentTemplate}";

		var delConfirm = "Are You Sure?";
		var delPromptYes = "Type YES to continue";

		var imageDir = "{$imageDir}";
		</script>

	</head>

    <body>


		<div class="navbar navbar-fixed-top" id="topbar">
		   <div class="navbar-inner">
		    <div class="container-fluid">
		      <a class="logo" href="{$conf.rooturl_admin}" title="Go to Dashboard">Admin Panel</a>

		      <div class="btn-group pull-right">
		        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#" id="profiledropdown">
		          {$me->fullname}
		          <span class="caret"></span>
		        </a>
		        <ul class="dropdown-menu">
		          <li><a href="{$conf.rooturl_admin}profile"><i class="fa fa-user"></i> My Profile</a></li>
		          <li><a href="{$conf.rooturl}site/logout?from=admin"><i class="fa fa-sign-out"></i> Sign Out</a></li>
		        </ul>
		      </div>

		    </div>
		  </div>
		</div>

		<div>
		  	<div class="row-fluid">
				{include file="_controller/admin/header_leftmenu.tpl"}
	        	<div id="container">
