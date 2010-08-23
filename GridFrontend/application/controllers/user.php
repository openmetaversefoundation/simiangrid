<?php

class User extends Controller {

	function User()
	{
		parent::Controller();
		$this->load->library('DX_Auth');
		$this->load->helper('form');
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
        if ( $this->dx_auth->is_logged_in() ) {
            $uuid = $this->getMyUUID();
            return redirect(base_url() . "index.php/user/view/$uuid", 'location');
        } else {
            redirect(base_url() . 'index.php/user/search');
        }
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
	
	function search()
	{
	    if ( $this->input->post('search') ) {
	        $name = $this->input->post('name');
	        $grid_user = get_grid_user_data('name', $name);
	        if ( $grid_user != null ) {
	            $uuid = $grid_user['UserID'];
	            if ( $this->dx_auth->is_admin() ) {
	                return redirect(base_url() . "index.php/user/view/$uuid", 'location');
	            }
	            if ( isset($grid_user['LLAbout']) && isset($grid_user['LLAbout']['AllowPublish']) ) {
	                if ( $grid_user['LLAbout']['AllowPublish'] ) {
	                    return redirect(base_url() . "index.php/user/view/$uuid", 'location');
	                }
	            }
	        }
	        return redirect(base_url() . "index.php/user/search", 'location');
	    } else {
	        parse_template('user/search');
	    }
	}
	
	function view($uuid)
	{
	    $this->uuid = $uuid;
	    $this->my_uuid = $this->getMyUUID();
	    parse_template('user/view');
	}
	
	function raw($uuid)
	{
	    $this->user_data = get_grid_user_data('id', $uuid);
	    $this->simple_page = true;
	    parse_template('user/raw');
	}
	
}
