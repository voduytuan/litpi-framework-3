<div class="header-row">
	<div class="header-row-wrapper">
		<header>
			<h1 class="header-main" id="page-header" rel="menu_codegenerator">
				<i class="fa fa-magic"></i>
				<span class="breadcrumb"><a href="{$conf.rooturl_admin}{$controller}" title="">Code Generator</a> /</span>
				{$table}
			</h1>
			<div class="header-right pull-right">
				<a class="btn btn-default" href="{$conf.rooturl_admin}{$controller}">Cancel</a>
				{if $tableNotFound == false}<a class="btn btn-success" href="javascript:void(0)" onclick="$('#myform').submit();">GENERATE NOW</a>{/if}
			</div>
		</header>
	</div>
</div>



<form action="" id="myform" method="post" name="myform" class="form-horizontal" style="padding:0 10px;">
<input type="hidden" name="ftoken" value="{$session->get('generatingToken')}" />


	{include file="notify.tpl" notifyError=$error notifySuccess=$success notifyWarning=$warning notifyInformation=$information}

	{if $tableNotFound == false}
	<legend>MODEL settings</legend>

	<div class="form-group">
		<label for="fmodulenamespace" class="col-md-2 control-label">Namespace</label>
		<span class="col-md-3">
			<input type="text" name="fmodulenamespace" id="fmodulenamespace" placeholder="Model" value="{$formData.fmodulenamespace|default:"Model"}" class="form-control" onkeyup="moduleHelpUpdate()">
		</span>
	</div>

	<div class="form-group">
		<label class="col-md-2 control-label" for="fmodule">Class</label>
		<div class="col-md-8">
			<span class="col-md-4 inner-left"><input type="text" name="fmodule" id="fmodule" value="{$formData.fmodule|@htmlspecialchars}" class="form-control" onkeyup="moduleHelpUpdate()"></span>
			<span class="col-md-1 inner-left"><code>Extends</code></span>
			<span class="col-md-4"><input type="text" name="fmodulebaseclass" id="fmodulebaseclass" value="{$formData.fmodulebaseclass|@htmlspecialchars|default:"BaseModel"}" class="form-control" /></span>
		</div>
	</div>

	<div class="form-group">
		<label class="col-md-2 control-label" for="fdboject">Database Object</label>
		<span class="col-md-1">
			<input type="text" name="fdbobject" id="fdbobject" value="db" placeholder="db" title="Default: db" class="form-control" />
		</span>
	</div>

	<div class="form-group">
		<label class="col-md-2 control-label" for="ftablealias">Table Alias</label>
		<div class="col-md-8">
			<div class="input-group col-md-6">
				<span class="input-group-addon">{$table}</span>
				<input type="text" name="ftablealias" id="ftablealias" value="{$formData.ftablealias|@htmlspecialchars}" class="form-control">
			</div>
		</div>
	</div>

	<div class="form-group">
		<label class="col-md-2 control-label">Mapping</label>
		<div class="col-md-10 pull-left">
			<table class="table table-hover" cellpadding="2" width="100%">
				{if $columnData|@count > 0}
					<thead>
						<tr>
							<th width="200">Column Name</th>
							<th width="120">Class Property</th>
							<th width="100"></th>
							<th width="100"></th>
							<th></th>
						</tr>
					</thead>

					<tbody>
				{foreach item=col from=$columnData}
					{assign var="colField" value=$col.Field}
						<tr>
							<td><b>{$col.Field}</b> <span class="label label-default">{$col.Type}</span> {if $col.Key == 'PRI'}<span class="label label-important">Primary</span>{elseif in_array($col.Field, $indexColumnData)}<span class="label label-info">Index</span>{/if}</td>
							<td><input type="hidden" name="ftype[{$col.Field}]" value="{$col.Type}" /><input type="text" name="fprop[{$col.Field}]" value="{$formData.fprop.$colField}" /></td>
							<td><label class="checkbox"><input type="checkbox" name="ffilterable[{$col.Field}]" value="1" {if in_array($col.Field, $indexColumnData)}checked="checked"{/if} {if $col.Key == 'PRI'}disabled="disabled"{/if} {if isset($formData.ffilterable.$colField)}checked="checked"{/if} /> Filterable</label></td>
							<td><label class="checkbox"><input type="checkbox" {if $col.Key != 'PRI'} name="fsortable[{$col.Field}]"{/if} value="1" {if $col.Key == 'PRI'}checked="checked" disabled="disabled"{/if} {if isset($formData.fsortable.$colField)}checked="checked"{/if} /> Sortable</label></td>
							<td>{if in_array($col.Field, $formData.textfield)}<label class="checkbox"> <input type="checkbox" name="fsearchabletext[{$col.Field}]" {if isset($formData.fsearchabletext.$colField)}checked="checked"{/if} value="1" /> Searchable Text</label>
								{elseif $formData.fprop.$colField == 'ipaddress'}
									<label class="checkbox"> <input type="checkbox" name="fipaddressable[{$col.Field}]" value="1" {if isset($formData.fipaddressable.$colField) || $formData.fsubmit == ''}checked="checked"{/if} />IP Address</label>
								{elseif $formData.fprop.$colField == 'displayorder'}
									<label title="Get the current MAX(displayorder) + 1, group by displayorder group" class="checkbox"> <input type="checkbox" name="fdisplayorderable[{$col.Field}]" value="1" {if isset($formData.fdisplayorderable.$colField) || $formData.fsubmit == ''}checked="checked"{/if} />Display Order</label>

									<select name="fdisplayordergroup[{$col.Field}]" class="input-sm">
										<option value="">- - Limit MAX Order in Column - -</option>
										{foreach item=col2 from=$columnData}
											<option value="{$col2.Field}" {if $formData.fdisplayordergroup.$colField == $col2.Field}selected="selected"{/if}>{$col2.Field}</option>
										{/foreach}
									</select>
								{elseif $col.Key != 'PRI' && $col.Type != 'float'}
									<input type="text" name="fconstantable[{$col.Field}]" value="{$formData.fconstantable.$colField}" placeholder="Constant Value" title="CONSTANT1:value1:text,CONSTANT2:value2:text2,..."  />
								{/if}
							</td>
						</tr>

				{/foreach}
					</tbody>
				{/if}


			</table>
		</div>
	</div>


	<div>
			<label class="checkbox"><input type="checkbox" {if $formData.fadmincontrollertoggler == 1}checked="checked"{/if} name="fadmincontrollertoggler" id="fadmincontrollertoggler" value="1" onchange="javascript:admincontrollertoggle()" /><span class="label label-warning">Enable generate manage CONTROLLER</span></label>
			<br />
	</div>



	<fieldset class="admincontrollergenerator" style="{if $formData.fadmincontrollertoggler != 1}display:none{/if}">
		<legend>CONTROLLER settings</legend>

		<div class="form-group">
			<label class="col-md-2 control-label" for="fcontrollericonclass">Font Awesome Icon Class</label>
			<span class="col-md-4 inner-left">
				<input type="text" name="fcontrollericonclass" id="fcontrollericonclass" value="{$formData.fcontrollericonclass}" value="" placeholder="fa-info-circle" class="inline form-control" />
				<a href="http://fontawesome.io/icons/" target="_blank" title="Click here to explore all available Font Awesome Icon Classes"> Find icon class..</a>
			</span>
		</div>

		<div class="form-group">
			<label class="col-md-2 control-label" for="fcontrollergroup">Namespace</label>
			<div class="col-md-4 input-group">
				<span class="input-group-addon"><code>Controller\</code></span>
				<input type="text" name="fcontrollernamespace" id="fcontrollernamespace" class="form-control" value="{$formData.fcontrollernamespace|default:"Admin"}" onkeyup="controllerHelpUpdate()"/>
			</div>
		</div>


		<div class="form-group">
			<label class="col-md-2 control-label" for="fcontrollerclass">Class Name</label>
			<div class="controls">
				<input type="text" name="fcontrollerclass" id="fcontrollerclass" value="{$formData.fcontrollerclass}" class="inline form-control" onkeyup="controllerHelpUpdate()"/>
				Extends <code>BaseController</code>
			</div>
		</div>

		<div class="form-group">
			<label class="col-md-2 control-label" for="fcontrollerrecordperpage">Record Per Page</label>
			<span class="col-md-4 inner-left">
				<input type="text" name="fcontrollerrecordperpage" id="fcontrollerrecordperpage" value="{$formData.fcontrollerrecordperpage|default:30}" value="" placeholder="Default: 30" class="inline form-control" />
			</span>
		</div>

		<div class="form-group">
			<label class="col-md-2 control-label" for="fclassextend">Mapping</label>
			<div class="col-md-10 pull-left">


				<table class="table table-hover">
					{if $columnData|@count > 0}
						<thead>
							<tr>
								<th width="200">Column Name</th>
								<th width="120">Label</th>
								<th width="200"></th>
								<th>Validating in Add/Edit</th>
							</tr>
						</thead>

						<tbody>
					{foreach item=col from=$columnData}
						{assign var="colField" value=$col.Field}
							<tr>
								<td><b>{$col.Field}</b> <span class="label label-default">{$col.Type}</span> {if $col.Key == 'PRI'}<span class="label label-danger">Primary</span> {/if}{if in_array($col.Field, $indexColumnData)}<span class="label label-info">Index</span>{/if}</td>
								<td><input type="text" class="input-small" name="flabel[{$col.Field}]" value="{$formData.flabel.$colField}" /></td>

								<td><label class="checkbox"><input type="checkbox" name="fexcludeindex[{$col.Field}]" {if isset($formData.fexcludeindex.$colField)}checked="checked"{/if} value="1" />Index Exclude</label></td>

								<td><label class="checkbox"><input type="checkbox"{if $col.Key == 'PRI' || $formData.fprop.$colField == 'datecreated' || $formData.fprop.$colField == 'datemodified'}disabled="disabled" checked="checked"{/if} name="fexclude[{$col.Field}]" {if isset($formData.fexclude.$colField)}checked="checked"{/if} value="1" />Add/Edit Exclude</label></td>
								<td>{if $col.Key != 'PRI'}
									<select name="fvalidating[{$col.Field}]" class="input-sm">
										<option value="notneed" {if $formData.fvalidating.$colField == 'notneed'}selected="selected"{/if}>Not Need</option>
										<option value="notempty" {if $formData.fvalidating.$colField == 'notempty'}selected="selected"{/if}>Not Empty String</option>
										<option value="greaterthanzero" {if $formData.fvalidating.$colField == 'greaterthanzero'}selected="selected"{/if}>Number greater than zero (0)</option>
										<option value="email" {if $formData.fvalidating.$colField == 'email'}selected="selected"{/if}>Email Address</option>
									</select>
									{/if}</td>
							</tr>

					{/foreach}
						</tbody>
					{/if}


				</table>

			</div>
		</div>
	</fieldset>



	<div class="row section buttons">
		<div class="pull-left">
			<input type="checkbox" name="foverwrite" id="foverwrite" {if $formData.foverwrite == 1}checked="checked"{/if} value="1" />
			<label class="inline" for="foverwrite">Overwrite Existed files</label>
		</div>
		<input type="hidden" name="fsubmit" value="1" />
		<input type="submit" value="GENERATE NOW" class="btn btn-success" />
	</div>

	{/if}


</form>

{if $tableNotFound == false}
<div class="box notice">
	<ul>
		<li><span class="help-block">Generated Model will be saved in directory <code>/<span id="modulenamespacePlaceholder"></span>/<span id="modulePlaceholder"></span>.php</code></span></li>

		<li class="admincontrollergenerator"><span class="help-block">Generated Controller will be saved in <code>/Controller/<span id="controllernamespacePlaceholder"></span>/<span id="controllerPlaceholder"></span>.php</code></span></li>

		<li class="admincontrollergenerator"><span class="help-block">Generated Controller Language will be saved in <code>/language/{$langCode}/<span id="controllerLangfilePlaceholder"></span>.xml</code></span></li>

		<li class="admincontrollergenerator"><span class="help-block">Generated Controller Template will be saved in <code>/templates/{$templateName}/_controller/<span id="controllernamespaceTplPlaceholder"></span>/<span id="controllerTplPlaceholder"></span>/*.tpl</code></span></li>
	</ul>
</div>
{/if}

{literal}
<script type="text/javascript">
	function admincontrollertoggle()
	{
		if ($('#fadmincontrollertoggler').is(':checked'))
		{
			$('.admincontrollergenerator').show();
		}
		else
		{
			$('.admincontrollergenerator').hide();
		}
	}

	//Init
	admincontrollertoggle();
	moduleHelpUpdate();
	controllerHelpUpdate();

	function moduleHelpUpdate()
	{
		$('#modulenamespacePlaceholder').text($('#fmodulenamespace').val().replace('\\', '/'));
		$('#modulePlaceholder').text($('#fmodule').val());

	}

	function controllerHelpUpdate()
	{
		$('#controllernamespacePlaceholder').text($('#fcontrollernamespace').val().replace('\\', '/'));
		$('#controllerPlaceholder').text($('#fcontrollerclass').val());
		$('#controllerLangfilePlaceholder').text($('#fcontrollerclass').val().toLowerCase());
		$('#controllernamespaceTplPlaceholder').text($('#fcontrollernamespace').val().toLowerCase());
		$('#controllerTplPlaceholder').text($('#fcontrollerclass').val().toLowerCase());

	}


</script>
{/literal}




