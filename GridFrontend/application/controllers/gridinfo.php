<?php

class GridInfo extends Controller {

	function GridInfo()
	{
		parent::Controller();   
	}
       
	function index()
	{
		header('Content-Type: application/xml');

		$data['user_service'] = $this->config->item('user_service');
		$data['base_url'] = $this->config->item('base_url');
		$data['grid_name'] = $this->config->item('grid_name');
		$data['grid_name_short'] = $this->config->item('grid_name_short');

		$this->parser->parse('gridinfo', $data);
	}
}
