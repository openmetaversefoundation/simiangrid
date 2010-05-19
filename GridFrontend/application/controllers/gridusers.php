<?php

class GridUsers extends Controller {

	function GridUsers()
	{
		parent::Controller();	

		$this->load->library('Table');
		$this->load->library('DX_Auth');
	}
	
	function show()
	{
		$this->load->model('dx_auth/users', 'users');

		$uri_array = $this->uri->ruri_to_assoc(3);
		if (isset($uri_array['id']))
		{
			$user_data = get_grid_user_data('id', $uri_array['id']);
        }
		elseif (isset($uri_array['name']))
		{
			$user_data = get_grid_user_data('name', $uri_array['name']);
		}

		if(isset($user_data))
		{
			$data['user_data']['Name'] = $user_data['Name'];

			// this stuff visible to admins only...
			if ($this->dx_auth->is_admin())
			{
				$data['user_data']['User ID'] = $user_data['UserID'];
				$data['user_data']['Email'] = $user_data['Email'];
				$data['user_data']['Access Level'] = $user_data['AccessLevel'];
			}
		}
		else
		{
			$data = '';
		}

		parse_template('gridusers/show', $data);
	}
}
