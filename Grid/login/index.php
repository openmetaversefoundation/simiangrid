<?php
/** Simian grid services
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
 * @author     Jim Radford <http://www.jimradford.com/>
 * @copyright  Open Metaverse Foundation
 * @license    http://www.debian.org/misc/bsd.license  BSD License (3 Clause)
 * @link       http://openmetaverse.googlecode.com/
 */

define('BASEPATH', str_replace("\\", "/", realpath(dirname(__FILE__) . '/..') . '/'));

require_once(BASEPATH . 'common/Config.php');
require_once(BASEPATH . 'common/Errors.php');
require_once(BASEPATH . 'common/Log.php');
require_once(BASEPATH . 'common/Interfaces.php');
require_once(BASEPATH . 'common/UUID.php');
require_once(BASEPATH . 'common/Vector3.php');
require_once(BASEPATH . 'common/Curl.php');
require_once(BASEPATH . 'common/Scene.php');
require_once(BASEPATH . 'common/SceneLocation.php');
require_once(BASEPATH . 'common/Session.php');

///////////////////////////////////////////////////////////////////////////////
// XML-RPC Server

$xmlrpc_server = xmlrpc_server_create();
xmlrpc_server_register_method($xmlrpc_server, "login_to_simulator", "process_login");

$request_xml = file_get_contents("php://input");

$response = xmlrpc_server_call_method($xmlrpc_server, $request_xml, '');

header('Content-Type: text/xml');
echo $response;

xmlrpc_server_destroy($xmlrpc_server);
exit();

///////////////////////////////////////////////////////////////////////////////

function make_seed()
{
    list ($usec, $sec) = explode(' ', microtime());
    return (float)$sec + ((float)$usec * 100000);
}

function ends_with($str, $sub)
{
   return (substr($str, strlen($str) - strlen($sub)) == $sub);
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
    
    log_message('debug', sprintf('Response received from %s POST to %s: %s', $requestMethod, $url, $response));
    
    // JSON decode the response
    $response = json_decode($response, TRUE);
	
	if (!isset($response))
	    $response = array('Message' => 'Invalid or missing response');
	
    return $response;
}

function authorize_identity($name, $passHash)
{
    $config =& get_config();
    $userService = $config['user_service'];
    
    $userID = NULL;
    
    $response = webservice_post($userService, array(
    	'RequestMethod' => 'AuthorizeIdentity',
    	'Identifier' => $name,
    	'Credential' => $passHash,
    	'Type' => 'md5hash')
    );
    
    if (!empty($response['Success']))
        UUID::TryParse($response['UserID'], $userID);
    
    return $userID;
}

function get_user($userID)
{
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

function inform_scene_of_logout($sceneID, $userID)
{
    // FIXME: Implement this
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
    
    if (!empty($response['Success']) && is_array($response['Scenes']))
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

function get_inventory($userID, &$rootFolderID, &$items)
{
    $config =& get_config();
    $inventoryService = $config['inventory_service'];
    
    // This is always true in SimianGrid
    $rootFolderID = $userID;
    
    $response = webservice_post($inventoryService, array(
    	'RequestMethod' => 'GetInventoryNode',
        'ItemID' => $rootFolderID,
        'OwnerID' => $userID,
        'IncludeFolders' => '1',
        'IncludeItems' => '0',
        'ChildrenOnly' => '0')
    );
    
    if (!empty($response['Success']) && is_array($response['Items']))
    {
        $items = $response['Items'];
        return true;
    }
    
    $items = null;
    return false;
}

function find_start_location($start, $lastLocation, $homeLocation, &$scene, &$startPosition, &$startLookAt)
{
    $scene = null;
    
    if (strtolower($start) == "last")
    {
        if (isset($lastLocation))
        {
            log_message('debug', sprintf("Finding start location (last) for '%s'", $lastLocation->SceneID));
            
            $scene = lookup_scene_by_id($lastLocation->SceneID);
            if (isset($scene))
            {
                $startPosition = $lastLocation->Position;
                $startLookAt = $lastLocation->LookAt;
                return true;
            }
        }
    }
    else if (strtolower($start) == "home")
    {
        if (isset($homeLocation))
        {
            log_message('debug', sprintf("Finding start location (home) for '%s'", $homeLocation->SceneID));
            
            $scene = lookup_scene_by_id($homeLocation->SceneID);
            if (isset($scene))
            {
                $startPosition = $homeLocation->Position;
                $startLookAt = $homeLocation->LookAt;
                return true;
            }
        }
    }
    else if (preg_match('/^([a-zA-Z0-9\s]+)\/?(\d+)?\/?(\d+)?\/?(\d+)?$/', $start, $matches))
    {
        log_message('debug', sprintf("Finding start location (custom: %s) for '%s'", $start, $matches[1]));
        
        $scene = lookup_scene_by_name($matches[1]);
        if (isset($scene))
        {
            // FIXME: Parse starting position out of the request
            $startPosition = new Vector3(
                ($scene->MinPosition->X + $scene->MaxPosition->X) / 2,
                ($scene->MinPosition->Y + $scene->MaxPosition->Y) / 2,
                25);
            $startLookAt = new Vector3(1, 0, 0);
            return true;
        }
    }
    
    // Last resort lookup
    $position = Vector3::Zero();
    log_message('debug', sprintf("Finding start location (any) for '%s'", $position));
    
    $scene = lookup_scene_by_position($position, true);
    if (isset($scene))
    {
        $startPosition = new Vector3(
            (($scene->MinPosition->X + $scene->MaxPosition->X) / 2) - $scene->MinPosition->X,
            (($scene->MinPosition->Y + $scene->MaxPosition->Y) / 2) - $scene->MinPosition->Y,
            25);
        $startLookAt = new Vector3(1, 0, 0);
        
        return true;
    }
    
    return false;
}

function add_wearable(&$wearables, $appearance, $wearableName)
{
    $uuid = null;
    
    // ItemID
    if (isset($appearance[$wearableName . 'Item']) && UUID::TryParse($appearance[$wearableName . 'Item'], $uuid))
        $wearables[] = $uuid;
    else
        $wearables[] = UUID::Zero;
    
    // AssetID
    if (isset($appearance[$wearableName . 'Asset']) && UUID::TryParse($appearance[$wearableName . 'Asset'], $uuid))
        $wearables[] = $uuid;
    else
        $wearables[] = UUID::Zero;
}

function create_opensim_presence($scene, $userID, $circuitCode, $fullName, $appearance, 
    $sessionID, $secureSessionID, $startPosition, &$seedCapability)
{
    $regionBaseUrl = $scene->Address;
    if (!ends_with($regionBaseUrl, '/'))
        $regionBaseUrl .= '/';
    $regionUrl = $regionBaseUrl . 'agent/' . $userID . '/';
    
    list($firstName, $lastName) = explode(' ', $fullName);
    $capsPath = UUID::Random();
    
    $wearables = array();
    
    if (isset($appearance))
    {
        add_wearable($wearables, $appearance, 'Shape');
        add_wearable($wearables, $appearance, 'Skin');
        add_wearable($wearables, $appearance, 'Hair');
        add_wearable($wearables, $appearance, 'Eyes');
        add_wearable($wearables, $appearance, 'Shirt');
        add_wearable($wearables, $appearance, 'Pants');
        add_wearable($wearables, $appearance, 'Shoes');
        add_wearable($wearables, $appearance, 'Socks');
        add_wearable($wearables, $appearance, 'Jacket');
        add_wearable($wearables, $appearance, 'Gloves');
        add_wearable($wearables, $appearance, 'Undershirt');
        add_wearable($wearables, $appearance, 'Underpants');
        add_wearable($wearables, $appearance, 'Skirt');
    }
    
    $response = webservice_post($regionUrl, array(
    	'agent_id' => $userID,
    	'caps_path' => $capsPath,
    	'child' => false,
    	'circuit_code' => $circuitCode,
        'first_name' => $firstName,
        'last_name' => $lastName,
        'session_id' => $sessionID,
        'secure_session_id' => $secureSessionID,
        'start_pos' => (string)$startPosition,
        'appearance_serial' => 1,
        'destination_x' => $scene->MinPosition->X,
        'destination_y' => $scene->MinPosition->Y,
        'destination_name' => $scene->Name,
        'destination_uuid' => $scene->SceneID,
        'wearables' => $wearables
    ), true);
    
    if (!empty($response['success']))
    {
        // This is the hardcoded format OpenSim uses for seed capability URLs
        $seedCapability = $regionBaseUrl . 'CAPS/' . $capsPath . '0000/';
        return true;
    }
    
    $seedCapability = null;
    return false;
}

///////////////////////////////////////////////////////////////////////////////

function process_login($method_name, $params, $user_data)
{
    $config =& get_config();
    $userService = $config['user_service'];
    
    log_message('debug', "Processing new login request");
    
    $req = $params[0];
    $fullname = $req["first"] . ' ' . $req["last"];
    
    // Sanity check the request, make sure it's somewhat valid
    if (!isset($req["first"], $req["last"], $req["passwd"]) ||
        empty($req["first"]) || empty($req["last"]) || empty($req["passwd"]))
    {
        return array('reason' => 'key' , 'login' => 'false' , 'message' =>
        	"Login request must contain a first name, last name, and password and they cannot be blank");
    }
    
    // Authorize the first/last/password and resolve it to a user account UUID
    $userID = authorize_identity($fullname, $req['passwd']);
    if (empty($userID))
    {
        return array('reason' => 'key' , 'login' => 'false' , 'message' =>
        	"Sorry! We couldn't log you in.\nPlease check to make sure you entered the right\n    * Account name\n    * Password\nAlso, please make sure your Caps Lock key is off.");
    }
    
    log_message('debug', sprintf("Authorization success for %s", $userID));
    
    // Get information about the user account
    $user = get_user($userID);
    if (empty($user))
    {
        return array('reason' => 'key', 'login' => 'false', 'message' =>
        	"Sorry! We couldn't log you in. User account information could not be retrieved. If this problem persists, please contact the grid operator.");
    }
    
    $lastLocation = null;
    if (isset($user['LastLocation']))
        $lastLocation = SceneLocation::fromOSD($user['LastLocation']);
    
    $homeLocation = null;
    if (isset($user['HomeLocation']))
        $homeLocation = SceneLocation::fromOSD($user['HomeLocation']);
    
    log_message('debug', sprintf("User retrieval success for %s", $fullname));
    
    // Check for an existing session
    $existingSession = get_session($userID);
    
    if (!empty($existingSession))
    {
        log_message('debug', sprintf("Existing session %s found for %s in scene %s",
            $existingSession["SessionID"], $fullname, $existingSession["SceneID"]));
        
        $sceneID = null;
        if (UUID::TryParse($existingSession["SceneID"], $sceneID))
            inform_scene_of_logout($sceneID, $userID);
        
        if (!remove_session($userID))
        {
            log_message('warn', "Failed to remove session for " . $userID);
            return array('reason' => 'presence', 'login' => 'false',
        		'message' => "You are already logged in from another location. Please try again later.");
        }
    }
    else
    {
        log_message('debug', sprintf("No existing sessions found for %s", $fullname));
    }
    
    // Create a login session
    $sessionID = null;
    $secureSessionID = null;
    
    if (!add_session($userID, $sessionID, $secureSessionID))
    {
        return array('reason' => 'presence', 'login' => 'false',
        	'message' => "Failed to create a login session. Please try again later.");
    }
    
    log_message('debug', sprintf("Session creation success for %s (%s)", $fullname, $userID));
    
    // Find the starting scene for this user
    $scene = null;
    $startPosition = null;
    $startLookAt = null;
    
    if (!find_start_location($req['start'], $lastLocation, $homeLocation, $scene, $startPosition, $startLookAt) ||
        !isset($scene->ExtraData['ExternalAddress'], $scene->ExtraData['ExternalPort']))
    {
        return array('reason' => 'presence', 'login' => 'false',
        	'message' => "Error connecting to the grid. No suitable region to connect to.");
    }
    
    $lludpAddress = $scene->ExtraData['ExternalAddress'];
    $lludpPort = $scene->ExtraData['ExternalPort'];
    
    // Generate a circuit code
    srand(make_seed());
    $circuitCode = rand();
    
    // Prepare a login to the destination scene
    $seedCapability = NULL;
    if (!create_opensim_presence($scene, $userID, $circuitCode, $fullname, json_decode($user['LLAppearance'], TRUE),
        $sessionID, $secureSessionID, $startPosition, $seedCapability))
    {
        return array('reason' => 'presence', 'login' => 'false',
        	'message' => "Failed to establish a presence in the destination region. Please try again later.");
    }
    
    log_message('debug', sprintf("Presence creation success for %s (%s) in %s with seedcap %s",
        $fullname, $userID, $scene->Name, $seedCapability));
    
    // Build the response
    $response = array();
    $response['seconds_since_epoch'] = time();
    $response['login'] = 'true';
    $response['agent_id'] = (string)$userID;
    list($response['first_name'], $response['last_name']) = explode(' ', $fullname);
    $response['message'] = $config['message_of_the_day'];
    $response['udp_blacklist'] = $config['udp_blacklist'];
    $response['circuit_code'] = $circuitCode;
    $response['sim_ip'] = $lludpAddress;
    $response['sim_port'] = (int)$lludpPort;
    $response['seed_capability'] = $seedCapability;
    $response['region_x'] = (string)$scene->MinPosition->X;
    $response['region_y'] = (string)$scene->MinPosition->Y;
    $response['look_at'] = sprintf("[r%s, r%s, r%s]", $startLookAt->X, $startLookAt->Y, $startLookAt->Z);
    // TODO: If a valid $homeLocation is set, we should be pulling region_handle / position / lookat out of it
    $response['home'] = sprintf("{'region_handle':[r%s, r%s], 'position':[r%s, r%s, r%s], 'look_at':[r%s, r%s, r%s]}",
        $scene->MinPosition->X, $scene->MinPosition->Y,
        $startPosition->X, $startPosition->Y, $startPosition->Z,
        $startLookAt->X, $startLookAt->Y, $startLookAt->Z);
    $response['session_id'] = (string)$sessionID;
    $response['secure_session_id'] = (string)$secureSessionID;
    
    $req['options'][] = 'initial-outfit';
    for ($i = 0; $i < count($req['options']); $i++)
    {
        $option = str_replace('-', '_', $req['options'][$i]);
        
        if (file_exists("options/Class.$option.php"))
        {
            if (include_once("options/Class.$option.php"))
            {
                $instance = new $option($user);
                $response[$req["options"][$i]] = $instance->GetResults();
            }
            else
            {
               log_message('warn', "Unable to process login option: " . $option);
            }
        }
        else
        {
            log_message('debug', "Option " . $option . " not implemented.");
        }
    }
    
    $response["start_location"] = $req["start"];
    $response["agent_access"] = 'M';
    $response["agent_region_access"] = 'M';
    $response["agent_access_max"] = 'M';
    $response["agent_flags"] = 0;
    $response["ao_transition"] = 0;
    $response["inventory_host"] = "127.0.0.1";
    
    log_message('info', sprintf("Login User=%s %s Channel=%s Start=%s Platform=%s Viewer=%s id0=%s Mac=%s",
        $req["first"], $req["last"], $req["channel"], $req["start"], $req["platform"], $req["version"],
        $req["id0"], $req["mac"]));
    
    return $response;
}
