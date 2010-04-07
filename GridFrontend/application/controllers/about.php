<?php

class About extends Controller {

	function About()
	{
		parent::Controller();	
	}
	
	function index()
	{
	    parse_template('about');
	}
}
