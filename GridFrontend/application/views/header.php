<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>
<?php
	$real_title = get_site_title();
	if ( isset($title) ) {
		$real_title = "$real_title - " . $title;
	}
	echo $real_title;
?>
</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<base href="{base_url}" />
<?php render_stylesheet(); ?>
<link rel="icon" href="{base_url}/static/images/favicon.ico"/>
<script src="{base_url}/static/javascript/jquery.min.js" type="text/javascript" ></script>
<script src="{base_url}/static/javascript/jquery.qtip.js" type="text/javascript" ></script>
<script src="{base_url}/static/javascript/jquery.qtip.ajax.js" type="text/javascript" ></script>
<script src="{base_url}/static/javascript/jquery-ui.min.js" type="text/javascript" ></script>
<script src="{base_url}/static/javascript/jquery.dataTables.js" type="text/javascript" ></script>
<script src="{base_url}/static/javascript/helpers.js" type="text/javascript" ></script>
</head>
<body>

<div id="page">

<div id="border" class="ui-corner-bottom">

<div id="header" class="ui-corner-top">
	<div id="logo"></div>
	<div id="menu" class="ui-widget-header ui-corner-all ui-tabs-nav"><ul>
	<li><a id="menu_users" class="ui-state-default ui-corner-all ui-widget" title='menu_users' href="{site_url}/user/"><Users><?php echo lang('sg_menu_users'); ?></a></li>
	<li><a id="menu_regions" class="ui-state-default ui-corner-all ui-widget"  title='menu_regions' href="{site_url}/region"><?php echo lang('sg_menu_regions'); ?></a></li>
	<?php if ($this->sg_auth->is_logged_in()): ?>
	<li><a id="menu_account" class="ui-state-default ui-corner-all ui-widget"  title='menu_account' href="{site_url}/user/self"><?php echo lang('sg_menu_account'); ?></a></li>
	<?php endif; ?>
	<?php if ($this->sg_auth->is_logged_in() && !strpos(uri_string(), 'logout')): ?>
	<li><a id="menu_logout" class="ui-state-default ui-corner-all ui-widget"  title='menu_logout' href="{site_url}/auth/logout"><?php echo lang('sg_menu_logout'); ?></a></li>
	<?php endif; ?>
	<?php if ( ! $this->sg_auth->is_logged_in()): ?>
	<li><a id="menu_login" class="ui-state-default ui-corner-all ui-widget"  title='menu_login' href="{site_url}/auth"><?php echo lang('sg_login'); ?></a></li>
	<li><a id="menu_join" class="ui-state-default ui-corner-all ui-widget"  title='menu_register' href="{site_url}/auth/register"><?php echo lang('sg_register'); ?></a></li>
	<?php endif; ?>
	<li><a id="menu_about" class="ui-state-default ui-corner-all ui-widget"  href="{site_url}/about"><?php echo lang('sg_menu_about'); ?></a></li>
	</ul></div>
</div>

<script type="text/javascript">
	function select_menu(selector)
	{
		$(selector).addClass("ui-state-active");
	}
	$().ready(function() {
		$("#menu li").hover(
			function(){ 
				$(this).addClass("ui-state-hover"); 
			},
			function(){ 
				$(this).removeClass("ui-state-hover"); 
			}
		);
		var current_page = "{page}";
		if ( current_page == 'users' ) {
			select_menu('#menu_users');
		} else if ( current_page == 'regions' ) {
			select_menu('#menu_regions');
		} else if ( current_page == 'about' ) {
			select_menu('#menu_about');
		} else if ( current_page == 'login' ) {
			select_menu('#menu_login');
		} else if ( current_page == 'join' ) {
			select_menu('#menu_join');
		} else if ( current_page == 'account' ) {
			select_menu('#menu_account');
		}
	});
</script>
<div id="contents">
<div id="messages">
<?php messages_render(); ?>
</div>
