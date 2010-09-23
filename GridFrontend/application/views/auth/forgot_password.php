<?php

$email = array(
	'name'	=> 'email',
	'id'	=> 'email',
	'maxlength'	=> 80,
	'size'	=> 30,
	'value' => set_value('email')
);

?>

<h2>Forgotten Password</h2>

<?php echo form_open($this->uri->uri_string()); ?>

<dl>
	<dt><?php echo form_label('Enter your Email Address', $email['id']);?></dt>
	<dd>
		<?php echo form_input($email); ?> 
		<?php echo form_error($email['name']); ?>
		<?php echo form_submit('reset', 'Send Reset Email'); ?>
	</dd>
</dl>

<?php echo form_close()?>
