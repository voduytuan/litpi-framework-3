<?php /* Smarty version Smarty-3.0.7, created on 2015-03-31 06:27:00
         compiled from "/Library/WebServer/Documents/www/litpiproject/github/litpi-framework-3/src/templates/default/_controller/site/index/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:16793777595519dbc4abeb76-87345102%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8bdbe40bae65b32033de001f3c93c4885a55fb86' => 
    array (
      0 => '/Library/WebServer/Documents/www/litpiproject/github/litpi-framework-3/src/templates/default/_controller/site/index/index.tpl',
      1 => 1427758017,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16793777595519dbc4abeb76-87345102',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<div class="avatar">
    <img src="<?php echo $_smarty_tpl->getVariable('imageDir')->value;?>
site/sample/avatar.png" alt="Avatar">
</div>

<div class="content">
    <h1 class="title">Geeky<br>Litpiman 3</h1>

    <p>
        You see me, it's ok<br>
        because this is default &amp; sample controller<br />
        (\Controller\Site\Index)

        <br /><a id="installbutton" href="<?php echo $_smarty_tpl->getVariable('conf')->value['rooturl'];?>
site/install">START \Controller\Index\Install &raquo;</a>
    </p>

    <ul class="social-icons">
        <li><a class="twitter" href="https://twitter.com/lonelywolfvn" title="Twitter">Twitter</a></li>
        <li><a class="facebook" href="https://www.facebook.com/voduytuan" title="Facebook">Facebook</a></li>
        <li><a class="googleplus" href="https://plus.google.com/105706813154750960885" title="Google+">Google+</a></li>
        <li><a class="dribbble" href="http://dribbble.com/voduytuan" title="Dribbble">Dribbble</a></li>
    </ul>

    <p>
        Founder of SOMETHING SPECIAL<br>
        <a href="https://teamcrop.com">teamcrop.com</a><br>
        <a href="http://tienboi.com">tienboi.com</a><br>
        <a href="http://21ngay.com">21ngay.com</a><br>
        <a href="http://spiral.vn">spiral.vn</a><br>
        <a href="http://www.github.com/voduytuan/litpi-framework-3">View on Github</a>
    </p>

    <p>Want to send me a love letter? <a href="mailto:tuanmaster2012@gmail.com">Email me</a>.</p>
</div>