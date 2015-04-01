<div id="navbar">
    <div class="well sidebar-nav">
		<h2>ADMINISTRATOR</h2>
        <ul class="nav nav-list" id="administrator" {if $request->cookies->get('navActive') == 'administrator'}style="overflow: hidden; display: block;"{/if}>
			<li id="menu_codegenerator"><a class="sidebar-link" href="{$conf.rooturl_admin}codegenerator"><i class="fa fa-magic"></i> Code Generator</a></li>
			<li id="menu_user_list"><a class="sidebar-link" href="{$conf.rooturl_admin}user"><i class="fa fa-users"></i> Users</a></li>
        </ul>

    </div><!--/.well -->
</div><!--/span-->
