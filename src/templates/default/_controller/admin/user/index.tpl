<div class="header-row">
	<div class="header-row-wrapper">
		<header>
			<h1 class="header-main" id="page-header" rel="menu_user_list">
				<i class="fa fa-users"></i>
				{if $formData.search != ''}
					<span class="breadcrumb"><a href="{$conf.rooturl_admin}{$controller}" title="{$lang.default.formViewAll}">Users</a> / </span>
					Search result (<span class="delete-decrease-count">{$total|number_format}</span>)
				{else}
					Users (<span class="delete-decrease-count">{$total|number_format}</span>)
				{/if}
			</h1>
			<div class="header-right">
				<a class="btn btn-success pull-right" href="{$conf.rooturl_admin}{$controller}/add">{$lang.controller.head_add}</a>
				<div class="formfilterpaginatorwrapper pull-right"></div>
			</div>
		</header>
	</div>
</div>


<div class="col-md-12 filter-form">
	<div class="input-group">
      <div class="input-group-btn">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Filter Users <span class="caret"></span></button>
        <div class="dropdown-menu dropdown-filter-box">
        	<h3>Show all Users where:</h3>
        	<select id="ffilter" class="input-sm" onchange="formfilterToggle()">
        		<option value="">Select a filter..</option>
        		<option value="email">{$lang.controller.labelEmail}</option>
				<option value="groupid">{$lang.controller.labelGroup}</option>
				<option value="region">{$lang.controller.labelRegion}</option>
				<option value="gender">{$lang.controller.labelGender}</option>
				<option value="id">{$lang.controller.labelId}</option>

        	</select>

        	<input type="text" id="filter_email_more" value="{$formData.femail}" class="filter_more hideinit input-sm" />
			<select id="filter_groupid_more" class="filter_more hideinit input-sm">
                        {html_options options=$userGroups selected=$formData.fgroupid}
                    </select>

			<select  name="fregion" id="filter_region_more" class="filter_more hideinit input-sm">
				{foreach item=region key=regionid from=$setting.region}
				<option {if $regionid == $formData.fregion}selected="selected" {/if} value="{$regionid}">{$region}</option>
				{/foreach}

			</select>


			<select id="filter_gender_more" class="filter_more hideinit input-sm">
					<option value="1" {if $formData.fgender == '1'}selected="selected"{/if}>Male</option>
	                <option value="2" {if $formData.fgender == '2'}selected="selected"{/if}>Female</option>
	        </select>

			<input type="text" id="filter_id_more" value="{$formData.fid}" class="filter_more hideinit input-sm" />


        	&nbsp;
        	<input type="button" onclick="formfilterAdd()" class="btn btn-sm btn-info inline" value="Add Filter" />
        </div>
      </div><!-- /btn-group -->
      <input type="text" id="fkeywordfilter" class="form-control filter-indicator" value="{$formData.keyword}" placeholder="Start typing to search in {$lang.controller.labelEmail}, {$lang.controller.labelFullname}..." />
    </div><!-- /input-group -->
</div>

<div class="filter-tags clear">
	<ul class="col-md-12 active-filters horizontal">
		{if $formData.filtertaglist|@count > 0}
			{foreach item=filtertag from=$formData.filtertaglist}
				<li class="tag closable filtertag" data-filtername="{$filtertag.name}" data-filtervalue="{$filtertag.value}" id="filtertag_{$filtertag.name}"><span><em>{$filtertag.namelabel} is equal to <b>{$filtertag.value}</b></em><span class="close" onclick="$('#filtertag_{$filtertag.name}').remove();$('#page').val('1');formfilterRefresh();"><i class="fa fa-times"></i></span></span></li>
			{/foreach}
		{/if}
	</ul>
</div>

<div class="col-md-12">
	{include file="notify.tpl" notifyError=$error notifySuccess=$success}

	<form action="" method="post" name="manage" class="form-horizontal formfilter-enable">
		<input type="hidden" id="pageurl" value="{$conf.rooturl}{$module}/{$controller}" />
		<input type="hidden" id="filterurl" value="{$conf.rooturl}{$module}/{$controller}/jsondata" />
		<input type="hidden" id="token" value="" />
		<input type="hidden" id="page" value="{$formData.page|default:1}" />
		<input type="hidden" id="sortby" value="{$formData.sortby}" />
		<input type="hidden" id="sorttype" value="{$formData.sorttype}" />

		<table class="table table-hover hideinit">
			<thead>
				<tr>
				   <th width="40"><input class="check-all" type="checkbox" /></th>

					<th class="formfilterth is-sortable" id="id">{$lang.controller.labelId}</th>
					<th class="formfilterth" id="fullname">{$lang.controller.labelFullname}</th>
					<th class="formfilterth" id="email">{$lang.controller.labelEmail}</th>
					<th class="formfilterth" id="groupid">{$lang.controller.labelGroupid}</th>
					<th class="formfilterth" id="gender">{$lang.controller.labelGender}</th>
					<th class="formfilterth" id="region">{$lang.controller.labelRegion}</th>
					<th class="formfilterth is-sortable" id="datelastaction"><a data-sortby="datelastaction"><span>{$lang.controller.labelDatelastaction}</span></a></th>
					<th class="formfilterth" id="datecreated">{$lang.controller.labelDatecreated}</th>
					<th width="100"></th>
				</tr>
			</thead>

			<tfoot>
				<tr>
					<td colspan="10">
						<div class="bulk-actions align-left hideinit">
							<input type="hidden" id="bulkActionInvalidWarn" value="{$lang.default.bulkActionInvalidWarn}" />
							<input type="hidden" id="bulkItemNoSelected" value="{$lang.default.bulkItemNoSelected}" />
							<select name="fbulkaction" id="fbulkaction">
								<option value="">{$lang.default.bulkActionSelectLabel}</option>
								<option value="delete">{$lang.default.bulkActionDeletetLabel}</option>
							</select>
							<input type="button" name="fsubmitbulk" id="fsubmitbulk" class="inline btn btn-sm btn-primary" value="{$lang.default.bulkActionSubmit}" onclick="formbulksubmit('{$conf.rooturl}{$module}/{$controller}/bulkapply')" />
						</div>
					</td>
				</tr>
			</tfoot>
			<tbody>

			</tbody>
		</table>
	</form>

	<div class="formfilter-empty hideinit">
		<div><i class="fa fa-users"></i></div>
		<p>{$lang.controller.errNotFound}</p>
	</div>

</div>



