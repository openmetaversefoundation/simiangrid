<?php

class User extends Controller {

	function User()
	{
		parent::Controller();
		$this->load->library('Openid');
		$this->load->library('Form_validation');
		$this->load->library('table');

		$this->load->helper('form');
		$this->load->helper('simian_view_helper');
		$this->load->helper('simian_openid_helper');
		$this->load->helper('simian_facebook_helper');		
	}

	function _me_or_admin($uuid)
	{
		$my_uuid = $this->sg_auth->get_uuid();
		$is_admin = $this->sg_auth->is_admin();
		if ( $my_uuid == $uuid || $is_admin ) {
			return true;
		} else {
			return false;
		}
	}

	function identities($uuid, $action=null)
	{
		if ( ! $this->_me_or_admin($uuid) ) {
			return redirect('user/index');
		}
		if ( $action == 'remove' ) {
			return $this->_remove_identity($uuid);
		} elseif ( $action == 'add_openid' ) {
			return $this->_add_openid($uuid);
		} elseif ( $action == 'add_facebook' ) {
			return $this->_add_facebook($uuid);
		}
		return $this->_list_identities($uuid);
	}
	
	function _add_facebook($uuid)
	{
		if ( ! empty($_SERVER['QUERY_STRING']) ) {
			parse_str($_SERVER['QUERY_STRING'],$_GET); 
			if ( ! empty($_GET['code']) ) {
				$token = process_facebook_verification($_GET['code'], site_url("user/identities/$uuid/add_facebook"));
				$fb_id = facebook_get_id($this, $token);
				if ( ! $this->sg_auth->facebook_exists($fb_id) ) {
					if ( ! $this->simiangrid->identity_set($uuid, 'facebook', $fb_id) ) {
						push_message(lang('sg_auth_fb_error_assoc'), 'error');
					}
				}
			}
		}
		return redirect("user/view/$uuid");
	}
	
	function _add_openid($uuid)
	{
		$callback_url = site_url("user/identities/$uuid/add_openid");
		if ($this->input->post('action') == 'verify') {
			return openid_process_verify($this, $callback_url);
	    } else if ( $this->session->flashdata('openid_identifier') OR openid_check($this, $callback_url, $data) ) {
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
				if ( ! $this->simiangrid->identity_set($uuid, 'openid', $openid) ) {
					push_message(lang('sg_auth_open_error_assoc'), 'error');
				}
			}
		}
		return redirect("user/view/$uuid");
	}
	
	function _remove_identity($uuid)
	{
		$val = $this->form_validation;
		$val->set_rules('type', 'Type', 'trim|required|xss_clean');
		$val->set_rules('identifier', 'Identifier', 'trim|required|xss_clean');
		
		if ( $val->run() ) {
			$type = $val->set_value('type');
			$identifier = $val->set_value('identifier');
			if ( ! $this->simiangrid->identity_remove($uuid, $type, $identifier) ) {
				push_message(set_message('sg_auth_ident_remove_error', $type), 'error');
			}
		}
		return redirect("user/view/$uuid");
	}
	
	function _render_remove_identity($user_id, $type, $identifier)
	{
		$form = form_open(site_url("user/identities/$user_id/remove"));
		$form = $form . form_hidden('type', $type);
		$form = $form . form_hidden('identifier', $identifier);
		$form = $form . form_submit('remove','Remove', 'class="button"');
		$form = $form . form_close();
		return $form;
	}
	
	function _list_identities($uuid) {
		$this->uuid = $uuid;
	    $this->identities = $this->simiangrid->get_user_identities($uuid);
	    $this->simple_page = true;
	
		$this->table->set_heading(lang('sg_type'), lang('sg_user_identifier'), lang('sg_actions') );
		
		$this->has_openid = false;
		$this->has_facebook = false;
		
		foreach ( $this->identities as $identity ) {
			$type = $identity['Type'];
			$enabled = (bool) $identity['Enabled'];
			$real_identifier = $identity['Identifier'];
			$ident = $real_identifier;
			if ( $type == "openid" ) {
				$this->has_openid = true;
				$ident = "N/A";
			} elseif ( $type == "facebook" ) {
				$this->has_facebook = true;
			}
			if ( $type != "md5hash" ) {
				$actions = $this->_render_remove_identity($uuid, $type, $real_identifier);
			} else {
				$actions = '';
			}
			$this->table->add_row($type, $ident, $actions);
		}
	    return parse_template('user/identities');
	}

	function index()
	{
		parse_template('user/index');
	}

    function profile_pic($uuid)
    {
		if ( $this->config->item('use_imagick') && extension_loaded('imagick') ) {
	        $grid_user = $this->simiangrid->get_user($uuid);
	        if ( isset($grid_user['LLAbout']) && isset($grid_user['LLAbout']['Image']) ) {
	            $image = $this->simiangrid->get_texture($grid_user['LLAbout']['Image'], 200, 200);
	            if ( $image == null ){
	                return show_404($uuid);
	            } else {
	                header('Content-type: image/jpeg');
    	            echo $image;
	            }
        	}
		} else {
			return show_404($uuid);
		}
    }

	function profile($uuid)
	{
	    $this->user_id = $uuid;
	    $this->my_uuid = $this->sg_auth->get_uuid();
	    $grid_user = $this->simiangrid->get_user($uuid);
	    if ( $grid_user == null ) {
	        return show_404($uuid);
	    }
	    if ( isset($grid_user['LastLocation'] ) ) {
	        $last_scene_id = $grid_user['LastLocation']['SceneID'];
    	    if ( $last_scene_id != null ) {
    	        $this->last_scene = $this->simiangrid->get_scene($last_scene_id);
    	    }
	    }
	    $this->user_info = array(
	        'name' => $grid_user['Name'],
	        'email' => $grid_user['Email']
	    );
		if ( $this->config->item('use_imagick') && extension_loaded('imagick') ) {
		    if ( isset($grid_user['LLAbout'] ) ) {
		        $this->user_info['about'] = $grid_user['LLAbout']['About'];
		        if ( isset($grid_user['LLAbout']['Image']) ) {
		            $this->avatar_image = $uuid;
		        }
		    }
		}
	    $this->simple_page = true;  
	    parse_template('user/profile');
	}

	function search()
	{
	    $this->simple_page = true;
	   	$this->user_list = array();
	    if ( $this->input->post('name') ) {
	        $name = $this->input->post('name');
	        $user_results = $this->simiangrid->search_user($name);
			if ( $user_results != null ) {
				foreach ( $user_results as $user ) {
					if ( $this->sg_auth->is_searchable($user['id']) ) {
						array_push($this->user_list, $user);
					}
				}
			}
	    }
		return parse_template('user/search_results');
	}
	
	function self()
	{
		$uuid = $this->sg_auth->get_uuid();
		if ( $uuid == null ) {
			return redirect('user/', 'location');
		} else {
			return redirect('user/view/' . $uuid, 'location');
		}
	}

	function view($uuid, $extra=null)
	{	
		$user = $this->simiangrid->get_user($uuid);
		if ( $user == null ) {
			$user = $this->simiangrid->get_user_by_name($uuid);
			if ( $user != null ) {
				$this->uuid = $user['UserID'];
			} else {
				push_message(set_message('sg_user_not_found', $uuid), 'error');
				return redirect('user/');
			}
		} else {
	    	$this->uuid = $uuid;
		}
	    $this->my_uuid = $this->sg_auth->get_uuid();
		$this->tab = '';
		if ( $extra == "inline" ) {
			$this->simple_page = true;
	    } else if ( $extra == "actions" ) {
			$this->tab = 'actions';
		} else if ( $extra == 'identities' ) {
			$this->tab = 'identities';
		}
		$this->title = $user['Name'];
		parse_template('user/view');
	}
	
	function raw($uuid)
	{
		if ( ! $this->_me_or_admin($uuid) ) {
			return redirect('user/index');
		}
	    $this->user_data = $this->simiangrid->get_user($uuid);
		if ( $this->user_data == null ) {
			push_message(set_message('sg_user_not_found', $uuid), 'error');
			return redirect('user/');
		}
	    $this->simple_page = true;
	    parse_template('user/raw');
	}
	
	function _change_password($uuid)
	{	
		$success = false;
		
		$val = $this->form_validation;
		$val->set_rules('password', 'Password', 'trim|required|xss_clean|min_length[8]|max_length[30]');
		
		if ( $val->run() ) {
			$password = $val->set_value('password');
			$user_data = $this->simiangrid->get_user($uuid);
			if ( $this->simiangrid->identity_set($uuid, 'md5hash', $user_data['Name'], '$1$' . md5($password)) ) {
				if ( $this->simiangrid->identity_set($uuid, 'a1hash', $user_data['Name'], md5($user_data['Name'] . ':Inventory:' . $password)) ) {
					$success = true;
				}
			}
		}
		$result = json_encode(array('success'=>$success));
		echo $result;
		return;
	}
	
	function _style_selection($uuid)
	{
		$val = $this->form_validation;
		$val->set_rules('value', 'style_selection', 'trim|required|xss_clean|min_length[4]|max_length[30]');
		
		if ( $val->run() ) {
			$style = $val->set_value('value');
			$styles = $this->config->item('style_list');
			if ( isset($styles[$style]) ) {
				$this->user_settings->set_style($uuid, $style);
				echo pretty_style($style);
			} 
		} 
	}
	
	function _load_style($uuid)
	{
		$user = $this->simiangrid->get_user($uuid);
		if ( $user != null ) {
			echo json_style_list();
		}
	}
	
	function _change_access_level($uuid)
	{
		$val = $this->form_validation;
		$val->set_rules('value', 'access_level', 'trim|required|xss_clean|numeric');
		
		if ( $val->run() ) {
			$level = $val->set_value('value');
			$levels = $this->sg_auth->access_level_map();
			if ( ! empty($levels[$level]) ) {
				if ( $this->simiangrid->set_access_level($uuid, $level) ) {
					echo $levels[$level];
				} 
			}
		}
		return;
	}
	
	function _load_access_level($uuid)
	{
		$user = $this->simiangrid->get_user($uuid);
		if ( $user != null ) {
			echo json_access_levels($user['AccessLevel']);
		}
	}
	
	function _change_ban_status($uuid)
	{
		$val = $this->form_validation;
		$val->set_rules('value', 'access_level', 'trim|required|xss_clean');
		
		if ( $val->run() ) {
			$real_val = $val->set_value('value');
			
			if ( $real_val == 'true' ) {
				$status = true;
			} else if ( $real_val == 'false' ) {
				$status = false;
			} else {
				return;
			}
			if ( $status ) {
				$result = $this->sg_auth->ban_user($uuid);
			} else {
				$result = $this->sg_auth->unban_user($uuid);
			}
			if ( $result ) {
				if ( $status ) {
					echo lang('sg_auth_banned');
				} else {
					echo lang('sg_auth_not_banned');
				}
			}
		}
		return;
	}
	
	function _load_ban_status($uuid)
	{
		$ban_data = array(
			'true' => lang('sg_auth_banned'),
			'false' => lang('sg_auth_not_banned')
		);
		if ( $this->sg_auth->is_banned($uuid) ) {
			$ban_data['selected'] = 'true';
		} else {
			$ban_data['selected'] = 'false';
		}
		echo json_encode($ban_data);
	}
	
	function _change_validation_status($uuid)
	{
		$val = $this->form_validation;
		$val->set_rules('value', 'validation_status', 'trim|required|xss_clean');
		
		if ( $val->run() ) {
			$real_val = $val->set_value('value');
			
			if ( $real_val == 'true' ) {
				$status = true;
			} else if ( $real_val == 'false' ) {
				$status = false;
			} else {
				return;
			}
			if ( $status ) {
				$result = $this->sg_auth->set_valid($uuid);
			} else {
				$result = $this->sg_auth->reset_validation($uuid);
			}
			if ( $result ) {
				if ( $status ) {
					echo lang('sg_auth_validated');
				} else {
					echo lang('sg_auth_not_validated');
				}
			}
		}
		return;
	}

	function _load_validation_status($uuid)
	{
		$validation_data = array(
			'true' => lang('sg_auth_validated'),
			'false' => lang('sg_auth_not_validated')
		);
		if ( $this->sg_auth->is_validated($uuid) ) {
			$validation_data['selected'] = 'true';
		} else {
			$validation_data['selected'] = 'false';
		}
		echo json_encode($validation_data);
	}

	function actions($uuid, $action=null)
	{
		if ( ! $this->_me_or_admin($uuid) ) {
			return redirect('user/index');
		}
		$user = $this->simiangrid->get_user($uuid);
		if ( $user == null ) {
			push_message(set_message('sg_user_not_found', $uuid), 'error');
			return redirect('user/');
		}
		if ( $action == "change_password" ) {
			return $this->_change_password($uuid);
		} else if ( $action == "style_selection" ) {
			return $this->_style_selection($uuid);
		} else if ( $action == "load_style" ) {
			return $this->_load_style($uuid);
		} else if ( $action == "change_access_level" && $this->sg_auth->is_admin() ) {
			return $this->_change_access_level($uuid);
		} else if ( $action == "load_access_level" && $this->sg_auth->is_admin() ) {
			return $this->_load_access_level($uuid);
		} else if ( $action == "change_ban_status" && $this->sg_auth->is_admin() ) {
			return $this->_change_ban_status($uuid);
		} else if ( $action == "load_ban_status" ) {
			return $this->_load_ban_status($uuid);
		} else if ( $action == "change_validation_status" && $this->sg_auth->is_admin() ) {
			return $this->_change_validation_status($uuid);
		} else if ( $action == "load_validation_status" ) {
			return $this->_load_validation_status($uuid);
		} else {
			$this->user_id = $uuid;
			$this->user_data = $this->simiangrid->get_user($uuid);
		    $this->my_uuid = $this->sg_auth->get_uuid();
			$this->stylesheet = pretty_style(get_stylesheet());
			if ( $this->sg_auth->is_banned($uuid) ) {
				$this->banned = lang('sg_auth_banned');
			} else {
				$this->banned = lang('sg_auth_not_banned');
			}
			if ( $this->sg_auth->is_validated($uuid) ) {
				$this->validation = lang('sg_auth_validated');
			} else {
				$this->validation = lang('sg_auth_not_validated');
			}
		    $this->simple_page = true;
			return parse_template('user/actions');
		}
	}
}
