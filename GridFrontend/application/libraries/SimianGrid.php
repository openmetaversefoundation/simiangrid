<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class SimianGrid
{
	var $_user_cache;

	function SimianGrid()
	{
		$this->ci =& get_instance();
		
		$this->_user_cache = array();
		
		log_message('debug', 'SimianGridClient Initialized');
		
	    $this->ci->load->library('Curl');
		
		$this->_init();
	}
	
	function _init()
	{
		$this->grid_service = $this->ci->config->item('grid_service');
		$this->user_service = $this->ci->config->item('user_service');
		$this->asset_service = $this->ci->config->item('asset_service');
	}

	function _rest_post($url, $params)
	{
		$response = $this->ci->curl->simple_post($url, $params);

		$response = decode_recursive_json($response);

		if (!isset($response))
		    $response = array('Message' => 'Invalid or missing response');

	    return $response;
	}

	function search_user($thing)
	{
		$query = array(
		    'RequestMethod' => 'GetUsers',
		    'NameQuery' => $thing
		);
	    $response = $this->_rest_post($this->user_service, $query);
	    if ( is_array($response['Users']) ) {
	        $result = array();
	        foreach ( $response['Users'] as $user ) {
	            $this_result = array(
	                'name' => $user['Name'],
	                'id' => $user['UserID']
	            );
	            array_push($result, $this_result);
	        }
	        return $result;
	    }
	    return null;
	}
	
	function user_delete($user_id)
	{
		$query = array(
			'RequestMethod' => 'RemoveUser',
			'UserID' => $user_id
		);
		
		$response = $this->_rest_post($this->user_service, $query);
        if (element('Success', $response)) {
            return true;
        } else {
			return false;
		}
	}
	
	function get_user($user_id, $force = false)
	{
		if ( $force || empty($this->_user_cache[$user_id]) ) {
			$user = $this->_user_get('id', $user_id);
			$this->_user_cache[$user_id] = $user;
			return $user;
		} else {
			return $this->_user_cache[$user_id];
		}
	}

	function get_user_by_name($name)
	{
		$user = $this->_user_get('name', $name);
		if ( $user != null ) {
			$this->_user_cache[$user['UserID']] = $user;
		}
		return $user;
	}

	function get_user_by_email($email)
	{
		$user = $this->_user_get('email', $email);
		if ( $user != null ) {
			$this->_user_cache[$user['UserID']] = $user;
		}
		return $user;
	}
	
	function user_id_from_name($name)
	{
		$user = $this->get_user_by_name($name);
		if ( $user === null ) {
			return null;
		} else {
			return $user['UserID'];
		}
	}

	function is_user($user_id)
	{
		$user = $this->user_get($user_id);
		if ( $user == null ) {
			return false;
		} else {
			return true;
		}
	}

	function _user_get($type, $thing)
	{
		// Fetch account data for this user
		if ($type == 'id') {
			$query = array(
				'RequestMethod' => 'GetUser',
				'UserID' => $thing
			);
		} elseif ($type == 'name') {
			$query = array(
				'RequestMethod' => 'GetUser',
				'Name' => $thing
			);
		} elseif ( $type =='email' ) {
			$query = array(
				'RequestMethod' => 'GetUser',
				'Email' => $thing
			);
		} else {
			return;
		}

		$response = $this->_rest_post($this->user_service, $query);

		if (element('Success', $response) && is_array($response['User'])) { 
			return $response['User'];
		} else {
			return;
		}
	}

	function get_user_identities($thing)
	{
		return $this->_get_user_identities('id', $thing);
	}
	
	function get_user_identities_by_name($thing)
	{
		return $this->_get_user_identities('name', $thing);
	}
	
	function _get_user_identities($type, $thing)
	{
		if ( $type == 'id' ) {
		    $query = array(
		        'RequestMethod' => 'GetIdentities',
		        'UserID' => $thing
		    );
		} else if ( $type == 'name' ) {
			$query = array(
				'RequestMethod' => 'GetIdentities',
				'Identifier' => $thing
			);
		}

	    $response = $this->_rest_post($this->user_service, $query);
		if (element('Success', $response) && is_array($response['Identities'])) {
			return $response['Identities'];
		} else {
			return null;
		}
	}
	
	function auth_user($username, $password)
	{
		return $this->authorize_identity('md5hash', $username, '$1$' . md5($password));
	}
	
	function auth_openid($openid)
	{
		return $this->authorize_identity('openid', $openid);
	}
	
	function auth_facebook($token)
	{
		return $this->authorize_identity('facebook', $token);
	}

	function authorize_identity($type, $identifier, $credential='') {
	    $query = array(
        	'RequestMethod'  => 'AuthorizeIdentity',
        	'Identifier'     => $identifier,
	        'Credential'     => $credential,
	        'Type'           => $type
        );

        $response = $this->_rest_post($this->user_service, $query);

		if (element('Success', $response, false) ) {
			return element('UserID', $response);
		} else {
			return null;
		}
	}

	function set_user_data($user_id, $key, $value)
	{
		$query = array(
			'RequestMethod' => 'AddUserData',
			'UserID' => $user_id,
			'Key' => $key,
			'Value' => $value
		);
		$this->_rest_post($this->user_service, $query);
	}

	function remove_user_data($user_id, $key)
	{
		$query = array(
			'RequestMethod' => 'RemoveUserData',
			'UserID' => $user_id,
			'Key' => $key
		);
		$this->_rest_post($this->user_service, $query);
	}

	function search_scene($name) {
		return $this->_search_scene('name', $name);
	}

	function _search_scene($request, $thing)
	{
	    if ( $request == "name" ) {
	        $query = array(
	            'RequestMethod' => 'GetScenes',
	            'NameQuery' => $thing
	        );
	    } else {
	        return null;
	    }
	    $response = $this->_rest_post($this->grid_service, $query);
	    if ( is_array($response['Scenes']) ) {
	        $result = array();
	        foreach ( $response['Scenes'] as $scene ) {
	            $this_result = array(
	                'name' => $scene['Name'],
	                'id' => $scene['SceneID'],
					'x' => $scene['MinPosition'][0] / 256,
					'y' => $scene['MinPosition'][1] / 256
	            );
	            array_push($result, $this_result);
	        }
	        return $result;
	    }
	    return null;
	}
	
	function get_scene($scene_id)
	{
		return $this->_get_scene_info('id', $scene_id);
	}
	
	function get_scene_by_name($name)
	{
		return $this->_get_scene_info('name', $name);
	}
	
	function get_scene_by_pos($x, $y)
	{
		return $this->_get_scene_info('pos', "<$x, $y, 0>");
	}

	function _get_scene_info($request, $thing)
	{
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

	    $response = $this->_rest_post($this->grid_service, $query);
		if (element('Success', $response) ) {
			return $response;
		} else {
			return;
		}
	}
	
	function get_texture($uuid, $x = null, $y = null)
	{   
	    $image = $this->ci->curl->simple_get($this->asset_service . $uuid);

	    if ( $image == null ) {
	        return null;
	    }

	    $im = new imagick();
	    $im->readImageBlob($image);

	    $im->setImageFormat("jpeg");
		if ( $x != null && $y != null ) {
	    	$im->scaleImage(200, 200, true);
		}
	    return $im;
	}

	function register($username, $email)
	{
		$userid = random_uuid();
		$query = array(
		    'RequestMethod' => 'AddUser',
		    'UserID' => $userid,
			'Name' => $username,
		    'Email' => $email
		);
		$response = $this->_rest_post($this->user_service, $query);
		if (element('Success', $response)) {
			return $userid;
		} else {
			return null;
		}
	}

	function identity_set($user_id, $type, $identifier, $credential='')
	{
		$query = array(
    	    'RequestMethod' => 'AddIdentity',
		    'Identifier' => $identifier,
		    'Credential' => $credential,
		    'Type' => $type,
    	    'UserID' => $user_id
        );

		$response = $this->_rest_post($this->user_service, $query);
        
        if (element('Success', $response)) {
            return true;
		} else {
			return false;
		}
	}
	
	function identity_remove($user_id, $type, $identifier)
	{
		$query = array(
			'RequestMethod' => 'RemoveIdentity',
			'Type' => $type,
			'Identifier' => $identifier,
			'UserID' => $user_id
		);
		
		$response = $this->_rest_post($this->user_service, $query);

        if (element('Success', $response)) {
            return true;
		} else {
			return false;
		}
	}
	
	function create_avatar($user_id, $avtype)
	{
		$query = array(
        	'RequestMethod' => 'AddInventory',
	    	'AvatarType' => $avtype,
        	'OwnerID' => $user_id
        );
        
        $response = $this->_rest_post($this->user_service, $query);
        
        if (element('Success', $response)) {
            return true;
        } else {
			return false;
		}
	}

}
?>