<?php
	echo form_open($this->uri->uri_string());
			
	echo form_input('name');
	echo form_submit('search', 'Search');
	
	echo form_close();
	
?>
