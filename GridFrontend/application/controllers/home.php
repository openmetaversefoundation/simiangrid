<?php

class Home extends Controller {

	function Home()
	{
		parent::Controller();	
	}
	
	function index()
	{
	    parse_template('home');
	}
	
	function import_assets()
	{
	    import_asset_folder($this->config->item('default_asset_folder'));
	}
}
