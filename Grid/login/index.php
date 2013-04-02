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

$gStartTime = microtime(true);

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

define('LOGINPATH', BASEPATH . 'login/');
require_once(LOGINPATH . 'lib/Class.Appearance.php');

// Performance profiling/logging                                                                                                       
function shutdown()
{
    global $gStartTime;
    $elapsed = microtime(true) - $gStartTime;
    log_message('debug', "Executed login in $elapsed seconds");
}
register_shutdown_function('shutdown');

///////////////////////////////////////////////////////////////////////////////
// XML-RPC Server

$xmlrpc_server = xmlrpc_server_create();
xmlrpc_server_register_method($xmlrpc_server, "login_to_simulator", "process_login");

// Read the request
$request_xml = file_get_contents("php://input");

// Check for a login capability
$authUserID = NULL;
if ( ! empty($_GET['cap']))
    $authUserID = get_capability($_GET['cap']);
    
// Build the response 
$response = xmlrpc_server_call_method($xmlrpc_server, $request_xml, $authUserID);
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
    
    $options = array();
    if ($jsonRequest)
    {
        $params = json_encode($params);
	$options[CURLOPT_HTTPHEADER] = array('Content-Type: application/json');
    }

    // POST our query and fetch the response
    $curl = new Curl();
    $response = $curl->simple_post($url, $params, $options);
    
    // log_message('debug', sprintf('Response received from %s POST to %s: %s', $requestMethod, $url, $response));
    
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

function get_capability($capID)
{
    $config =& get_config();
    $userService = $config['user_service'];
    
    $response = webservice_post($userService, array(
        'RequestMethod' => 'GetCapability',
        'CapabilityID' => $capID)
    );
    
    if (!empty($response['Success']) && $response['Resource'] == 'login')
        return $response['OwnerID'];
    
    return null;
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

function get_user_by_name($userName)
{
    $config =& get_config();
    $userService = $config['user_service'];
    
    $response = webservice_post($userService, array(
        'RequestMethod' => 'GetUser',
        'Name' => $userName)
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
    if ($sceneID == UUID::Zero)
        return;
    
    $scene = lookup_scene_by_id($sceneID);
    
    if (isset($scene))
    {
        // FIXME: Implement this
        log_message('debug', "TODO: Need to contact " . print_r($scene, true) . " with an agent delete");
    }
    else
    {
        log_message('error', "Cannot inform scene $sceneID of logout, scene lookup failed");
    }
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
        'RequestMethod' => 'GetScene',
        'Name' => $name,
        'Enabled' => '1')
    );
    
    if (!empty($response['Success']))
        return Scene::fromOSD($response);
    
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

function get_inventory_items($userID, $folderID, $childrenOnly, &$items)
{
    $config =& get_config();
    $inventoryService = $config['inventory_service'];

    $response = webservice_post($inventoryService, array(
        'RequestMethod' => 'GetInventoryNode',
        'ItemID' => $folderID,
        'OwnerID' => $userID,
        'IncludeFolders' => '1',
        'IncludeItems' => '0',
        'ChildrenOnly' => $childrenOnly));

    if (! empty($response['Success']) && is_array($response['Items']))
    {
        $items = $response['Items'];
        return true;
    }

    $items = null;
    return false;
}

function get_inventory_folder_by_path($userID, &$folderID, $path)
{
    // check to see if we are done parsing the path
    if (! is_array($path) || count($path) == 0)
        return true;

    $folders = NULL;
    if (get_inventory_items($userID, $folderID, 1, $folders))
    {
        $pathelem = array_shift($path);
        foreach ($folders as $folder)
        {
            if ($folder['Name'] == $pathelem)
            {
                $folderID = $folder['ID'];
                return get_inventory_folder_by_path($userID, $folderID, $path);
            }
        }
    }

    return false;
}

function get_library_owner(&$ownerID)
{
    $config =& get_config();

    // Get the library owner ID
    if (! isset($config['library_owner_id']))
    {
        if (! isset($config['library_owner_name']))
        {
            log_message('warn','library owner not configured');
            return false;
        }

        $userName = $config['library_owner_name'];
        $userInfo = get_user_by_name($userName);
        if (! $userInfo)
        {
            log_message('warn',sprintf('library owner not found %s',$userName));
            return false;
        }
        $config['library_owner_id'] = $userInfo["UserID"];
    }

    $ownerID = $config['library_owner_id'];
    return true;
}

function get_library_root_folder($ownerID,&$rootFolderID)
{
    $config =& get_config();
    $rootFolderID = $ownerID;
    
    if (isset($config['library_folder_id']))
    {
        log_message('debug','[index] found library root in configuration');
        $rootFolderID = $config['library_folder_id'];
        return true;
    }

    if (isset($config['library_folder_path']))
    {
        $path = $config['library_folder_path'];
        $pathArray = preg_split('/\//',$path,-1,PREG_SPLIT_NO_EMPTY);

        // Sets $rootFolderID on return
        if (! get_inventory_folder_by_path($ownerID,$rootFolderID,$pathArray))
        {
            log_message('warn',sprintf('unable to resolve library %s',$path));
            return false;
        }
    }

    $config['library_folder_id'] = $rootFolderID;
    return true;
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

function get_friends($userID)
{
    $config =& get_config();
    $userService = $config['user_service'];
    
    $friends = array();
    
    // Load the list of friends and their granted permissions
    $response = webservice_post($userService, array(
        'RequestMethod' => 'GetGenerics',
        'OwnerID' => $userID,
        'Type' => 'Friend')
    );
    
    if (!empty($response['Success']) && is_array($response['Entries']))
    {
        $friendEntries = $response['Entries'];
        
        // Populate the friends array
        foreach ($friendEntries as $friendEntry)
        {
            $friendID = $friendEntry['Key'];
            $friends[$friendID] = array('buddy_rights_has' => 0, 'buddy_rights_given' => (int)$friendEntry['Value'], 'buddy_id' => $friendID);
        }
        
        // Load the permissions those friends have granted to this user
        $response = webservice_post($userService, array(
            'RequestMethod' => 'GetGenerics',
            'Key' => $userID,
            'Type' => 'Friend')
        );
        
        if (!empty($response['Success']) && is_array($response['Entries']))
        {
            $friendedMeEntries = $response['Entries'];
            
            foreach ($friendedMeEntries as $friendedMeEntry)
            {
                $friendID = $friendedMeEntry['OwnerID'];
                
                if (isset($friends[$friendID]))
                {
                    $friends[$friendID]['buddy_rights_has'] = $friendedMeEntry['Value'];
                }
            }
        }
        else
        {
            log_message('warn', "Failed to retrieve the reverse friends list for " . $userID . " from " . $userService . ": " . $response['Message']);
        }
    }
    else
    {
        log_message('warn', "Failed to retrieve the friends list for " . $userID . " from " . $userService . ": " . $response['Message']);
    }
    
    // Convert the friends associative array into a plain array
    $ret = array();
    foreach ($friends as $friend)
        $ret[] = $friend;
    
    return $ret;
}

function find_start_location($start, $lastLocation, $homeLocation, &$scene, &$startPosition, &$startLookAt)
{
    $config =& get_config();
    $defaultLocation = $config['default_location'];
    
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
    
    if (strtolower($start) == "home")
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
    
    if (preg_match('/^uri:([a-zA-Z0-9\s]+)&(\d+)&(\d+)&(\d+)$/', $start, $matches))
    {
        log_message('debug', sprintf("Finding start location (custom: %s) for '%s'", $start, $matches[1]));
        
        $scene = lookup_scene_by_name($matches[1]);
        if (isset($scene))
        {
            $startPosition = new Vector3($matches[2], $matches[3], $matches[4]);
            $startLookAt = new Vector3(1, 0, 0);
            return true;
        }
    }
    
    // Check to see if a valid default location has been set
    if (preg_match('/^([a-zA-Z0-9\s]+)\/(\d+)\/(\d+)\/(\d+)$/', $defaultLocation, $matches))
    {
        log_message('debug', sprintf("Finding start location (default: %s) for '%s'", $defaultLocation, $matches[1]));
        
        $scene = lookup_scene_by_name($matches[1]);
        if (isset($scene))
        {
            $startPosition = new Vector3($matches[2], $matches[3], $matches[4]);
            $startLookAt = new Vector3(1, 0, 0);
            return true;
        }
    }
    else
    {
        log_message('info', 'No valid default_location set');
    }
    
    // Last resort lookup
    $position = Vector3::Zero();
    log_message('debug', sprintf("Finding start location (any: %s) for '%s'", $start, $position));
    
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

function create_opensim_presence($scene, $userID, $circuitCode, $fullName, $appearance,
    $sessionID, $secureSessionID, $startPosition, &$seedCapability)
{
    // These configuration variables give you some control over opensim versions
    // send_packedapp should be false to support older opensim versions
    $config =& get_config();
    $sendPackedAppearance = isset($config['send_packedapp']) ? $config['send_packedapp'] : true;
    $sendWearables = isset($config['send_wearables']) ? $config['send_wearables'] : false;

    $regionBaseUrl = $scene->Address;
    if (!ends_with($regionBaseUrl, '/'))
        $regionBaseUrl .= '/';
    $regionUrl = $regionBaseUrl . 'agent/' . $userID . '/';
    
    list($firstName, $lastName) = explode(' ', $fullName);
    $capsPath = UUID::Random();
    
    log_message('warn','[LOGIN] create opensim session');

    $serviceurls = array(
        'GatekeeperURI' => $config['hypergrid_uri'],
        'HomeURI' => $config['hypergrid_uri'],
        'InventoryServerURI' => $config['hg_inventory_service'],
        'AssetServerURI' => $config['hg_asset_service'],
        'ProfileServerURI' => $config['hg_user_service'],
        'FriendsServerURI' => $config['hypergrid_uri'],
        'IMServerURI' => $config['hypergrid_uri']
    );
    
    $params = array(
                    'agent_id' => $userID,
                    'caps_path' => $capsPath,
                    'child' => false,
                    'circuit_code' => $circuitCode,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'session_id' => $sessionID,
                    'secure_session_id' => $secureSessionID,
                    'start_pos' => (string)$startPosition,
                    'destination_x' => $scene->MinPosition->X,
                    'destination_y' => $scene->MinPosition->Y,
                    'destination_name' => $scene->Name,
                    'destination_uuid' => $scene->SceneID,
                    'appearance_serial' => 1,
                    'teleport_flags' => 128,
                    'serviceurls' => $serviceurls
    );

    if ($sendPackedAppearance)
    {
        $params['packed_appearance'] = $appearance->GetPackedAppearance();
    }

    if ($sendWearables)
    {
        $params['wearables'] = $appearance->GetWearables();
        $params['attachments'] = $appearance->GetAttachments();
    }

    $response = webservice_post($regionUrl, $params, true);
    if (!empty($response['success']))
    {
        log_message('warn','[LOGIN] created opensim session');

        // This is the hardcoded format OpenSim uses for seed capability URLs
        $seedCapability = $regionBaseUrl . 'CAPS/' . $capsPath . '0000/';
        return true;
    }

    log_message('warn','[LOGIN] failed to created opensim session');
    
    $seedCapability = null;
    return false;
}

///////////////////////////////////////////////////////////////////////////////

function process_login($method_name, $params, $userID)
{
    $config =& get_config();
    $userService = $config['user_service'];
    
    log_message('debug', "Processing new login request");
    
    $req = $params[0];
    $fullname = $req["first"] . ' ' . $req["last"];
    
    // Sanity check the request, make sure it's somewhat valid
    if (empty($userID))
    {
        if (!isset($req["first"], $req["last"], $req["passwd"]) ||
            empty($req["first"]) || empty($req["last"]) || empty($req["passwd"]))
        {
            return array('reason' => 'key' , 'login' => 'false' , 'message' =>
                "Login request must contain a first name, last name, and password and they cannot be blank");
        }
        
        // Authorize the first/last/password and resolve it to a user account UUID
        log_message('debug', "Doing password-based authorization for user $fullname");
        $userID = authorize_identity($fullname, $req['passwd']);
        
        if (empty($userID))
        {
            return array('reason' => 'key' , 'login' => 'false' , 'message' =>
                "Sorry! We couldn't log you in.\nPlease check to make sure you entered the right\n    * Account name\n    * Password\nAlso, please make sure your Caps Lock key is off.");
        }
        
        log_message('debug', sprintf("Authorization success for %s", $userID));
    }
    else
    {
        log_message('debug', sprintf("Using pre-authenticated capability for %s", $userID));
    }
    
    // Get information about the user account
    $user = get_user($userID);
    if (empty($user))
    {
        return array('reason' => 'key', 'login' => 'false', 'message' =>
            "Sorry! We couldn't log you in. User account information could not be retrieved. If this problem persists, please contact the grid operator.");
    }

    $login_success = true;
    //ensure username has the same case as in the database
    $fullname = $user['Name'];
    if ( ! empty($user['UserFlags']) ) {

        $userflags = json_decode($user['UserFlags'], TRUE);
        if ( ! empty($userflags['Suspended']) && (bool) $userflags['Suspended'] === true ) {
            $login_success = false;
            log_message('debug', "User " . $user['Name'] . " is banned.");
        } else {
            if ( $user['AccessLevel'] < $config['access_level_minimum'] ) {
                if ( $config['validation_required'] ) {
                    if ( ! empty($userflags['Validated']) ) {
                        $login_success = $userflags['Validated'];
                    } else {
                        $login_success = false;
                    }
                    if ( ! $login_success ) {
                        log_message('debug', "User " . $user['Name'] . " has not validated their email.");
                    }
                }
            }
        }
    } else if ( $user['AccessLevel'] < $config['access_level_minimum'] && $config['validation_required'] ) {
        $login_success = false;
        log_message('debug', "User " . $user['Name'] . " has not validated their email.");
    }
    
    if ( ! $login_success ) {
        return array(
            'reason' => 'key', 
            'login' => 'false', 
            'message' => "Sorry!  We couldn't log you in.  User account has been suspended or is not yet activated.  If this problem persists, please contact the grid operator."
        );
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
        
        if (remove_session($userID))
        {
            log_message('debug', "Removed existing session for $fullname ($userID)");
        }
        else
        {
            log_message('warn', "Failed to remove session for $fullname ($userID)");
            return array('reason' => 'presence', 'login' => 'false',
                'message' => "You are already logged in from another location. Please try again later.");
        }
    }
    else
    {
        log_message('debug', "No existing session found for $fullname ($userID)");
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
    $llappearance = new Appearance($user);
    
    if (!create_opensim_presence($scene, $userID, $circuitCode, $fullname, $llappearance,
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
        
        if (file_exists(LOGINPATH . "options/Class.$option.php"))
        {
            if (include_once(LOGINPATH . "options/Class.$option.php"))
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
    $response["agent_access"] = 'A';
    $response["agent_region_access"] = 'A';
    $response["agent_access_max"] = 'A';
    $response["agent_flags"] = 0;
    $response["ao_transition"] = 0;
    $response["inventory_host"] = "127.0.0.1";
    
    log_message('info', sprintf("Login User=%s %s Channel=%s Start=%s Viewer=%s id0=%s Mac=%s",
        $req["first"], $req["last"], $req["channel"], $req["start"], $req["version"],
        $req["id0"], $req["mac"]));
    
    return $response;
}
