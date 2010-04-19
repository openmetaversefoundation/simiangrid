	<?php  				
		// Show reset password message if exist
		if (isset($reset_message))
			echo $reset_message;
		
		// Show error
		echo validation_errors();

		$this->table->set_template(array(
			'row_start' => '<tr class="trOdd">',
			'row_alt_start' => '<tr class="trEven">'
		));

		$this->table->set_heading('', 'Username', 'Email', 'Banned', 'Last IP', 'Last login', 'Created');
		
		foreach ($users as $user) 
		{
			$banned = ($user->banned == 1) ? 'Yes' : 'No';

			// if last_login time is the unix epoch, then show
			// "Never" in the UI instead of 1970-01-01
			if (date('U', strtotime($user->last_login)) == 0)
			{
				$last_login = "Never";
			}
			else
			{
				$last_login = date('Y-m-d', strtotime($user->last_login));
			}
			
			$this->table->add_row(
				form_checkbox('checkbox_'.$user->id, $user->id),
				$user->username, 
				$user->email, 
				$banned, 
				$user->last_ip,
				$last_login,
				date('Y-m-d', strtotime($user->created)));
		}
		
		echo form_open($this->uri->uri_string());
				
		echo form_submit('ban', 'Ban user');
		echo form_submit('unban', 'Unban user');
		echo form_submit('reset_pass', 'Reset password');
		
		echo '<br/><br/>';
		
		echo $this->table->generate(); 
		
		echo form_close();
		
		echo $pagination;
			
	?>
