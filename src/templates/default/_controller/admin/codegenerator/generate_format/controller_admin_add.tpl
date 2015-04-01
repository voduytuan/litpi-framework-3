<div class="header-row">
	<div class="header-row-wrapper">
		<header>
			<h1 class="header-main" id="page-header" rel="menu_{{MODULE_LOWER}}">
				<i class="fa {{CONTROLLER_ICONCLASS}}"></i>
				<span class="breadcrumb"><a href="{$conf.rooturl_admin}{$controller}">{{MODULE}}</a> /</span>
				{$lang.controller.head_add}
			</h1>
			<div class="header-right pull-right">
				<a class="btn btn-default" href="{$conf.rooturl_admin}{$controller}">Cancel</a>
				<a class="btn btn-success" href="javascript:void(0)" onclick="$('#myform').submit();">{$lang.default.formAddSubmit}</a>
			</div>
		</header>
	</div>
</div>


<form action="" method="post" name="myform" class="form-horizontal" id="myform">
<input type="hidden" name="ftoken" value="{$session->get('{{MODULE_LOWER}}AddToken')}" />


	{include file="notify.tpl" notifyError=$error notifySuccess=$success}

	<div class="row section">
		<div class="col-md-3 section-summary">
			<h1>{{MODULE}} Overview</h1>
			<p>General Information about {{MODULE}}</p>
		</div>
		<div class="col-md-9 section-content">

			{{FORM_ADD_CONTROLGROUP}}
		</div>
	</div><!-- end .section -->

	<div class="row section buttons">
		<input type="hidden" name="fsubmit" value="1" />
		<input type="submit" value="{$lang.default.formAddSubmit}" class="btn btn-success" />
		<span class="pull-left"><span class="star_require">*</span> : {$lang.default.formRequiredLabel}</span>
	</div>

</form>


