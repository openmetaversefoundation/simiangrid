<?php

class Region extends Controller {

	function Region()
	{
		parent::Controller();
		$this->load->library('table');
		$this->load->library('SimianGrid');

		$this->load->helper('form');
		$this->load->helper('simian_view_helper');
		$this->load->helper('simian_facebook_helper');
	}

	function search()
	{
	    $this->simple_page = true;
	    if ( $this->input->post('name') ) {
	        $name = $this->input->post('name');
	        $this->scene_list = $this->simiangrid->search_scene($name);
	    } else {
	        $this->scene_list = array();
	    }
		return parse_template('region/search_results');
	}
	
	function view_coord()
	{    
	    $this->simple_page = true;
	    if ( $this->input->post('x') && $this->input->post('y') ) {
    	    $this->x = ($this->input->post('x') * 256) + 1;
    	    $this->y = ($this->input->post('y') * 256) + 1;
    	    $this->scene_data = $this->simiangrid->get_scene_by_pos($this->x, $this->y);
    	    $this->uuid = $this->scene_data['SceneID'];
    	    $this->_scene_extra_info($this->uuid);
    	}
	    parse_template('region/info');
	}
	
	function index()
	{
   	    if ( $this->config->item('map_x') ) {
   	        $this->x = $this->config->item('map_x');
   	    } else {
   	        $this->x = 1000;
   	    }

        if ( $this->config->item('map_y') ) {
            $this->y = $this->config->item('map_y');
        } else {
            $this->y = 1000;
        }
	   
        if ( $this->config->item('zoom') ) {
            $this->zoom = $this->config->item('zoom');
        } else {
            $this->zoom = 4;
        }
	    
	    if ( $this->config->item('tile_host') ) {
	        $this->tile_host = $this->config->item('tile_host');
	    } else {
	        $this->tile_host = "/Grid/map.php/";
	    }
	    parse_template('region/index');
	}
	
	function info($uuid, $extra=null)
	{
	    $this->uuid = $uuid;
	    $this->scene_data = $this->simiangrid->get_scene($uuid);
	    $this->_scene_extra_info($uuid);
	    
		if ( $extra == "inline" ) {
			if ( $this->input->post('is_search') !== null ) {
				$this->center_map = TRUE;
			}
			$this->simple_page = true;
		} else {
			$this->title = $this->scene_data['Name'];
			$this->simple_page = false;
		}
	    parse_template('region/info');
	}
	
	function _scene_extra_info($uuid)
	{    
	    $grid_user = $this->simiangrid->get_user($this->scene_data['ExtraData']['EstateOwner']);
	    $this->owner_name = $grid_user['Name'];
		$this->owner_id = $grid_user['UserID'];
	}
}
