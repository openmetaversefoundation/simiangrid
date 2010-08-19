<?php

class Region extends Controller {

	function Region()
	{
		parent::Controller();
		$this->load->library('DX_Auth');
		$this->load->helper('form');
		$this->load->library('table');
	}

	function search()
	{
	    $this->simple_page = true;
	    if ( $this->input->post('name') ) {
	        $name = $this->input->post('name');
	        $this->scene_list = region_search("name", $name);
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
    	    $this->scene_data = get_scene_info('pos', "<$this->x, $this->y, 0>");
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
	        $this->tile_host = "/Grid/map/";
	    }
	    parse_template('region/index');
	}
	
	function info($uuid)
	{
	    $this->uuid = $uuid;
	    $this->scene_data = get_scene_info('id', $uuid);
	    $this->_scene_extra_info($uuid);
	    $this->simple_page = true;
	    parse_template('region/info');
	}
	
	function _scene_extra_info($uuid)
	{    
	    $grid_user = get_grid_user_data('id', $this->scene_data['ExtraData']['EstateOwner']);
	    $this->owner_name = $grid_user['Name'];
	}
}
