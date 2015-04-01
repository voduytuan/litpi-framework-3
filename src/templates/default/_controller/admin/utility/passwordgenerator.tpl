<div class="header-row">
	<div class="header-row-wrapper">
		<header>
			<h1 class="header-main" id="page-header" rel="menu_codegenerator">
				<i class="fa fa-asterisk"></i>
				Password Generator
			</h1>
		</header>
	</div>
</div>

<form method="post" action="" class="form-inline">


	<div class="row section">
		<div class="col-md-3 section-summary">
			<h1>Generate Password</h1>
			<p>Using this form to generate password (using framework Hash algorithm)</p>
		</div>
		<div class="col-md-9 section-content">
			<div class="col-md-12 ssb clear">
				<div class="col-md-6 inner-left inner-left">
					<label for="fpassword">Password</label>
					<div class="input-group">
						<input type="text" name="fpassword" class="form-control" />
						<span class="input-group-btn">
							<input type="submit" name="fsubmit" value="Generate!" class="btn btn-success" />
						</span>
					</div>
				</div>
			</div>

			{if $encodedPass!= ''}
			<div class="col-md-12 clear">
				<div class="box success">
					Hashed Password of "{$smarty.post.fpassword}" is : <br /><br />
					<textarea readonly="readonly" style="width:90%; height:50px;">{$encodedPass}</textarea>
				</div>
			</div>
			{/if}
		</div>

	</div>
</form>






