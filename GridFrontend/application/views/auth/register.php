<?php

$username = array(
	'name'	=> 'username',
	'id'	=> 'username',
	'size'	=> 30,
	'value' =>  set_value('username')
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

$email = array(
	'name'	=> 'email',
	'id'	=> 'email',
	'maxlength'	=> 80,
	'size'	=> 30,
	'value'	=> set_value('email')
);

$openid_identifier = array(
    'name'  => 'openid_identifier',
    'id'    => 'openid_identifier',
    'size'  => 40
);
?>

<?php openid_identifier_render(lang('sg_auth_register_login'), site_url('auth/register_openid'), "Register"); ?>

<?php generate_facebook_auth(site_url('auth/register_facebook')); ?>

<fieldset><legend><?php echo lang('sg_register'); ?></legend>
<?php echo form_open($this->uri->uri_string())?>

<dl>
	<dt><?php echo form_label(lang('sg_name'), $username['id']);?></dt>
	<dd>
		<?php echo form_input($username)?>
        <?php echo form_error($username['name']); ?>
	</dd>
	
	<dt><?php echo form_label(lang('sg_auth_appearance'), 'avatar_type');?></dt>
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

	<dt><?php echo form_label(lang('sg_email'), $email['id']);?></dt>
	<dd>
		<?php echo form_input($email);?>
		<?php echo form_error($email['name']); ?>
	</dd>

	<dt></dt>
	<dd><?php echo form_submit('register',lang('sg_register'), 'class="button"');?></dd>
</dl>

<?php echo form_close()?>
</fieldset>

