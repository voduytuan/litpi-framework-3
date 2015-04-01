<div class="header-row">
	<div class="header-row-wrapper">
		<header>
			<h1 class="header-main" id="page-header" rel="menu_codegenerator">
				<i class="fa fa-magic"></i>
				<span class="breadcrumb"><a href="{$conf.rooturl_admin}{$controller}" title="">Code Generator</a> /</span>
				Classmap Generating
			</h1>
			<div class="header-right pull-right">
				<a class="btn btn-default" href="{$conf.rooturl_admin}{$controller}">Cancel</a>
			</div>
		</header>
	</div>
</div>

<div class="col-md-12">
	<p>Because Litpi follow PSR-0, so Classname of Controller must map the controller filename. So that, we need Classmap to mapping route url to correct controller file name. [<a href="https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md" target="_blank">Learn about PSR-0</a>]</p>
</div>

{include file="notify.tpl" notifyError=$error notifySuccess=$success notifyWarning=$warning}

<div class="col-md-12">


	<textarea class="textarea" rows="20" style="font-family: Courier New, mono-space; font-size:12px;">{$classmapFiledata}</textarea>

</div>