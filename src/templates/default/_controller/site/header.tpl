<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{if $pageTitle != ''}{$pageTitle} - {$setting.site.heading}{else}{$setting.site.defaultPageTitle}{/if}</title>
<meta name="keywords" content="{$pageKeyword|default:$setting.site.defaultPageKeyword|escapequote}" />
<meta name="description" content="{$pageDescription|default:$setting.site.defaultPageDescription|escapequote}" />
<link rel="shortcut icon" href="{$conf.rooturl}favicon.ico" type="image/x-icon">
<link rel="icon" href="{$conf.rooturl}favicon.ico" type="image/x-icon">

<link type="text/css" rel="stylesheet" href="{$currentTemplate}min/index.php?g=css&ver={$setting.site.cssversion}" media="screen" />
<script  type="text/javascript" src="{$currentTemplate}js/jquery.js"></script>
<script src="{$currentTemplate}min/index.php?g=js&ver={$setting.site.jsversion}"></script>

<script type="text/javascript">
		var rooturl = "{$conf.rooturl}";
		var imageDir = "{$imageDir}";
</script>
</head>

<body class="animated bounceInDown">