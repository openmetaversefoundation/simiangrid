<?php
class Auth extends Controller
{
	// Used for registering and changing password form validation
	var $min_username = 2;
	var $max_username = 20;
	var $min_password = 6;
	var $max_password = 20;

	function Auth()
	{
		parent::Controller();
		
		$this->lang->load('openid', 'english');
		
		$this->load->library('Form_validation');
		$this->load->library('DX_Auth');
		$this->load->library('Openid');
		
		$this->load->helper('url');
		$this->load->helper('form');
	}
	
    function _set_message($msg, $val = '', $sub = '%s')
    {
        return str_replace($sub, $val, $this->lang->line($msg));
    }
	
	function index()
	{
		$this->login();
	}
	
	/* Callback function */
	
	function username_check($username)
	{
		$result = $this->dx_auth->is_username_available($username);
		if ( ! $result)
		{
			$this->form_validation->set_message('username_check', 'Username already exists. Please choose another username.');
		}
		
		return $result;
	}

	function email_check($email)
	{
		$result = $this->dx_auth->is_email_available($email);
		if ( ! $result)
		{
			$this->form_validation->set_message('email_check', 'Email is already used by another user. Please choose another email address.');
		}
		
		return $result;
	}
	
	function recaptcha_check()
	{
		$result = $this->dx_auth->is_recaptcha_match();		
		if ( ! $result)
		{
			$this->form_validation->set_message('recaptcha_check', 'Your confirmation code does not match the one in the image. Try again.');
		}
		
		return $result;
	}
	
	/* End of Callback function */
	
	function login_openid()
	{
	    $data = array();
	    
	    if ( ! $this->dx_auth->is_logged_in())
		{
    	    if ($this->input->post('action') == 'verify')
    		{
    		    // Start the OpenID auth process
    		    $user_id = $this->input->post('openid_identifier');
                $pape_policy_uris = $this->input->post('policies');
                
                if (!$pape_policy_uris)
                {
                  $pape_policy_uris = array();
                }
                
                $this->config->load('openid');
                $sreg_req = $this->config->item('openid_sreg_required');
                $sreg_opt = $this->config->item('openid_sreg_optional');
                $ax_req = $this->config->item('openid_ax_required');
                $ax_opt = $this->config->item('openid_ax_optional');
                $policy = site_url($this->config->item('openid_policy'));
                $request_to = site_url('auth/login_openid');
                
                $this->openid->set_request_to($request_to);
                $this->openid->set_trust_root(base_url());
                $this->openid->set_args(null);
                $this->openid->set_sreg(true, $sreg_req, $sreg_opt, $policy);
                $this->openid->set_ax(true, $ax_req, $ax_opt);
                $this->openid->set_pape(false, $pape_policy_uris);
                $this->openid->authenticate($user_id);
    		}
    		else if ($this->_check_openid('auth/login_openid', $data))
    		{
    		    $openid = $data['openid_success'];
    		    
        		if ($this->dx_auth->openid_login($openid))
    			{
    				// Redirect to homepage
    				redirect('', 'location');
    			}
    			else
    			{
    				// Check if the user is failed logged in because user is banned user or not
    				if ($this->dx_auth->is_banned())
    				{
    					// Redirect to banned uri
    					$this->dx_auth->deny_access('banned');
    				}
    				else
    				{
    				    // OpenID auth succeeded but local login failed
    				    if (!$this->dx_auth->get_auth_error())
    				        $this->dx_auth->set_auth_error("The OpenID $openid is not registered with this site. Please register first");
    				    
    					// Load login page view
    					$data['show_captcha'] = FALSE;
    					parse_template($this->dx_auth->login_view, $data);
    				}
    			}
    		}
    		else
    		{
    		    // Load login page view
    		    $data['show_captcha'] = FALSE;
		        parse_template($this->dx_auth->login_view, $data);
    		}
		}
		else
		{
			$data['auth_message'] = 'You are already logged in.';
			parse_template($this->dx_auth->logged_in_view, $data);
		}
	}
	
	function login()
	{
		if ( ! $this->dx_auth->is_logged_in())
		{
			$val = $this->form_validation;
			
			// Set form validation rules
			$val->set_rules('first_name', 'First Name', 'trim|required|xss_clean|alpha_dash');
			$val->set_rules('last_name', 'Last Name', 'trim|required|xss_clean|alpha_dash');
			$val->set_rules('password', 'Password', 'trim|required|xss_clean');
			$val->set_rules('remember', 'Remember me', 'integer');

			// Set captcha rules if login attempts exceed max attempts in config and captcha login is enabled
			if ($this->dx_auth->is_max_login_attempts_exceeded() AND $this->dx_auth->captcha_login)
			{
			    $val->set_rules('recaptcha_response_field', 'Confirmation Code', 'trim|xss_clean|required|callback_recaptcha_check');
			}
			
			if ($val->run() AND $this->dx_auth->login($val->set_value('first_name'), $val->set_value('last_name'), $val->set_value('password'), $val->set_value('remember')))
			{
				// Redirect to homepage
				redirect('', 'location');
			}
			else
			{
				// Check if the user is failed logged in because user is banned user or not
				if ($this->dx_auth->is_banned())
				{
					// Redirect to banned uri
					$this->dx_auth->deny_access('banned');
				}
				else
				{						
					// Default is we don't show captcha until max login attempts exceeded
					$data['show_captcha'] = FALSE;
				    
					// Show captcha if login attempts exceed max attempts in config
					if ($this->dx_auth->is_max_login_attempts_exceeded() AND $this->dx_auth->captcha_login)
					{
						// Set view data to show captcha on view file
						$data['show_captcha'] = TRUE;
					}
					
					// Load login page view
					parse_template($this->dx_auth->login_view, $data);
				}
			}
		}
		else
		{
			$data['auth_message'] = 'You are already logged in.';
			parse_template($this->dx_auth->logged_in_view, $data);
		}
	}
	
	function logout()
	{
		$this->dx_auth->logout();
		
		$data['auth_message'] = 'You have been logged out.';
	    parse_template($this->dx_auth->logout_view, $data);
	}
	
	function register()
	{
		if ( ! $this->dx_auth->is_logged_in() AND $this->dx_auth->allow_registration)
		{
			$val = $this->form_validation;
			
			// Set form validation rules	
			$val->set_rules('first_name', 'First Name', 'trim|required|xss_clean|min_length['.$this->min_username.']|max_length['.$this->max_username.']|callback_username_check|alpha_dash');
			$val->set_rules('last_name', 'Last Name', 'trim|required|xss_clean|min_length['.$this->min_username.']|max_length['.$this->max_username.']|callback_username_check|alpha_dash');
			$val->set_rules('password', 'Password', 'trim|required|xss_clean|min_length['.$this->min_password.']|max_length['.$this->max_password.']|matches[confirm_password]');
			$val->set_rules('confirm_password', 'Confirm Password', 'trim|required|xss_clean');
			$val->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email|callback_email_check');
			$val->set_rules('avatar_type', 'Avatar Type', 'trim|xss_clean');
			
    		// Is registration using captcha
			if ($this->dx_auth->captcha_registration)
			{
				// Set recaptcha rules.
				// IMPORTANT: Do not change 'recaptcha_response_field' because it's used by reCAPTCHA API,
				// This is because the limitation of reCAPTCHA, not DX Auth library
				$val->set_rules('recaptcha_response_field', 'Confirmation Code', 'trim|xss_clean|required|callback_recaptcha_check');
			}

			// Run form validation and register user if validation succeeds
			if ($val->run() AND $this->dx_auth->register($val->set_value('first_name'), $val->set_value('last_name'), $val->set_value('password'), $val->set_value('email'), $val->set_value('avatar_type', 'DefaultAvatar')))
			{
				// Set success message accordingly
				if ($this->dx_auth->email_activation)
				{
					$data['auth_message'] = 'You have successfully registered. Check your email address to activate your account.';
				}
				else
				{					
					$data['auth_message'] = 'You have successfully registered. '.anchor(site_url($this->dx_auth->login_uri), 'Login');
				}
				
				// Load registration success page
        	    parse_template($this->dx_auth->register_success_view, $data);
			}
			else
			{
				// Load registration page
        	    parse_template($this->dx_auth->register_view);
			}
		}
		elseif ( ! $this->dx_auth->allow_registration)
		{
			$data['auth_message'] = 'Registration has been disabled.';
        	parse_template($this->dx_auth->register_disabled_view, $data);
		}
		else
		{
			$data['auth_message'] = 'You have to logout first, before registering.';
        	parse_template($this->dx_auth->logged_in_view, $data);
		}
	}
	
	function register_openid()
	{
	    if ( ! $this->dx_auth->is_logged_in() AND $this->dx_auth->allow_registration)
		{
		    $data = array();
		    
		    if ($this->input->post('action') == 'verify')
		    {
		        // Start the OpenID auth process
		        $user_id = $this->input->post('openid_identifier');
                $pape_policy_uris = $this->input->post('policies');
                
                if (!$pape_policy_uris)
                {
                  $pape_policy_uris = array();
                }
                
                $this->config->load('openid');
                $sreg_req = $this->config->item('openid_sreg_required');
                $sreg_opt = $this->config->item('openid_sreg_optional');
                $ax_req = $this->config->item('openid_ax_required');
                $ax_opt = $this->config->item('openid_ax_optional');
                $policy = site_url($this->config->item('openid_policy'));
                $request_to = site_url('auth/register_openid');
                
                $this->openid->set_request_to($request_to);
                $this->openid->set_trust_root(base_url());
                $this->openid->set_args(null);
                $this->openid->set_sreg(true, $sreg_req, $sreg_opt, $policy);
                $this->openid->set_ax(true, $ax_req, $ax_opt);
                $this->openid->set_pape(false, $pape_policy_uris);
                $this->openid->authenticate($user_id);
		    }
		    else if ($this->session->flashdata('openid_identifier') OR $this->_check_openid('auth/register_openid', $data))
		    {
		        $openid = null;
		        
		        if ($this->session->flashdata('openid_identifier'))
		        {
		            $openid = $this->session->flashdata('openid_identifier');
		            $data['openid_success'] = $openid;
		            $this->session->keep_flashdata('openid_identifier');
		        }
		        else
		        {
		            $openid = $data['openid_success'];
		            $this->session->set_flashdata('openid_identifier', $openid);
		        }
		        
		        // OpenID authentication succeeded
		        $val = $this->form_validation;
    			
    			// Set form validation rules
    			$val->set_rules('first_name', 'First Name', 'trim|required|xss_clean|min_length['.$this->min_username.']|max_length['.$this->max_username.']|callback_username_check|alpha_numeric');
    			$val->set_rules('last_name', 'Last Name', 'trim|required|xss_clean|min_length['.$this->min_username.']|max_length['.$this->max_username.']|callback_username_check|alpha_numeric');
    			$val->set_rules('password', 'Password', 'trim|required|xss_clean|min_length['.$this->min_password.']|max_length['.$this->max_password.']|matches[confirm_password]');
    			$val->set_rules('confirm_password', 'Confirm Password', 'trim|required|xss_clean');
    			$val->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email|callback_email_check');
    			$val->set_rules('avatar_type', 'Avatar Type', 'trim|xss_clean');
    			
                // Is registration using captcha
    			if ($this->dx_auth->captcha_registration)
    			{
    				// Set recaptcha rules.
    				// IMPORTANT: Do not change 'recaptcha_response_field' because it's used by reCAPTCHA API,
    				// This is because the limitation of reCAPTCHA, not DX Auth library
    				$val->set_rules('recaptcha_response_field', 'Confirmation Code', 'trim|xss_clean|required|callback_recaptcha_check');
    			}
                
    			// Run form validation and register user if validation succeeds
    			if ($val->run() AND $this->dx_auth->register($val->set_value('first_name'), $val->set_value('last_name'), $val->set_value('password'), $val->set_value('email'), $val->set_value('avatar_type', 'DefaultAvatar'), $openid))
    			{
    				// Set success message accordingly
    				if ($this->dx_auth->email_activation)
    				{
    					$data['auth_message'] = 'You have successfully registered. Check your email address to activate your account.';
    				}
    				else
    				{					
    					$data['auth_message'] = 'You have successfully registered. '.anchor(site_url($this->dx_auth->login_uri), 'Login');
    				}
    				
    				// Load OpenID registration success page
    	            parse_template($this->dx_auth->register_success_view, $data);
    			}
    			else
    			{
    			    // Load OpenID registration page
    	            parse_template($this->dx_auth->register_openid_view, $data);
    			}
		    }
		    else
		    {
		        redirect('auth/register', 'location');
		    }
		}
	    elseif ( ! $this->dx_auth->allow_registration)
		{
			$data['auth_message'] = 'Registration has been disabled.';
            parse_template($this->dx_auth->register_disabled_view, $data);
		}
		else
		{
			$data['auth_message'] = 'You have to logout first, before registering.';
            parse_template($this->dx_auth->logged_in_view, $data);
		}
	}
	
	function _check_openid($returnPath, &$data)
	{
	    if (!isset($data))
	        $data = array();
	    
	    $this->config->load('openid');
	    $request_to = site_url($returnPath);
	    
	    $this->openid->set_request_to($request_to);
	    $response = $this->openid->getResponse();
	    
    	switch ($response->status)
        {
        case Auth_OpenID_CANCEL:
            $data['auth_message'] = $this->lang->line('openid_cancel');
            break;
        case Auth_OpenID_FAILURE:
            $data['auth_message'] = $this->_set_message('openid_failure', $response->message);
            break;
        case Auth_OpenID_SUCCESS:
            $openid = $response->getDisplayIdentifier();
            $esc_identity = htmlspecialchars($openid, ENT_QUOTES);
            
            $data['openid_success'] = $openid;

            //$data['success'] = $this->_set_message('openid_success', array($esc_identity, $esc_identity), array('%s','%t'));

            //if ($response->endpoint->canonicalID) {
            //    $data['success'] .= $this->_set_message('openid_canonical', $response->endpoint->canonicalID);
            //}

            $sreg_resp = Auth_OpenID_SRegResponse::fromSuccessResponse($response);
            $sreg = $sreg_resp->contents();

            $ax_resp = new Auth_OpenID_AX_FetchResponse();
            $ax = $ax_resp->fromSuccessResponse($response);
            
            if (isset($sreg['email']))
            {
                $data['openid_email'] = $sreg['email'];
            }
            if (isset($sreg['fullname']))
            {
                list($data['openid_first'], $data['openid_last']) = explode(' ', $sreg['fullname']);
            }
            if ($ax)
            {
                if (isset($ax->data['http://axschema.org/contact/email']))
                    $data['openid_email'] = $ax->getSingle('http://axschema.org/contact/email');
                if (isset($ax->data['http://axschema.org/namePerson/first']))
                    $data['openid_first'] = $ax->getSingle('http://axschema.org/namePerson/first');
                if (isset($ax->data['http://axschema.org/namePerson/last']))
                    $data['openid_last'] = $ax->getSingle('http://axschema.org/namePerson/last');
            }
            
            log_message('debug', "OpenID authentication succeeded for $openid");

            return true;
        }
        
        return false;
	}
	
	function activate()
	{
		// Get username and key
		$username = $this->uri->segment(3);
		$key = $this->uri->segment(4);

		// Activate user
		if ($this->dx_auth->activate($username, $key)) 
		{
			$data['auth_message'] = 'Your account have been successfully activated. '.anchor(site_url($this->dx_auth->login_uri), 'Login');
			parse_template($this->dx_auth->activate_success_view, $data);
		}
		else
		{
			$data['auth_message'] = 'The activation code you entered was incorrect. Please check your email again.';
			parse_template($this->dx_auth->activate_failed_view, $data);
		}
	}
	
	function forgot_password()
	{
		$val = $this->form_validation;
		
		// Set form validation rules
		$val->set_rules('login', 'Username or Email address', 'trim|required|xss_clean');

		// Validate rules and call forgot password function
		if ($val->run() AND $this->dx_auth->forgot_password($val->set_value('login')))
		{
			$data['auth_message'] = 'An email has been sent to your email with instructions with how to activate your new password.';
			parse_template($this->dx_auth->forgot_password_success_view, $data);
		}
		else
		{
			parse_template($this->dx_auth->forgot_password_view);
		}
	}
	
	function reset_password()
	{
		// Get username and key
		$username = $this->uri->segment(3);
		$key = $this->uri->segment(4);

		// Reset password
		if ($this->dx_auth->reset_password($username, $key))
		{
			$data['auth_message'] = 'You have successfully reset you password, '.anchor(site_url($this->dx_auth->login_uri), 'Login');
			parse_template($this->dx_auth->reset_password_success_view, $data);
		}
		else
		{
			$data['auth_message'] = 'Reset failed. Your username and key are incorrect. Please check your email again and follow the instructions.';
			parse_template($this->dx_auth->reset_password_failed_view, $data);
		}
	}
	
	function change_password()
	{
		// Check if user logged in or not
		if ($this->dx_auth->is_logged_in())
		{			
			$val = $this->form_validation;
			
			// Set form validation
			$val->set_rules('old_password', 'Old Password', 'trim|required|xss_clean|min_length['.$this->min_password.']|max_length['.$this->max_password.']');
			$val->set_rules('new_password', 'New Password', 'trim|required|xss_clean|min_length['.$this->min_password.']|max_length['.$this->max_password.']|matches[confirm_new_password]');
			$val->set_rules('confirm_new_password', 'Confirm new Password', 'trim|required|xss_clean');
			
			// Validate rules and change password
			if ($val->run() AND $this->dx_auth->change_password($val->set_value('old_password'), $val->set_value('new_password')))
			{
				$data['auth_message'] = 'Your password has successfully been changed.';
				parse_template($this->dx_auth->change_password_success_view, $data);
			}
			else
			{
				parse_template($this->dx_auth->change_password_view);
			}
		}
		else
		{
			// Redirect to login page
			$this->dx_auth->deny_access('login');
		}
	}	
	
	function cancel_account()
	{
		// Check if user logged in or not
		if ($this->dx_auth->is_logged_in())
		{			
			$val = $this->form_validation;
			
			// Set form validation rules
			$val->set_rules('password', 'Password', "trim|required|xss_clean");
			
			// Validate rules and change password
			if ($val->run() AND $this->dx_auth->cancel_account($val->set_value('password')))
			{
				// Redirect to homepage
				redirect('', 'location');
			}
			else
			{
				parse_template($this->dx_auth->cancel_account_view);
			}
		}
		else
		{
			// Redirect to login page
			$this->dx_auth->deny_access('login');
		}
	}
	
	function deny()
	{
	    $data['auth_message'] = 'You do not have permission to access this area.';
	    parse_template($this->dx_auth->deny_view, $data);
	}
}
