<?php
$username = array(
	'name'	=> 'username',
	'id'	=> 'username',
	'size'	=> 30,
	'value' => set_value('username')
);

$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'size'	=> 30
);

$remember = array(
	'name'	=> 'remember',
	'id'	=> 'remember',
	'value'	=> 1,
	'checked' => set_value('remember'),
	'style' => 'margin:0;padding:0'
);

$confirmation_code = array(
	'name'	=> 'captcha',
	'id'	=> 'captcha',
	'maxlength'	=> 8
);

$openid_identifier = array(
    'name'  => 'openid_identifier',
    'id'    => 'openid_identifier',
    'size'  => 40
);
?>

<h2><?php echo lang('sg_login'); ?></h2>

<?php generate_facebook_auth("$site_url/auth/login_facebook"); ?>

<?php openid_identifier_render(lang('sg_auth_openid_login'), "$site_url/auth/login_openid", "Login"); ?>

<fieldset><legend><?php lang('sg_login')?></legend>
<?php echo form_open($this->uri->uri_string())?>

<dl>
	<dt><?php echo form_label(lang('sg_name'), $username['id']);?></dt>
	<dd>
		<?php echo form_input($username)?>
    	<?php echo form_error($username['name']); ?>
	</dd>

    <dt><?php echo form_label(lang('sg_password'), $password['id']);?></dt>
	<dd>
		<?php echo form_password($password)?>
    	<?php echo form_error($password['name']); ?>
	</dd>

	<dt></dt>
	<dd>
		<?php echo form_checkbox($remember);?> <?php echo form_label(lang('sg_auth_remember'), $remember['id']);?> 
		<?php echo anchor("$site_url/auth/forgot_password", lang('sg_auth_forgot_password'));?> 
	</dd>

	<dt></dt>
	<dd><?php echo form_submit('login', lang('sg_login'), 'class="button"');?></dd>
</dl>

<?php echo form_close()?>
</fieldset>
