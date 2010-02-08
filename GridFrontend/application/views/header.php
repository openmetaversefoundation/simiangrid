<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SimianGrid</title>
<base href="{base_url}" />
<link rel="stylesheet" href="static/style.css" type="text/css" />
<!-- <link rel="icon" href="static/images/icon.ico" /> -->
</head>
<body>

<div id="menu">
 <ul>
  <li><a href="{site_url}"><span>Home</span></a></li>
  <?php if ($this->dx_auth->is_logged_in() && !strpos(uri_string(), 'logout')): ?>
  <li><a href="{site_url}/auth/change_password">Change Password</a></li>
  <li><a href="{site_url}/auth/logout"><span>Log Out</span></a></li>
  <?php else: ?>
  <li><a href="{site_url}/auth"><span>Login</span></a></li>
  <li><a href="{site_url}/auth/register"><span>Join Now</span></a></li>
  <?php endif; ?>
  <li><a href="{site_url}/about"><span>About SimianGrid</span></a></li>
  <li><a href="{site_url}/contact"><span>Contact Us</span></a></li>
 </ul>
</div>

<div id="logo"><h1>SimianGrid</h1></div>
