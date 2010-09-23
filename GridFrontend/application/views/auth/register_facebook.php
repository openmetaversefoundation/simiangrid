<?php

if (!isset($facebook_token)) $facebook_token = '';
if (!isset($username)) $username = '';
if (!isset($email)) $email = '';

$username_field = array(
	'name'	=> 'username',
	'id'	=> 'username',
	'size'	=> 30,
	'value' =>  set_value('username', $username)
);

$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'size'	=> 30,
	'value' => set_value('password')
);

$confirm_password = array(
	'name'	=> 'confirm_password',
	'id'	=> 'confirm_password',
	'size'	=> 30,
	'value' => set_value('confirm_password')
);

$email_field = array(
	'name'	=> 'email',
	'id'	=> 'email',
	'maxlength'	=> 80,
	'size'	=> 30,
	'value'	=> set_value('email', $email)
);

$facebook_token_field = array(
    'name'     => 'facebook_token',
    'id'       => 'facebook_token',
    'size'     => 40,
    'value'    => set_value('facebook_token', $facebook_token),
    'readonly' => 'readonly'
);
?>

<fieldset><legend><?php echo lang('sg_auth_fb_register'); ?></legend>
<dl>
	<?php echo form_open("$site_url/auth/register_facebook"); ?>

	<dt><?php echo form_label(lang('sg_name'), $username_field['id']);?></dt>
	<dd>
		<?php echo form_input($username_field)?>
        <?php echo form_error($username_field['name']); ?>
	</dd>
	
	<dt><?php echo form_label(lang('sg_auth_apperance'), 'avatar_type');?></dt>
	<dd>
		<?php
		    $options = array();
		    $options['DefaultAvatar'] = 'Default Avatar';
		    foreach ($this->config->item('extra_avatar_types') as $lbl => $val)
                $options[$val] = $lbl;
		?>
		<?php echo form_dropdown('avatar_type', $options, 'DefaultAvatar', 'id="avatar_type"')?>
		<?php echo form_error('avatar_type'); ?>
	</dd>

	<dt><?php echo form_label(lang('sg_password'), $password['id']);?></dt>
	<dd>
		<?php echo form_password($password)?>
        <?php echo form_error($password['name']); ?>
	</dd>

	<dt><?php echo form_label(lang('sg_password_confirm'), $confirm_password['id']);?></dt>
	<dd>
		<?php echo form_password($confirm_password);?>
		<?php echo form_error($confirm_password['name']); ?>
	</dd>

	<dt><?php echo form_label(lang('sg_email'), $email_field['id']);?></dt>
	<dd>
		<?php echo form_input($email_field);?>
		<?php echo form_error($email_field['name']); ?>
	</dd>

	<dt></dt>
	<dd><?php echo form_submit('register',lang('sg_register'), 'class="button"');?></dd>
	
	<?php echo form_close()?>
</dl>
</fieldset>
