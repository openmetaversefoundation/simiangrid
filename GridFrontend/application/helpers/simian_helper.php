<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function ends_with($str, $sub)
{
   return (substr($str, strlen($str) - strlen($sub)) == $sub);
}

function decode_recursive_json($json)
{   
    if ( is_string($json) ) {
        $response = json_decode($json, TRUE);
    } else if ( is_array($json) ) {
        $response = $json;
    } else {
        return $json;
    }
    if ( $response == null ) {
        return $json;
    }
    foreach ( $response as $key => $value ) {
        $response[$key] = decode_recursive_json($value);
    }
    return $response;
}

function get_texture_jpg($uuid)
{
    $CI =& get_instance();
    $CI->load->library('Curl');
    
    $image = $CI->curl->simple_get($CI->config->item('asset_service') . $uuid);
    
    if ( $image == null ) {
        return null;
    }
    
    $im = new imagick();
    $im->readImageBlob($image);
    
    $im->setImageFormat("jpeg");
    $im->scaleImage(200, 200, true);
    return $im;
}

function rest_post($url, $params)
{
    $CI =& get_instance();
    $CI->load->library('Curl');
    
	$response = $CI->curl->simple_post($url, $params);
	
	$response = decode_recursive_json($response);
	
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
    $uuidRegex = '/^([\w+\d+\s]+\-\s*?)??([0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12})\.(\w+)$/';
    $mimeMap = array(
    	'bodypart' => 'application/vnd.ll.bodypart',
		'ogg' => 'application/ogg',
		'j2c' => 'image/x-j2c',
		'animation' => 'application/vnd.ll.animation',
		'clothing' => 'application/vnd.ll.clothing',
		'lsl' => 'application/vnd.ll.lsltext'
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

function get_identities($uuid)
{
    $CI =& get_instance();
    
    $query = array(
        'RequestMethod' => 'GetIdentities',
        'UserID' => $uuid
    );
    
    $response = rest_post($CI->config->item('grid_service'), $query);
	if (element('Success', $response) && is_array($response['Identities'])) {
	    return $response['Identities'];
	} else {
		return;
	}
}

function get_scene_info($request, $thing)
{
    $CI =& get_instance();
    
    if ( $request == "id" ) {
        $query = array(
            'RequestMethod' => 'GetScene',
            'SceneID' => $thing
        );
    } else if ( $request == "pos" ) {
        $query = array(
            'RequestMethod' => 'GetScene',
            'Position' => $thing
        );
    } else if ( $request == "name" ) {
        $query = array(
            'RequestMethod' => 'GetScene',
            'Name' => $thing
        );
    } else {
        return null;
    }
    
    $response = rest_post($CI->config->item('grid_service'), $query);
	if (element('Success', $response) ) {
		return $response;
	} else {
		return;
	}
}

function region_search($request, $thing)
{
    $CI =& get_instance();
    
    if ( $request == "name" ) {
        $query = array(
            'RequestMethod' => 'GetScenes',
            'NameQuery' => $thing
        );
    } else {
        return null;
    }
    $response = rest_post($CI->config->item('grid_service'), $query);
    if ( is_array($response['Scenes']) ) {
        $result = array();
        foreach ( $response['Scenes'] as $scene ) {
            $this_result = array(
                'name' => $scene['Name'],
                'id' => $scene['SceneID']
            );
            array_push($result, $this_result);
        }
        return $result;
    }
    return null;
}

function get_grid_user_data($key, $value)
{
	$CI =& get_instance();

	// Fetch account data for this user
	if ($key == 'id')
	{
		$query = array(
			'RequestMethod' => 'GetUser',
			'UserID' => $value
		);
	}
	elseif ($key == 'name')
	{
		$query = array(
			'RequestMethod' => 'GetUser',
			'Name' => $value
		);
	}
	else
	{
		return;
	}

	$response = rest_post($CI->config->item('user_service'), $query);

	if (element('Success', $response) && is_array($response['User']))
	{ 
		return $response['User'];
	}
	else
	{
		return;
	}
}

function _suspend_simiangrid_user($userID)
{   
    $CI =& get_instance();

    $query = array(
        'RequestMethod' => 'AddUserData',
        'UserID' => $userID,
        'Suspended' => 1
    );

    $response = rest_post($CI->config->item('user_service'), $query);

    if (element('Success', $response))
        return true;
    log_message('error', "Failed to suspend user $userID: " .  element('Message', $response, 'Unknown error'));
    return false;
}

function _unsuspend_simiangrid_user($userID)
{       
    $CI =& get_instance();

    $query = array(
        'RequestMethod' => 'RemoveUserData',
        'UserID' => $userID,
        'Key' => 'Suspended'
    );

    $response = rest_post($CI->config->item('user_service'), $query);

    if (element('Success', $response))
        return true;
    log_message('error', "Failed to unsuspend user $userID: " .  element('Message', $response, 'Unknown error'));
    return false;
}
