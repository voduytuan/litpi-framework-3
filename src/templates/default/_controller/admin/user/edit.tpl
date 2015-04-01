<div class="header-row">
	<div class="header-row-wrapper">
		<header>
			<h1 class="header-main" id="page-header" rel="menu_user_list">
				<i class="fa fa-users"></i>
				<span class="breadcrumb"><a href="{$conf.rooturl_admin}{$controller}" title="">Users</a> /</span>
				Edit
			</h1>
			<div class="header-right pull-right">
				<a class="btn btn-default" href="{$conf.rooturl_admin}{$controller}">Cancel</a>
				<a class="btn btn-success" href="javascript:void(0)" onclick="$('#myform').submit();">{$lang.default.formUpdateSubmit}</a>
			</div>
		</header>
	</div>
</div>


<form action="" id="myform" method="post" name="myform" class="form-horizontal">
<input type="hidden" name="ftoken" value="{$session->get('userEditToken')}" />

	{include file="notify.tpl" notifyError=$error notifySuccess=$success notifyWarning=$warning}

	<div class="row section">
		<div class="col-md-3 section-summary">
			<h1>User Overview</h1>
			<p>General Information about User</p>
		</div>
		<div class="col-md-9 section-content">
			<div class="col-md-6 ssb clear">
				<label for="fgroupid">Group</label>
				<select id="fgroupid" name="fgroupid" class="col-md-12">
					<option value="">- - - -</option>
					{foreach item=groupname key=key from=$userGroups}
						<option value="{$key}" {if $formData.fgroupid == $key}selected="selected"{/if}>{$groupname}</option>
					{/foreach}
				</select>
			</div>

			<div class="col-md-6 ssb clear">
				<label for="femail">Email</label>
				<input type="text" name="femail" id="femail" disabled="disabled" value="{$formData.femail|@htmlspecialchars}" />
			</div>

			<div class="col-md-6 ssb clear">
				<label for="ffullname">Full Name</label>
				<input type="text" name="ffullname" id="ffullname" value="{$formData.ffullname|@htmlspecialchars}" />
			</div>

			<div class="col-md-6 ssb clear">
				<label for="fgender">Gender</label>
				<select id="fgender" name="fgender">
					<option value="">- - - -</option>
					<option value="1" {if $formData.fgender == '1'}selected="selected"{/if}>Male</option>
	                <option value="2" {if $formData.fgender == '2'}selected="selected"{/if}>Female</option>
				</select>
			</div>
		</div>
	</div>


	<div class="row section">
		<div class="col-md-3 section-summary">
			<h1>More Information</h1>
			<p>Information about contact, introduction</p>
		</div>
		<div class="col-md-9 section-content">
			<div class="col-md-6 ssb clear">
				<label for="fbirthday">Birthday</label>
				<input type="text" name="fbirthday" id="fbirthday" value="{$formData.fbirthday|@htmlspecialchars}" class="">
			</div>

			<div class="col-md-6 ssb clear">
				<label for="fphone">Phone</label>
				<input type="text" name="fphone" id="fphone" value="{$formData.fphone|@htmlspecialchars}" class="">
			</div>

			<div class="col-md-12 ssb clear">
				<div class="col-md-8 inner-left">
					<label for="faddress">Address</label>
					<input type="text" name="faddress" id="faddress" value="{$formData.faddress|@htmlspecialchars}">
				</div>
				<div class="col-md-4 inner-right">
					<label for="fregion">Region</label>
					<select  name="fregion" id="fregion" class="col-md-12">
						<option value="0">- - - Choose a Region - - -</option>
						{foreach item=region key=regionid from=$setting.region}
						<option {if $regionid == $formData.fregion}selected="selected" {/if} value="{$regionid}">{$region}</option>
						{/foreach}
					</select>
					<input type="hidden" name="fcountry" id="fcountry" size="2" value="{$formData.fcountry|@htmlspecialchars}">
				</div>
			</div>

			<div class="col-md-12 ssb clear">
				<label for="fwebsite">Website</label>
				<input type="text" name="fwebsite" id="fwebsite" value="{$formData.fwebsite|@htmlspecialchars}" />
			</div>

			<div class="col-md-12 clear">
				<label for="fbio">Bio</label>
				<div class="expanding-textarea expanded">
					<pre><span><br /></span></pre>
					<textarea name="fbio" row="6" cols="80" class="col-md-12">{$formData.fbio}</textarea>
				</div>
			</div>
		</div>
	</div>

	<div class="row section">
		<div class="col-md-3 section-summary">
			<h1>Date & Time</h1>
			<p>Date time tracking</p>
		</div>
		<div class="col-md-9 section-content">
			<div class="col-md-12 ssb clear">
				<label>Date Registered: <span class="note">{$myUser->datecreated|date_format:"%H:%M:%S - %A, %B %e, %Y"} (IP AddressL {$myUser->ipaddress})</span></label>
			</div>

			<div class="col-md-12 ssb clear">
				<label>Date Modified: <span class="note">{if $myUser->datemodified > 0}{$myUser->datemodified|date_format:"%H:%M:%S - %A, %B %e, %Y"}{else}n/a{/if}</span></label>
			</div>

			<div class="col-md-12 ssb clear">
				<label>Date Last Login : <span class="note">{if $myUser->datelastlogin > 0}{$myUser->datelastlogin|date_format:"%H:%M:%S - %A, %B %e, %Y"}{else}n/a{/if}</span></label>
			</div>
		</div>
	</div>



	{if $myUser->avatar != ''}
	<div class="control-group">
		<label class="control-label">Avatar</label>
		<div class="controls">
			<a href="{$conf.rooturl}{$setting.avatar.imageDirectory}{$myUser->avatar}" target="_blank"><img src="{$conf.rooturl}{$setting.avatar.imageDirectory}{$myUser->thumbImage()}" width="100" border="0" /></a><input type="checkbox" name="fdeleteimage" value="1" />Delete<br />
		</div>
	</div>
	{/if}


	<div class="row section">
		<div class="col-md-3 section-summary">
			<h1>Open Authentication</h1>
			<p>Login from other service via OAuth (Ex: facebook, google...)</p>
		</div>
		<div class="col-md-9 section-content">
			<div class="col-md-12">
				<div class="col-md-6 inner-left">
					<label for="foauthpartner">Service</label>
					<select name="foauthpartner" id="foauthpartner" class="col-md-12">
						<option value="0">- - Not used - -</option>
						<option value="1" {if $formData.foauthpartner == 1}selected="selected"{/if}>Facebook</option>
						<option value="2" {if $formData.foauthpartner == 2}selected="selected"{/if}>Yahoo</option>
						<option value="3" {if $formData.foauthpartner == 3}selected="selected"{/if}>Google</option>
					</select>
				</div>

				<div class="col-md-6 inner-right">
					<label for="foauthuid">OAuth ID</label>
					<input type="text" name="foauthuid"  id="foauthuid" value="{$formData.foauthuid}">
				</div>
			</div>
		</div>
	</div>

	<div class="row section buttons">
		<span class="pull-left"><span class="star_require">*</span> : {$lang.default.formRequiredLabel}</span>

		<input type="hidden" name="fsubmit" value="1" />
		<input type="submit" value="{$lang.default.formUpdateSubmit}" class="btn btn-large btn-success" />
		<a href="javascript:void(0)" id="resetpasswordbutton" onclick="resetpasswordHandler('{$conf.rooturl_admin}user/resetpass/id/{$myUser->id}')" class="btn btn-warning btn-pull-right">Reset Password</a>
	</div>

</form>

