<?php /* Smarty version Smarty-3.0.7, created on 2015-03-31 06:43:00
         compiled from "/Library/WebServer/Documents/www/litpiproject/github/litpi-framework-3/src/templates/default/_controller/admin/utility/passwordgenerator.tpl" */ ?>
<?php /*%%SmartyHeaderCode:16111778895519df84d4bba2-74275977%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '355038b2722e3041adf926bbff14f4feedf45f8c' => 
    array (
      0 => '/Library/WebServer/Documents/www/litpiproject/github/litpi-framework-3/src/templates/default/_controller/admin/utility/passwordgenerator.tpl',
      1 => 1418794208,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16111778895519df84d4bba2-74275977',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
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

			<?php if ($_smarty_tpl->getVariable('encodedPass')->value!=''){?>
			<div class="col-md-12 clear">
				<div class="box success">
					Hashed Password of "<?php echo $_POST['fpassword'];?>
" is : <br /><br />
					<textarea readonly="readonly" style="width:90%; height:50px;"><?php echo $_smarty_tpl->getVariable('encodedPass')->value;?>
</textarea>
				</div>
			</div>
			<?php }?>
		</div>

	</div>
</form>






