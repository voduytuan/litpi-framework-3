<div class="header-row">
	<div class="header-row-wrapper">
		<header>
			<h1 class="header-main" id="page-header" rel="menu_user_list">
				<i class="fa fa-users"></i>
				<span class="breadcrumb"><a href="{$conf.rooturl_admin}{$controller}" title="">Users</a> /</span>
				Add new User
			</h1>
			<div class="header-right pull-right">
				<a class="btn btn-default" href="{$conf.rooturl_admin}{$controller}">Cancel</a>
				<a class="btn btn-success" href="javascript:void(0)" onclick="$('#myform').submit();">Save</a>
			</div>
		</header>
	</div>
</div>


<form action="" id="myform" method="post" name="myform" class="form-horizontal">
<input type="hidden" name="ftoken" value="{$session->get('userAddToken')}" />

	{include file="notify.tpl" notifyError=$error notifySuccess=$success notifyWarning=$warning}


	<div class="row section">
		<div class="col-md-3 section-summary">
			<h1>User Overview</h1>
			<p>General Information about User</p>
		</div>
		<div class="col-md-9 section-content">
			<div class="col-md-6 ssb clear inner-left">
				<label for="fgroupid">Account Group <span class="star_require">*</span></label>
				<select id="fgroupid" name="fgroupid" class="col-md-12">
					<option value="">- - - -</option>
					{foreach item=groupname key=key from=$userGroups}
							<option value="{$key}" {if $formData.fgroupid == $key}selected="selected"{/if}>{$groupname}</option>
					{/foreach}
				</select>
			</div>

			<div class="col-md-6 ssb clear inner-left">
				<label for="ffullname">Full Name <span class="star_require">*</span></label>
				<input type="text" name="ffullname" id="ffullname" value="{$formData.ffullname|@htmlspecialchars}" class="">
			</div>

			<div class="col-md-6 ssb clear inner-left">
				<label for="femail">Email <span class="star_require">*</span></label>
				<input type="text" name="femail" id="femail" value="{$formData.femail|@htmlspecialchars}" class="">
			</div>

			<div class="ssb clear">
				<div class="col-md-6 inner-left">
					<label for="fpassword">Password <span class="star_require">*</span></label>
					<input type="password" name="fpassword" id="fpassword" />
				</div>
				<div class="col-md-6 inner-right">
					<label for="fpassword2">Retype Password <span class="star_require">*</span></label>
					<input type="password" name="fpassword2" id="fpassword2" />
				</div>
			</div>

		</div>
	</div><!-- end .section -->

	<div class="row section buttons">
		<input type="hidden" name="fsubmit" value="1" />
		<input type="submit" value="{$lang.default.formAddSubmit}" class="btn btn-success" />
		<span class="pull-left"><span class="star_require">*</span> : {$lang.default.formRequiredLabel}</span>
	</div>

</form>

