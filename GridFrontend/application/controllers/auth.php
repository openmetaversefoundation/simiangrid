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

		$this->load->library('Openid');
		$this->load->library('Form_validation');

		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('simian_openid_helper');
		$this->load->helper('simian_facebook_helper');

		$this->lang->load('simian_grid', get_language() );
		$this->lang->load('openid', get_language() );
		$this->lang->load('form_validation', get_language() );
	}
	
	function index()
	{
		$this->login();
	}
	
	/* Callback function */
	
	function username_check($username)
	{
		$result = false;
		$space_pos = strpos($username, ' ');
		if ( $space_pos !== false ) {
			$first_name = substr($username, 0, $space_pos);
			$last_name = substr($username, $space_pos);
			if ( $this->form_validation->alpha_dash($first_name) && $this->form_validation->alpha_dash($first_name) ) {
				$result = true;
			}
		}
		return $result;
	}
	
	function username_exists_check($username)
	{
		$result = $this->simiangrid->get_user_by_name($username);
		if ( $result != null ) {
			return false;
		} else {
			return true;
		}
	}

	function email_check($email)
	{
		$result = $this->simiangrid->get_user_by_email($email);
		if ( $result != null ) {
			$this->form_validation->set_message('email', lang('sg_auth_email_exists') );
			return false;
		} else {
			return true;
		}
	}

	function email_exists($email)
	{
		$result = $this->simiangrid->get_user_by_email($email);
		if ( $result != null ) {
			return true;
		} else {
			$this->form_validation->set_message('email', lang('sg_auth_email_not_exist') );
			return false;
		}
	}

	function login_facebook()
	{
		if ( ! $this->sg_auth->is_logged_in()) {
			if ( ! empty($_SERVER['QUERY_STRING']) ) {
				parse_str($_SERVER['QUERY_STRING'],$_GET); 
				if ( ! empty($_GET['code']) ) {
					$token = process_facebook_verification($_GET['code'], site_url("auth/login_facebook") );
					$fb_id = facebook_get_id($this, $token);
					if ( $this->sg_auth->login_facebook($fb_id) ) {
						return redirect('', 'location');
					} else {
						push_message(lang('sg_auth_facebook_error_login'), 'error');
					}
				}
			}
		}
		return redirect('auth/login');
	}

	function login_openid()
	{
	    $data = array();
	    
	    if ( ! $this->sg_auth->is_logged_in()) {
    	    if ($this->input->post('action') == 'verify') {
				return openid_process_verify($this, site_url('auth/login_openid'));
    		} elseif ( openid_check($this, site_url('auth/login_openid'), $data)) {
    		    $openid = $data['openid_identifier'];
        		if ($this->sg_auth->login_openid($openid)) {
    				// Redirect to homepage
    				return redirect('', 'location');
    			} else {
					push_message(lang('sg_auth_openid_error_login'), 'error');
				}
    		}
		} else {
			push_message(lang('sg_auth_error_already'), 'error');
			return redirect('about', 'location');
		}
		return redirect('auth/login');
	}
	
	function login()
	{
		if ( ! $this->sg_auth->is_logged_in() ) {
			$val = $this->form_validation;
			
			// Set form validation rules
			$val->set_rules('username', 'User Name', 'trim|required|xss_clean|callback_username_check');
			$val->set_rules('password', 'Password', 'trim|required|xss_clean');
			$val->set_rules('remember', 'Remember me', 'integer');
			
			if ($val->run() AND $this->sg_auth->login($val->set_value('username'), $val->set_value('password'), $val->set_value('remember')) ) {
				// Redirect to homepage
				return redirect('about', 'location');
			} else {
				return parse_template('auth/login');
			}
		} else {
			push_message(lang('sg_auth_error_already'), 'error');
			return redirect('about', 'location');
		}
	}
	
	function logout()
	{
		$this->sg_auth->logout();
		push_message(lang('sg_auth_logged_out'), 'info');
		return redirect('about', 'location');
	}
	
	function _register($val)
	{
		// Set form validation rules	
		$val->set_rules('username', 'User Name', 'trim|required|xss_clean|min_length['.$this->min_username.']|max_length['.$this->max_username.']|callback_username_check|callback_username_exists_check');
		$val->set_rules('password', 'Password', 'trim|required|xss_clean|min_length['.$this->min_password.']|max_length['.$this->max_password.']|matches[confirm_password]');
		$val->set_rules('confirm_password', 'Confirm Password', 'trim|required|xss_clean');
		$val->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email|callback_email_check');
		$val->set_rules('avatar_type', 'Avatar Type', 'trim|xss_clean');
		
		// Run form validation and register user if validation succeeds
		if ($val->run() ) {
			$user_id = $this->sg_auth->register($val->set_value('username'), $val->set_value('password'), $val->set_value('email'), $val->set_value('avatar_type', 'DefaultAvatar'));
			if ( $user_id != null ) {
				return $user_id;
			}
		} else {
			return FALSE;
		}
		return null;
	}
	
	function register()
	{
		if ( ! $this->sg_auth->is_logged_in() && $this->sg_auth->allow_registration ) {
			$val = $this->form_validation;
			$user_id = $this->_register($val);
			if ( $user_id === null ) {
				push_message(lang('sg_auth_register_failure'), 'error');
				return redirect('auth/register');
			} else {
				return parse_template('auth/register');
			}
		} elseif ( ! $this->sg_auth->allow_registration) {
			push_message(lang('sg_auth_registration_disabled'), 'error');
		} else {
			push_message(lang('sg_auth_error_logout_first'), 'error');
		}
		return redirect('about');
	}
	
	function _get_facebook_token_flash()
	{
		$token = $this->session->flashdata('facebook_token');
		if ( $token != null ) {
			$this->session->keep_flashdata('facebook_token');
		}
		return $token;
	}

	function _get_facebook_token()
	{
		$token = null;
		if ( ! empty($_SERVER['QUERY_STRING']) ) {
			parse_str($_SERVER['QUERY_STRING'], $_GET);
			if ( ! empty($_GET['code']) ) {
				$token = process_facebook_verification($_GET['code'], site_url("auth/register_facebook"));
				$this->session->set_flashdata('facebook_token', $token);
			} else {
				return $this->_get_facebook_token_flash();
			}
		} else {
			return $this->_get_facebook_token_flash();
		}
		return $token;
	}
	
	function register_facebook()
	{
		if ( ! $this->sg_auth->is_logged_in() && $this->sg_auth->allow_registration ) {
			$token = $this->_get_facebook_token();
			if ( $token == null ) {
				return redirect('auth/register');
			}
			$token = $this->_get_facebook_token();
			if ( $token == null ) {
				push_message(lang('sg_auth_fb_error'), 'error');
				return redirect('auth/register');
			}
			$fb_id = facebook_get_id($this, $token);
			if ( $fb_id == null ) {
				push_message(lang('sg_auth_fb_error'), 'error');
				return redirect('auth/register');
			}
			if ( ! $this->sg_auth->facebook_exists($fb_id) && facebook_check($this, $token, $data) ) {
				$val = $this->form_validation;

				$user_id = $this->_register($val);

				if ( $user_id === null ) {
    	            push_message(lang('sg_auth_register_failure'), 'error');
	                return redirect('auth/register');
				} else if ( $user_id !== FALSE ) { 
					if ( $this->simiangrid->identity_set($user_id, 'facebook', $fb_id) ) {
						return redirect('about');
					} else {
						push_message(lang('sg_auth_fb_error_assoc'), 'error');
						$this->simiangrid->user_delete($user_id);
					}
				} else {
    			    // Load OpenID registration page
    	            return parse_template('auth/register_facebook', $data);
    			}
			}
		} elseif ( ! $this->sg_auth->allow_registration ) {
			push_message(lang('sg_auth_registration_disabled'), 'error');
		} else {
			push_message(lang('sg_auth_error_logout_first'), 'error');
		}
		return redirect('about');
	}
	
	function register_openid()
	{
	    if ( ! $this->sg_auth->is_logged_in() && $this->sg_auth->allow_registration ) {
		    $data = array();
		    
		    if ($this->input->post('action') == 'verify') {
				return openid_process_verify($this, site_url('auth/register_openid'));
		    } else if ($this->session->flashdata('openid_identifier') OR openid_check($this, site_url('auth/register_openid'), $data)) {
		        $openid = null;
		        if ($this->session->flashdata('openid_identifier')) {
		            $openid = $this->session->flashdata('openid_identifier');
		            $data['openid_identifier'] = $openid;
		            $this->session->keep_flashdata('openid_identifier');
		        } else {
		            $openid = $data['openid_identifier'];
		            $this->session->set_flashdata('openid_identifier', $openid);
		        }
		        
				if ( ! $this->sg_auth->openid_exists($openid) ) {
			        // OpenID authentication succeeded
			        $val = $this->form_validation;
		
					$user_id = $this->_register($val);
				
					if ( $user_id === null ) {
	                    push_message(lang('sg_auth_register_failure'), 'error');
    	                return redirect('auth/register');
					} else if ( $user_id !== FALSE ) {
						if ( $this->simiangrid->identity_set($user_id, 'openid', $openid) ) {
							return redirect('about');
						} else {
							push_message(lang('sg_auth_open_error_assoc'), 'error');
							$this->simiangrid->user_delete($user_id);
						}
					} else {
	    			    // Load OpenID registration page
	    	            return parse_template('auth/register_openid', $data);
	    			}
				}
		    } else {
				return redirect('auth/register');
			}
		} elseif ( ! $this->sg_auth->allow_registration ) {
			push_message(lang('sg_auth_registration_disabled'), 'error');
		} else {
			push_message(lang('sg_auth_error_logout_first'), 'error');
		}
		return redirect('about');
	}
	
	function validate($code)
	{
		if ( ! $this->sg_auth->validate('email', $code) ) {
			push_message(set_message('sg_auth_validation_fail'), 'error');
		} else {
			push_message(lang('sg_auth_validation_success'), 'info');
		}
		return redirect('about');
	}
	
	function forgot_password()
	{
		if ( ! $this->sg_auth->is_logged_in() ) {
			$val = $this->form_validation;
			$val->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email|callback_email_exists');
			if ( $val->run() ) {
				$email = $val->set_value('email');
				$user = $this->simiangrid->get_user_by_email($email);
				if ( $this->sg_auth->reset_password_start($user['UserID']) ) {
					push_message(set_message('sg_auth_password_reset_email', $email), 'info');
				} 
				return redirect('about', 'location');
			}
		} else {
			return redirect('about', 'location');
		}
		return parse_template('auth/forgot_password');
	}
	
	function reset_password($code=null)
	{
		if ( $this->sg_auth->is_logged_in() ) {
			push_message(lang('sg_auth_error_logout_first'), 'error');
			return redirect('about', 'location');
		}
		if ( $code == null ) {
			log_message('debug', 'no code detected so processing form');
			$val = $this->form_validation;
			$val->set_rules('password', 'Password', 'trim|required|xss_clean|min_length['.$this->min_password.']|max_length['.$this->max_password.']|matches[confirm_password]');
			$val->set_rules('confirm_password', 'Confirm Password', 'trim|required|xss_clean');
			$val->set_rules('code', '', 'trim|required|xss_clean');
			
			if ( $val->run() ) {
				log_message('debug', 'validated reset_password form');
				if ( $this->sg_auth->password_reset($val->set_value('code'), $val->set_value('password')) ) {
					push_message(lang('sg_password_success'), 'info');
				} else {
					push_message(lang('sg_password_error'), 'error');
				}
				return redirect('about', 'location');
			} else {
				if ( $this->input->post('code') != null ) {
					$code = $this->input->post('code');
					log_message('debug', 'Unable to validate reset_password form');
					return redirect(site_url("auth/reset_password/$code"), 'location');
				} else {
					log_message('error', 'Missing code on reset_password');
					return redirect('about', 'location');
				}
			}
		} else {
			if ( $this->sg_auth->password_reset_verify($code) ) {
				log_message('debug', 'validated reset_password code so producing form');
				$data = array();
				$data['code'] = $code;
				return parse_template('auth/reset_password', $data);
			} else {
				log_message('debug', 'unable to validate reset_password code');
				return redirect('about', 'location');
			}
		}
	}
}
