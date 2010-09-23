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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<base href="{base_url}" />
<?php render_stylesheet(); ?>
<!-- <link rel="icon" href="static/images/icon.ico" /> -->
<script src="{base_url}/static/javascript/jquery.min.js" type="text/javascript" ></script>
<script src="{base_url}/static/javascript/jquery.qtip.js" type="text/javascript" ></script>
<script src="{base_url}/static/javascript/jquery.qtip.ajax.js" type="text/javascript" ></script>
<script src="{base_url}/static/javascript/jquery-ui.min.js" type="text/javascript" ></script>
<script src="{base_url}/static/javascript/helpers.js" type="text/javascript" ></script>
</head>
<body>

<div id="page">

<h1>SimianGrid<span></span></h1>

<div id="menu">
<li><a title='menu_users' href="{site_url}/user/"><Users><?php echo lang('sg_menu_users'); ?></a></li>
<li><a title='menu_regions' href="{site_url}/region"><?php echo lang('sg_menu_regions'); ?></a></li>
<?php if ($this->sg_auth->is_logged_in()): ?>
<li><a title='menu_account' href="{site_url}/user/self"><?php echo lang('sg_menu_account'); ?></a></li>
<?php endif; ?>
<?php if ($this->sg_auth->is_logged_in() && !strpos(uri_string(), 'logout')): ?>
<li><a title='menu_logout' href="{site_url}/auth/logout"><?php echo lang('sg_menu_logout'); ?></a></li>
<?php endif; ?>
<?php if ( ! $this->sg_auth->is_logged_in()): ?>
<li><a title='menu_login' href="{site_url}/auth"><?php echo lang('sg_login'); ?></a></li>
<li><a title='menu_register' href="{site_url}/auth/register"><?php echo lang('sg_register'); ?></a></li>
<?php endif; ?>
<li><a href="{site_url}/about"><?php echo lang('sg_menu_about'); ?></a></li>
</div>
<div id="border">
<div id="contents">
<div id="messages">
<?php messages_render(); ?>
</div>
