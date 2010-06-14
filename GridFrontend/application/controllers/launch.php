<?php

class Launch extends Controller {

	function Launch()
	{
		parent::Controller();	
	}
	
	function index()
	{
	    $data = array();
	    
	    // TODO: Support capability generation and name retrieval for logged in sessions
	    // TODO: Allow region to be passed in as a parameter (sanitize!)
        $data['login_url'] = $this->config->item('login_service');
	    //$data['name'] = '';
	    //$data['region'] = '';
	    
        header("Content-Type: application/calm+json");
	    $this->parser->parse('launch', $data);
	}
}
