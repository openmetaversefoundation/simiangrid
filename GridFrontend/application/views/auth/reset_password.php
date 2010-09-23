<?php

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

?>

<h2>Reset Password</h2>

<?php echo form_open(site_url("auth/reset_password")); ?>

<?php echo form_hidden('code', $code); ?>
<dl>
	<dt><?php echo form_label(lang('sg_password'), $password['id']);?></dt>
	<dd>
		<?php echo form_password($password); ?>
        <?php echo form_error($password['name']); ?>
	</dd>

	<dt><?php echo form_label(lang('sg_password_confirm'), $confirm_password['id']);?></dt>
	<dd>
		<?php echo form_password($confirm_password); ?>
		<?php echo form_error($confirm_password['name']); ?>
	</dd>
</dl>	
<?php echo form_submit('reset', 'Change Password'); ?>

<?php echo form_close()?>
