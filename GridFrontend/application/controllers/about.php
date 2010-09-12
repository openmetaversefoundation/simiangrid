<?php

class About extends Controller {

	function About()
	{
		parent::Controller();
		$this->load->helper('simian_view_helper');
		$this->load->helper('simian_facebook_helper');	
	}
	
	function index()
	{
	    parse_template('about');
	}
}
