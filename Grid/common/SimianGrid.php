<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Simian grid services
 *
 * PHP version 5
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 * 3. The name of the author may not be used to endorse or promote products
 *    derived from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS OR
 * IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
 * OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
 * IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT
 * NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF
 * THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 *
 * @package    SimianGrid
 * @author     Mic Bowman
 * @copyright  Open Metaverse Foundation
 * @license    http://www.debian.org/misc/bsd.license  BSD License (3 Clause)
 * @link       http://openmetaverse.googlecode.com/
 */

require_once(BASEPATH . 'common/Config.php');
require_once(BASEPATH . 'common/Errors.php');
require_once(BASEPATH . 'common/Log.php');
require_once(BASEPATH . 'common/Interfaces.php');
require_once(BASEPATH . 'common/UUID.php');
require_once(BASEPATH . 'common/Vector3.php');
require_once(BASEPATH . 'common/Curl.php');

function decode_recursive_json($json)
{   
    if ( is_string($json) ) {
        $response = json_decode($json, TRUE);
        if ( $response === null || ! is_array($response) ) {
            return $json;
        }
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

function webservice_post($url, $params, $jsonRequest = FALSE)
{
    // Parse the RequestMethod out of the request for debugging purposes
    if (isset($params['RequestMethod']))
        $requestMethod = $params['RequestMethod'];
    else
        $requestMethod = '';
    
    if (empty($url))
    {
        log_message('error', "Canceling $requestMethod POST to an empty URL");
        return array('Message' => 'Web service URL is not configured');
    }
    
    if ($jsonRequest)
        $params = json_encode($params);

    // POST our query and fetch the response
    $curl = new Curl();
    $response = $curl->simple_post($url, $params);
    
    // JSON decode the response
    $response = decode_recursive_json($response);
    
    if (!isset($response))
        $response = array('Message' => 'Invalid or missing response');
    
    return $response;
}

function get_user($userID)
{
    log_message('info',"get user: $userID");

    $config =& get_config();
    $userService = $config['user_service'];
    
    $response = webservice_post($userService, array(
        'RequestMethod' => 'GetUser',
        'UserID' => $userID)
    );
    
    if (!empty($response['Success']) && !empty($response['User']))
        return $response['User'];
    
    return null;
}

function get_session($userID)
{
    $config =& get_config();
    $userService = $config['user_service'];
        
    $response = webservice_post($userService, array(
        'RequestMethod' => 'GetSession',
        'UserID' => $userID)
    );
    
    if (!empty($response['Success']))
        return $response;
    
    return null;
}

function add_session($userID, &$sessionID, &$secureSessionID)
{
    $config =& get_config();
    $userService = $config['user_service'];
    
    $response = webservice_post($userService, array(
        'RequestMethod' => 'AddSession',
        'UserID' => $userID)
    );
    
    if (!empty($response['Success']) &&
        UUID::TryParse($response['SessionID'], $sessionID) &&
        UUID::TryParse($response['SecureSessionID'], $secureSessionID))
    {
        return true;
    }
    
    return false;
}

function remove_session($sessionID)
{
    $config =& get_config();
    $userService = $config['user_service'];
    
    $response = webservice_post($userService, array(
        'RequestMethod' => 'RemoveSession',
        'SessionID' => $sessionID)
    );
    
    if (!empty($response['Success']))
        return true;
    
    return false;
}

function create_session($user_id, $session_id, $secure_session_id)
{
    $config =& get_config();
    $gridService = $config['grid_service'];
    $response = webservice_post($gridService, array(
        'RequestMethod' => 'AddSession',
        'UserID' => $user_id,
        'SessionID' => $session_id,
        'SecureSessionID' => $secure_session_id
    ));
    if (!empty($response['success'])) {
        return true;
    } else {
        return false;
    }
}

function create_user($user_id, $username, $email)
{
    $config =& get_config();
    $userService = $config['user_service'];

    $query = array(
        'RequestMethod' => 'AddUser',
        'UserID' => $user_id,
        'Name' => $username,
        'Email' => $email,
        'AccessLevel' => 0);

    $response = webservice_post($userService, $query);

    if (isset($response['Success']))
        return $response['Success'];

    return false;
}

function set_user_data($user_id, $key, $value)
{
    $config =& get_config();
    $userService = $config['user_service'];

    $query = array('RequestMethod' => 'AddUserData',
                   'UserID' => $user_id,
                   $key => $value);

    $response = webservice_post($userService, $query);

    if (isset($response['Success']))
        return $response['Success'];

    return false;
}

function add_map_tile($x, $y, $maptile)
{    
    $params = array(
        'X' => $x,
        'Y' => $y
    );
    $config =& get_config();
    $gridService = $config['grid_service'];
    $curl = new Curl($gridService);
    $result = $curl->multipart('Tile', 'image/jpeg', $maptile, $params);
    var_dump($result);
    return $result['Success'];
}

function refresh_map_tile($x, $y, $regionImage)
{
    $success = false;
    $curl = new Curl();
    $maptile = $curl->simple_get($regionImage);
    if ( $maptile ) {
        if ( ! add_map_tile($x, $y, $maptile) ) {
            log_message('warn', "Unable to upload map image from $regionImage");
        } else {
            $success = true;
        }
    } else {
        log_message('warn', "unable to fetch map image from $regionImage");
    }
    return $success;
}

function lookup_scene_by_id($sceneID)
{
    $config =& get_config();
    $gridService = $config['grid_service'];
    
    $response = webservice_post($gridService, array(
        'RequestMethod' => 'GetScene',
        'SceneID' => $sceneID,
        'Enabled' => '1')
    );
    
    if (!empty($response['Success']))
        return Scene::fromOSD($response);
    
    return null;
}

function lookup_scene_by_name($name)
{
    $config =& get_config();
    $gridService = $config['grid_service'];
    
    $response = webservice_post($gridService, array(
        'RequestMethod' => 'GetScenes',
        'NameQuery' => $name,
        'Enabled' => '1',
        'MaxNumber' => '1')
    );
    
    if (!empty($response['Success']) && is_array($response['Scenes']) && count($response['Scenes']) > 0)
        return Scene::fromOSD($response['Scenes'][0]);
    
    return null;
}

function lookup_scene_by_position($position, $findClosest = false)
{
    $config =& get_config();
    $gridService = $config['grid_service'];
    
    $response = webservice_post($gridService, array(
        'RequestMethod' => 'GetScene',
        'Position' => $position,
        'FindClosest' => ($findClosest ? '1' : '0'),
        'Enabled' => '1')
    );
    
    if (!empty($response['Success']))
        return Scene::fromOSD($response);
    
    return null;
}

function create_opensim_presence($scene, $userID, $circuitCode, $fullName, $appearance,
    $sessionID, $secureSessionID, $startPosition, $capsPath)
{
    return create_opensim_presence_full($scene->Address, $scene->Name, $scene->ID, $scene->MinPosition->X, $scene->MinPosition->Y, $userID, $circuitCode, $fullName, $appearance, $sessionID, $secureSessionID, $startPosition, $capsPath, null, null, null);
}

function create_opensim_presence_full($server_uri, $scene_name, $scene_uuid, $scene_x, $scene_y, $userID, $circuitCode, $fullName, $appearance, $sessionID, $secureSessionID, $startPosition, $capsPath, $client_ip, $service_urls, $tp_flags, $service_session_id)
{
    if (!ends_with($server_uri, '/'))
        $server_uri .= '/';
    $regionUrl = $server_uri . 'agent/' . $userID . '/';
    
    list($firstName, $lastName) = explode(' ', $fullName);

    $request = array(
        'agent_id' => $userID,
        'caps_path' => $capsPath,
        'child' => false,
        'circuit_code' => $circuitCode,
        'first_name' => $firstName,
        'last_name' => $lastName,
        'session_id' => $sessionID,
        'secure_session_id' => $secureSessionID,
        'start_pos' => (string)$startPosition,
        'destination_x' => $scene_x,
        'destination_y' => $scene_y,
        'destination_name' => $scene_name,
        'destination_uuid' => $scene_uuid,
	'packed_appearance' => $appearance,
        'appearance_serial' => 1,
	'teleport_flags' => (1 << 30), // marks this as via HG
        'child' => true
    );
    if ( $client_ip != null ) {
        $request['client_ip'] = $client_ip;
    }
    if ( $service_urls != null ) {
        $request['serviceurls'] = $service_urls;
    }
    if ( $service_session_id != null ) {
        $request['service_session_id'] = $service_session_id;
    }
    //    if ( $tp_flags != null ) {
    //        $request['teleport_flags'] = $tp_flags;
    //    }
    $response = webservice_post($regionUrl, $request, true);
    
    if (!empty($response['success']))
        return $response['success'];
    
    log_message('warn',"failed to create presence for $userID");
    
    $seedCapability = null;
    return false;
}

