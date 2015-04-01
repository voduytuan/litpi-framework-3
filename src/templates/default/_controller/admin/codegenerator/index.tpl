<div class="header-row">
	<div class="header-row-wrapper">
		<header>
			<h1 class="header-main" id="page-header" rel="menu_codegenerator">
				<i class="fa fa-magic"></i>
				Code Generator
			</h1>
			<div class="header-right pull-right">
				<a class="btn btn-default" href="{$conf.rooturl_admin}utility/passwordgenerator"><i class="fa fa-asterisk"></i> Generate Password</a>
				<a class="btn btn-default" href="{$conf.rooturl_admin}{$controller}/classmap"><i class="fa fa-folder"></i> Generate Classmap</a>
			</div>
		</header>
	</div>
</div>

<div class="col-md-12">
	<table class="table table-hover" cellpadding="5" width="100%">
		<thead>
			<tr>
				<th>Select a Table to generate</th>
			</tr>
		</thead>

		{if $tables|@count > 0}
			<tbody>
			{foreach item=table from=$tables}
				<tr>
					<td><a title="Code generate for {$table}" href="{$conf.rooturl_admin}codegenerator/generate/table/{$table}/redirect/{$redirectUrl}" class="btn"><i class="icon-circle-arrow-right icon-white"></i> {$table}</a></td>
				</tr>
			{/foreach}
			</tbody>
		{else}
			<tbody>
				<tr>
					<td>There is no table in current database to generate Model.</td>
				</tr>

			</tbody>
		{/if}


	</table>
</div>







