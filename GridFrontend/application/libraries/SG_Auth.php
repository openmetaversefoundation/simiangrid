<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class SG_Auth
{

	function SG_Auth()
	{
		$this->ci =& get_instance();
		$this->ci->load->library('Session');
		$this->ci->load->helper('cookie');
		$this->ci->load->helper('simian_view');
		$this->ci->load->config('openid');
		$this->ci->load->database();
		$this->ci->load->model('sg_auth/user_session', 'user_session');
		$this->ci->load->model('sg_auth/user_validation', 'user_validation');
		$this->_init();
	}
	
	function _init()
	{
		$this->enabled_openid = $this->ci->config->item('openid_enabled');
		$this->email_activation = $this->ci->config->item('email_activation');
		$this->admin_access_level = $this->ci->config->item('admin_access_level');
		$this->allow_registration = $this->ci->config->item('allow_registration');
		
		$this->session = $this->ci->session;
		$this->simiangrid = $this->ci->simiangrid;
		$this->user_session = $this->ci->user_session;
		$this->user_validation = $this->ci->user_validation;
	}

	function is_logged_in()
	{
		$result = false;
		$uuid = $this->get_uuid();
		if ( $uuid === null ) {
			$user_id = $this->_check_autologin();
			if ( $user_id != null ) {
				$this->_login_session($user_id, true);
				$result = true;
			}
		} else {
			$result = true;
		}
		return $result;
	}
	
	function access_level()
	{

		$user = $this->simiangrid->get_user($this->get_uuid());
		if ( $user !== null ) {
			return $user['AccessLevel'];
		} else {
			return null;
		}
	}

	function is_admin()
	{
		$is_admin = false;
		$access_level = $this->access_level();
		if ( $access_level !== null ) {
			if ( $access_level >= $this->admin_access_level ) {
				$is_admin = true;
			}
		}
		return $is_admin;
	}
	
	function _set_autologin($user_id)
	{
		$session_id = random_uuid();
		set_cookie('sg_auth_cookie', $session_id, -1, get_site_host(), get_site_path(), '');
		$this->user_session->set_session($user_id, $session_id);
	}
	
	function _delete_autologin()
	{
		$user_id = $this->get_uuid();
		if ( $user_id != null ) {
			$this->user_session->clear_sessions($user_id);
			delete_cookie('sg_auth_cookie', get_site_host(), get_site_path(), '' );
		}
	}

	function _get_autologin_cookie()
	{
		$cookie = get_cookie('sg_auth_cookie');
		if ( $cookie !== false ) {
			return $cookie;
		} else {
			return null;
		}
	}

	function _check_autologin()
	{
		$session_id = $this->_get_autologin_cookie();
		if ( $session_id == null ) {
			return null;
		}
		$user_id = $this->user_session->get_from_session_id($session_id);
		return $user_id;
	}

	function _login_session($user_id, $remember)
	{
		$this->_set_uuid($user_id);
		if ( $remember ) {
			$this->_set_autologin($user_id);
		}
	}

	function get_uuid()
	{
		$user_id = $this->session->userdata('SG_UserID');
		if ( $user_id === false ) {
			return null;
		} else {
			return $user_id;
		}
	}
	
	function _set_uuid($uuid)
	{
		if ( $uuid === null ) {
			$this->session->unset_userdata('SG_UserID');
		} else {
			$this->session->set_userdata('SG_UserID', $uuid);
		}
	}

	function _login_finish($user_id, $remember=false) {
		if ( $user_id === null ) {
			return false;
		} else {
			if ( $this->is_banned($user_id) ) {
				return false;
			} else {
				$this->_login_session($user_id, $remember);
				return true;
			}
		}
	}
	
	function login_facebook($fb_id)
	{
		if ( empty($fb_id) ) {
			return false;
		}
		if ( $this->is_logged_in() ) {
			return true;
		}

		$user_id = $this->simiangrid->auth_facebook($fb_id);
		return $this->_login_finish($user_id);
	}
	
	function login_openid($openid)
	{
		if ( empty($openid) ) {
			return false;
		}
		if ( $this->is_logged_in() ) {
			return true;
		}
		
		$user_id = $this->simiangrid->auth_openid($openid);

		return $this->_login_finish($user_id);
	}

	function login($username, $password, $remember = false)
	{
		if ( empty($username) || empty($password) ) {
			return false;
		}
		if ( $this->is_logged_in() ) {
			return true;
		}
		
		$user_id = $this->simiangrid->auth_user($username, $password);
		return $this->_login_finish($user_id, $remember);
	}
	
	function logout()
	{
		$this->_delete_autologin();
		$this->_set_uuid(null);
		$this->session->sess_destroy();
	}
	
	function register($username, $password, $email, $avtype)
	{
		$user_id = $this->simiangrid->register($username, $email);
		if ( $user_id != null ) {
			if ( $this->simiangrid->identity_set($user_id, 'md5hash', $username, '$1$' . md5($password)) ) {
				if ( $this->simiangrid->create_avatar($user_id, $avtype) ) {
					return $user_id;
				}
			}
		}
		return null;
	}

	function is_searchable($user_id)
	{
		$result = false;
		$user = $this->simiangrid->get_user($user_id);

		if ( $user != null ) {
			$uuid = $user['UserID'];
			$result = false;
	        if ( $this->is_admin() ) {
				$result = true;
			} else if ( isset($user['LLAbout']) && isset($user['LLAbout']['AllowPublish']) ) {
	            if ( $user['LLAbout']['AllowPublish'] ) {
					$result = true;
	            }
	        }
		}
		return $result;
	}
	
	function openid_exists($openid)
	{
		$result = $this->simiangrid->get_identity($openid);
		if ( $result != null ) {
			if ( $result['Type'] == 'openid' ) {
				push_message(lang('sg_auth_openid_exists'), 'error');
				return true;
			}
		}
		return false;
	}
	
	function facebook_exists($fb_id)
	{		
		$result = $this->simiangrid->get_identity($fb_id);
		if ( $result != null ) {
			if ( $result['Type'] == 'facebook' ) {
				push_message(lang('sg_auth_facebook_exists'), 'error');
				return true;
			}
		}
		return false;
	}

	function _get_user_flags($user_id)
	{
		$user_data = $this->simiangrid->get_user($user_id, true);
		if ( ! empty($user_data['UserFlags']) ) {
			$user_flags = $user_data['UserFlags'];
		} else {
			$user_flags = array();
		}
		return $user_flags;
	}

	function _set_user_flag($user_id, $flag, $status)
	{
		$user_flags = $this->_get_user_flags($user_id);
		$user_flags[$flag] = $status;
		return $this->_set_user_flags($user_id, $user_flags);
	}
	
	function _set_user_flags($user_id, $user_flags)
	{
		return $this->simiangrid->set_user_data($user_id, 'UserFlags', json_encode($user_flags));
	}
	function ban_user($user_id)
	{
		return $this->_set_user_flag($user_id, 'Suspended', true);
	}
	
	function unban_user($user_id)
	{
		return $this->_set_user_flag($user_id, 'Suspended', false);
	}
	
	function is_banned($user_id)
	{
		$user_flags = $this->_get_user_flags($user_id);
		if ( empty($user_flags['Suspended']) ) {
			return false;
		} else {
			return $user_flags['Suspended'];
		}
	}
	
	function is_validated($user_id)
	{
		$user_flags = $this->_get_user_flags($user_id);
		if ( empty($user_flags['Validated']) ) {
			return false;
		} else {
			return $user_flags['Validated'];
		}
	}

	function reset_validation($user_id)
	{
		$this->user_validation->clear_validation($user_id);
		$code = random_uuid();
		$this->user_validation->set_code($user_id, $code);
		$user_flags = $this->_get_user_flags($user_id);
		$user_flags['Validated'] = false;
		
		$result = false;
		if ( $this->_set_user_flags($user_id, $user_flags) ) {
			$user = $this->simiangrid->get_user($user_id);
			$email = $user['Email'];
			if ( ! send_email($email, set_message('sg_auth_reset_subject', get_site_title()), set_message('sg_auth_reset_body', site_url("auth/validate/$code") ) ) ) {
				push_message(set_message('sg_email_fail', $email), 'error');
			} else {
				$result = true;
			}
		}
	}
	
	function set_valid($user_id)
	{
		$user_flags = $this->_get_user_flags($user_id);
		$user_flags['Validated'] = true;
		$this->user_validation->clear_validation($user_id);
		return $this->_set_user_flags($user_id, $user_flags);
	}
	
	function validate($code)
	{
		$user_id = $this->user_validation->check_code($code);
		$result = false;
		if ( $user_id != null ) {
			$result = $this->set_valid($user_id);
			$this->user_validation->clear_validation($user_id);
		}
		return $result;
	}
	
	function access_level_map()
	{
		return array(
			'0' => lang('sg_user_access_anon'),
			'1' => lang('sg_user_access_normal'),
			'200' => lang('sg_user_access_admin')
		);
	}
}
