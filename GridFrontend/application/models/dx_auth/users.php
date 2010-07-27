<?php

class Users extends Model 
{
	function Users()
	{
		parent::Model();

		// Other stuff
		$this->_prefix = $this->config->item('DX_table_prefix');
		$this->_table = $this->_prefix.$this->config->item('DX_users_table');	
	}
	
	// General function
	
	function get_all($offset = 0, $row_count = 0)
	{
		$users_table = $this->_table;
		
		if ($offset >= 0 AND $row_count > 0)
		{
			$this->db->select("$users_table.*", FALSE);
			$this->db->order_by("$users_table.id", "ASC");
			
			$query = $this->db->get($this->_table, $row_count, $offset); 
		}
		else
		{
			$query = $this->db->get($this->_table);
		}
		
		return $query;
	}
	
	function sync_user_with_simiangrid($response)
	{
        if (element('Success', $response) && is_array($response['User']))
        {
            $user_id = element('UserID', $response['User'], '');
            $username = str_replace(' ', '_', element('Name', $response['User'], ''));
            $email =  element('Email', $response['User'], '');
            
            // Try to fetch this user
            $this->db->where('user_id', $user_id);
		    $existing = $this->db->get($this->_table);
		    
		    if (empty($existing))
		    {
		        // Create this account
                $user = array(
        		    'user_id'	=> $user_id,
        			'username'	=> $username,
        			'email'		=> $email
        		);
        		
        		$this->create_user($user);
		    }
            else
    		{
    		    // Update this account
    		    $user = array(
        			'username'	=> $username,
        			'email'		=> $email
        		);
        		
    		    $this->db->where('user_id', $user_id);
		        $this->db->update($this->_table, $user);
    		}
        }
	}

	function get_user_by_id($user_id)
	{
		$this->db->where('id', $user_id);
		return $this->db->get($this->_table);
	}

	function get_user_by_username($username)
	{
	    // Fetch the SimianGrid user account
	    $query = array(
        	'RequestMethod' => 'GetUser',
        	'Name' => str_replace('_', ' ', $username)
        );
	    $response = rest_post($this->config->item('user_service'), $query);
	    
	    // Lazy synchronization of SimianGrid to SimianGridFrontend
	    $this->sync_user_with_simiangrid($response);
	    
		$this->db->where('username', $username);
		return $this->db->get($this->_table);
	}
	
	function get_user_by_email($email)
	{
	    // Fetch the SimianGrid user account
	    $query = array(
        	'RequestMethod' => 'GetUser',
        	'Email' => $email
        );
	    $response = rest_post($this->config->item('user_service'), $query);
	    
	    // Lazy synchronization of SimianGrid to SimianGridFrontend
	    $this->sync_user_with_simiangrid($response);
	    
		$this->db->where('email', $email);
		return $this->db->get($this->_table);
	}
	
	function get_login($login)
	{
	    if (strpos($login, '@') === FALSE)
	        return $this->get_user_by_username($login);
	    else
	        return $this->get_user_by_email($login);
	}
	
	function check_ban($user_id)
	{
		$this->db->select('1', FALSE);
		$this->db->where('id', $user_id);
		$this->db->where('banned', '1');
		return $this->db->get($this->_table);
	}
	
	function check_username($username)
	{
	    // Fetch the SimianGrid user account
	    $query = array(
        	'RequestMethod' => 'GetUser',
        	'Name' => str_replace('_', ' ', $username)
        );
	    $response = rest_post($this->config->item('user_service'), $query);
	    
	    // Lazy synchronization of SimianGrid to SimianGridFrontend
	    $this->sync_user_with_simiangrid($response);
	    
		$this->db->select('1', FALSE);
		$this->db->where('LOWER(username)=', strtolower($username));
		return $this->db->get($this->_table);
	}

	function check_email($email)
	{
	    // Fetch the SimianGrid user account
	    $query = array(
        	'RequestMethod' => 'GetUser',
        	'Email' => $email
        );
	    $response = rest_post($this->config->item('user_service'), $query);
	    
	    // Lazy synchronization of SimianGrid to SimianGridFrontend
	    $this->sync_user_with_simiangrid($response);
	    
		$this->db->select('1', FALSE);
		$this->db->where('LOWER(email)=', strtolower($email));
		return $this->db->get($this->_table);
	}
	
	function ban_user($user_id, $reason = NULL)
	{
		$data = array(
			'banned' 			=> 1,
			'ban_reason' 	=> $reason
		);
		
		// FIXME: Disable the SimianGrid identities for this user
		
		return $this->set_user($user_id, $data);
	}
	
	function unban_user($user_id)
	{
		$data = array(
			'banned' 			=> 0,
			'ban_reason' 	=> NULL
		);
		
		// FIXME: Enable the SimianGrid identities for this user
		
		return $this->set_user($user_id, $data);
	}

	// User table function

	function create_user($data)
	{
		$data['created'] = date('Y-m-d H:i:s', time());
		return $this->db->insert($this->_table, $data);
	}

	function get_user_field($user_id, $fields)
	{
		$this->db->select($fields);
		$this->db->where('id', $user_id);
		return $this->db->get($this->_table);
	}

	function set_user($user_id, $data)
	{
		$this->db->where('id', $user_id);
		return $this->db->update($this->_table, $data);
	}
	
	function delete_user($user_id)
	{
		$this->db->where('id', $user_id);
		$this->db->delete($this->_table);
		
		// FIXME: Delete all SimianGrid identities associated with this user
		
		return $this->db->affected_rows() > 0;
	}
	
	// Forgot password function

	function newpass($user_id, $pass, $key)
	{
		$data = array(
			'newpass' 		=> $pass,
			'newpass_key' 	=> $key,
			'newpass_time' 	=> date('Y-m-d h:i:s', time() + $this->config->item('DX_forgot_password_expire'))
		);
		return $this->set_user($user_id, $data);
	}

	function activate_newpass($fullname, $user_id, $key)
	{
	    $user = $this->get_user_by_username($fullname);
	    $simiangrid_id = $user->user_id;
	    $password = $user->newpass;
	    
	    $this->create_simiangrid_identities($fullname, $simiangrid_id, $password, NULL);
	    
		$this->db->set('newpass', NULL);
		$this->db->set('newpass_key', NULL);
		$this->db->set('newpass_time', NULL);
		$this->db->where('id', $user_id);
		$this->db->where('newpass_key', $key);
		
		return $this->db->update($this->_table);
	}

	function clear_newpass($user_id)
	{
		$data = array(
			'newpass' 		=> NULL,
			'newpass_key' 	=> NULL,
			'newpass_time' 	=> NULL
		);
		return $this->set_user($user_id, $data);
	}
	
    function create_simiangrid_identity($identifier, $credential, $type, $user_id)
	{
	    $query = array(
    	    'RequestMethod' => 'AddIdentity',
		    'Identifier' => $identifier,
		    'Credential' => $credential,
		    'Type' => $type,
    	    'UserID' => $user_id
        );
        
        $response = rest_post($this->config->item('user_service'), $query);
        
        if (element('Success', $response))
            return true;
        
        log_message('error', "Failed to create user identity $type for $user_id: " . element('Message', $response, 'Unknown error'));
        return false;
	}
	
	function create_simiangrid_identities($fullname, $userid, $password, $openid)
	{
	    $success =
	        $this->create_simiangrid_identity($fullname, '$1$' . md5($password), 'md5hash', $userid) &&
	        $this->create_simiangrid_identity($fullname, md5($fullname . ':Inventory:' . $password), 'a1hash', $userid);
	    
	    if (!empty($openid))
	        $success = $success && $this->create_simiangrid_identity($openid, '', 'openid', $userid);
	    
	    return $success;
	}
}
