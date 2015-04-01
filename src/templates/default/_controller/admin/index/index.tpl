<div class="header-row">
	<div class="header-row-wrapper">
		<header>
			<h1 class="header-main">
				<i class="fa fa-dashboard"></i>
				Dashboard
			</h1>
		</header>
	</div>
</div>


<div class="col-md-12 ">
<table class="table table-striped">
	<thead>
		<tr>
			<th colspan="2"><h1>System Information</h1></th>
		</tr>
	</thead>
	<tr>
		<td width="200" class="td_right">Server IP :</td>
		<td>{$formData.fserverip}</td>
	</tr>
	<tr>
		<td class="td_right">Server Name :</td>
		<td>{$formData.fserver}</td>
	</tr>
	<tr>
		<td class="td_right">PHP Version :</td>
		<td>{$formData.fphp}</td>
	</tr>

	<tr>
		<td class="td_right">Server Time :</td>
		<td>{$smarty.now|date_format:$lang.default.dateFormatTimeSmarty}</td>
	</tr>
</table>
</div>