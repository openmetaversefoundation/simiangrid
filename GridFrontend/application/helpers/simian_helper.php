<?php

function rest_post($url, $params)
{
    $CI =& get_instance();
    $CI->load->library('Curl');
    
	$response = json_decode($CI->curl->simple_post($url, $params), TRUE);
	
	if (!isset($response))
	    $response = array('Message' => 'Invalid or missing response');
	
    return $response;
}

function parse_template($template, $data = array())
{
    $CI =& get_instance();
    
    $data['site_url'] = site_url();
	$data['base_url'] = base_url();
	
    $CI->parser->parse('header', $data);
    $CI->parser->parse($template, $data);
    $CI->parser->parse('footer', $data);
}

function random_uuid()
{
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff)
    );
}
