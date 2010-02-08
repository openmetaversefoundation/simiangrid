<?php

class Contact extends Controller {

	function Contact()
	{
		parent::Controller();	
	}
	
	function index()
	{
	    parse_template('contact');
	}
}
