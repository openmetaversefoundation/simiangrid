<?php

// if we're given a list of user data, spit it out in a table.
// otherwise we couldn't find the user.  admins viewing this page will
// see more info than regular users; that's all handled in the
// controller.
if(isset($user_data))
{
	foreach($user_data as $key => $value)
	{
		if(isset($value))
		{
			$this->table->add_row(
				$key,
				$value
			);
		}
	}

	echo $this->table->generate();
}
else
{
	echo "User not found.";
}

?>
