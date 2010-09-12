<?php if ( ! isset($this->simple_page) || ! $this->simple_page ):?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>
<?php
	$title = get_site_title();
	if ( isset($this->title) ) {
		$title = "$title - " . $this->title;
	}
	echo $title;
?>
</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<base href="{base_url}" />
<?php render_stylesheet(); ?>
<link rel="stylesheet" href="static/jquery-ui.css" type="text/css" />
<!-- <link rel="icon" href="static/images/icon.ico" /> -->
<script src="static/javascript/jquery.min.js" type="text/javascript" ></script>
<script src="static/javascript/jquery-ui.min.js" type="text/javascript" ></script>
<script src="static/javascript/helpers.js" type="text/javascript" ></script>
</head>
<body>

<div id="page">

<h1><span>SimianGrid</span></h1>

<menu>
<li><a href="{site_url}/user/"><Users><?php echo lang('sg_menu_users'); ?></a></li>
<li><a href="{site_url}/region"><?php echo lang('sg_menu_regions'); ?></a></li>
<?php if ($this->sg_auth->is_logged_in()): ?>
<li><a href="{site_url}/user/self"><?php echo lang('sg_menu_account'); ?></a></li>
<?php endif; ?>
<?php if ($this->sg_auth->is_logged_in() && !strpos(uri_string(), 'logout')): ?>
<li><a href="{site_url}/auth/logout"><?php echo lang('sg_menu_logout'); ?></a></li>
<?php endif; ?>
<?php if ( ! $this->sg_auth->is_logged_in()): ?>
<li><a href="{site_url}/auth"><?php echo lang('sg_login'); ?></a></li>
<li><a href="{site_url}/auth/register"><?php echo lang('sg_register'); ?></a></li>
<?php endif; ?>
<li><a href="{site_url}/about"><?php echo lang('sg_menu_about'); ?></a></li>
</menu>
<div id="border">
<div id="contents">
<?php endif; ?>
<div id="messages">
<?php messages_render(); ?>
</div>