<?php

class User extends Controller {

	function User()
	{
		parent::Controller();
		$this->load->library('DX_Auth');
		$this->load->helper('form');
		$this->load->helper('simian_view_helper');
	}
	
	function getMyUUID()
	{
        $this->load->model('dx_auth/users', 'users');
        $dx_user_id = $this->dx_auth->get_user_id();
        $dx_user_query = $this->users->get_user_by_id($dx_user_id);
        $dx_user = $dx_user_query->row();
        if ( $dx_user != null ) {
            $uuid = $dx_user->user_id;
        } else {
            $uuid = null;
        }
        return $uuid;
	}

	function identities($uuid)
	{
	    $this->identities = get_identities($uuid);
	    $this->simple_page = true;
	    parse_template('user/identities');
	}

	function index()
	{
		parse_template('user/index');
	}

    function profile_pic($uuid)
    {
		if ( $this->config->item('use_imagick') && extension_loaded('imagick') ) {
	        $grid_user = get_grid_user_data('id', $uuid);
	        if ( isset($grid_user['LLAbout']) && isset($grid_user['LLAbout']['Image']) ) {
	            $image = get_texture_jpg($grid_user['LLAbout']['Image']);
	            if ( $image == null ){
	                return show_404($uuid);
	            } else {
	                header('Content-type: image/jpg');
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
	    $this->my_uuid = $this->getMyUUID();
	    $grid_user = get_grid_user_data('id', $uuid);
	    if ( $grid_user == null ) {
	        return show_404($uuid);
	    }
	    if ( isset($grid_user['LastLocation'] ) ) {
	        $last_scene_id = $grid_user['LastLocation']['SceneID'];
    	    if ( $last_scene_id != null ) {
    	        $this->last_scene = get_scene_info('id', $last_scene_id);
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

	function _is_searchable($user_id) {
		$result = false;
		$user = get_grid_user_data('id', $user_id);
		
		if ( $user != null ) {
			$uuid = $user['UserID'];
			$result = false;
	        if ( $this->dx_auth->is_admin() ) {
				$result = true;
			} else if ( isset($user['LLAbout']) && isset($user['LLAbout']['AllowPublish']) ) {
	            if ( $user['LLAbout']['AllowPublish'] ) {
					$result = true;
	            }
	        }
		}
		return $result;
	}

	function search()
	{
	    $this->simple_page = true;
	   	$this->user_list = array();
	    if ( $this->input->post('name') ) {
	        $name = $this->input->post('name');
	        $user_results = user_search($name);
			if ( $user_results != null ) {
				foreach ( $user_results as $user ) {
					if ( $this->_is_searchable($user['id']) ) {
						array_push($this->user_list, $user);
					}
				}
			}
	    }
		return parse_template('user/search_results');
	}
	
	function self()
	{
		$uuid = $this->getMyUUID();
		if ( $uuid == null ) {
			return redirect('user/search/' . $uuid, 'location');
		} else {
			return redirect('user/view/' . $uuid, 'location');
		}
	}

	function view($uuid, $extra=null)
	{
	    $this->uuid = $uuid;
	    $this->my_uuid = $this->getMyUUID();
		if ( $extra == "inline" ) {
			$this->simple_page = true;
	    }
		$user = get_grid_user_data('id', $uuid);
		$this->title = $user['Name'];
		parse_template('user/view');
	}
	
	function raw($uuid)
	{
	    $this->user_data = get_grid_user_data('id', $uuid);
	    $this->simple_page = true;
	    parse_template('user/raw');
	}

}
