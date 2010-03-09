<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function ends_with($str, $sub)
{
   return (substr($str, strlen($str) - strlen($sub)) == $sub);
}

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

function create_asset($assetID, $creatorID, $contentType, $filename)
{
    $CI =& get_instance();
    $CI->load->library('Curl');
    $CI->load->helper('path');
    
    $filename = rtrim(set_realpath($filename), '/');
    
    $params = array(
		'AssetID' => $assetID,
		'CreatorID' => $creatorID,
		'Asset' => "@$filename;type=$contentType"
    );
    
    echo 'Posting ' . $filename . ' to ' . $CI->config->item('asset_service') . '<br/>';
    
    $curl = new Curl();
    $curl->create($CI->config->item('asset_service'));
    $curl->option(CURLOPT_POST, TRUE);
    $curl->option(CURLOPT_POSTFIELDS, $params);
    $curl->http_method('post');
    $response = json_decode($curl->execute(), TRUE);
	
	if (!isset($response))
	    $response = array('Message' => 'Invalid or missing response. ' . $curl->error_string);
	
    return $response;
}

function import_asset_folder($folder)
{
    $uuidRegex = '/^([\w+\d+\s]+)\-([0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12})\.(\w+)$/';
    $mimeMap = array(
    	'bodypart' => 'application/vnd.ll.bodypart',
		'ogg' => 'application/ogg',
		'j2c' => 'image/x-j2c',
		'animation' => 'application/vnd.ll.animation',
		'clothing' => 'application/vnd.ll.clothing'
    );
    
    if (!ends_with($folder, '/'))
        $folder .= '/';
    
    $i = 0;
    
    if($handle = opendir($folder))
    {
        while($file = readdir($handle))
        {
            if(is_file($folder . $file))
            {
                echo 'Opening ' . $folder . $file . '<br/>';
                
                $parts = pathinfo($folder . $file);
                if(isset($mimeMap[$parts["extension"]]) && preg_match($uuidRegex, $file, $matches))
                {
                    $assetID = $matches[2];
                    $creatorID = '00000000-0000-0000-0000-000000000000';
                    $contentType = $mimeMap[$parts["extension"]];
                    
                    $response = create_asset($assetID, $creatorID, $contentType, $folder . $file);
                    
                    if (!empty($response['Success']))
                    {
                        echo 'Successfully imported ' . $file . '<br/>';
                        ++$i;
                    }
                    else
                    {
                        echo 'Failed to import ' . $file . ': ' . $response['Message'] . '<br/>';
                        return $i;
                    }
                }
            }
        }
    }
    else
    {
        echo 'Failed opening folder ' . $folder . '<br/>';
    }
    
    return $i;
}
