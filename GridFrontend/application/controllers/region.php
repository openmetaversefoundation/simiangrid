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
		
		$this->lang->load('simian_grid', get_language() );
	}

	function search()
	{
		$data = array();
	    if ( $this->input->post('name') ) {
	        $name = $this->input->post('name');
	        $data['scene_list'] = $this->simiangrid->search_scene($name);
	    } else {
	        $data['scene_list'] = array();
	    }
		return parse_template('region/search_results', $data, true);
	}
	
	function view_coord()
	{    
	    $data = array();
	    if ( $this->input->post('x') && $this->input->post('y') ) {
    	    $data['x'] = ($this->input->post('x') * 256) + 1;
    	    $data['y'] = ($this->input->post('y') * 256) + 1;
    	    $data['scene_data'] = $this->simiangrid->get_scene_by_pos($data['x'], $data['y']);
    	    $data['uuid'] = $data['scene_data']['SceneID'];
			$data['inline'] = true;
    	    $this->_scene_extra_info($data['uuid'], $data);
    	}
	    return parse_template('region/details_popup', $data, true);
	}
	
	function _init_map(&$data)
	{
		if ( $this->config->item('map_x') ) {
   	        $data['x'] = $this->config->item('map_x');
   	    } else {
   	        $data['x'] = 1000;
   	    }

        if ( $this->config->item('map_y') ) {
            $data['y'] = $this->config->item('map_y');
        } else {
            $data['y'] = 1000;
        }
	   
        if ( $this->config->item('zoom') ) {
            $data['zoom'] = $this->config->item('zoom');
        } else {
            $data['zoom'] = 4;
        }
	    
	    if ( $this->config->item('tile_host') ) {
	        $data['tile_host'] = $this->config->item('tile_host');
	    } else {
	        $data['tile_host'] = "/Grid/map.php/";
	    }
	}

	function map()
	{
		$data = array();
		$this->_init_map($data);
		return parse_template('region/map', $data, true);
	}

	function index()
	{
		$data = array();
		$this->_init_map($data);
	    return parse_template('region/index', $data);
	}
	
	function view($uuid, $extra=null)
	{	
		$data = array();
	    $data['scene_data'] = $this->simiangrid->get_scene($uuid);
		if ( $data['scene_data'] == null ) {
			$data['scene_data'] = $this->simiangrid->get_scene_by_name($uuid);
			if ( $data['scene_data'] != null ) {
				$uuid = $data['scene_data']['SceneID'];
			} else {
				push_message(set_message('sg_region_unknown', $uuid), 'error');
				return redirect('region');
			}
		}
		$data['uuid'] = $uuid;
		$data['tab'] = '';
		if ( $extra == "stats" ) {
			$data['tab'] = 'stats';
		}
		$data['title'] = $data['scene_data']['Name'];
		parse_template('region/view', $data);
	}

	function details($uuid, $extra=null)
	{
		$data = array();
	    $data['scene_data'] = $this->simiangrid->get_scene($uuid);
	    $this->_scene_extra_info($uuid, $data);
	    
		$data['inline'] = false;
		if ( $extra == "inline" ) {
			if ( $this->input->post('is_search') !== null ) {
				$data['center_map'] = true;
			}
			return parse_template('region/details_popup', $data, true);
		} else {
	    	return parse_template('region/details', $data, true);
		}
	}
	
	function stats($uuid, $extra=null)
	{
		$data = array();
		$data['scene_id'] = $uuid;
	    $data['scene_data'] = $this->simiangrid->get_scene($uuid);
		$details = $this->simiangrid->simulator_details($uuid);
		if ( $details != null ) {
			$data['sim_details'] = $details;
			if ( $extra == 'feed' ) {
				$result = array(
					'sim_fps' => (float) $details['sim_fps'],
					'phys_fps' => (float) $details['phys_fps']
				);
				echo json_encode($result);
				return;
			}
		}
		return parse_template('region/stats', $data, true);
	}
	
	function _scene_extra_info($uuid, &$data)
	{    
	    $grid_user = $this->simiangrid->get_user($data['scene_data']['ExtraData']['EstateOwner']);
	    $data['owner_name'] = $grid_user['Name'];
		$data['owner_id'] = $grid_user['UserID'];
	}
}
