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
 * @author     John Hurliman <http://software.intel.com/en-us/blogs/author/john-hurliman/>
 *             Jonathan Freedman <http://twitter.com/otakup0pe>
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
xmlrpc_server_register_method($xmlrpc_server, "login_to_simulator", "link_region");
xmlrpc_server_register_method($xmlrpc_server, "login_to_simulator", "get_region");
xmlrpc_server_register_method($xmlrpc_server, "login_to_simulator", "get_home_region");
xmlrpc_server_register_method($xmlrpc_server, "login_to_simulator", "verify_client");
xmlrpc_server_register_method($xmlrpc_server, "login_to_simulator", "verify_agent");
xmlrpc_server_register_method($xmlrpc_server, "login_to_simulator", "logout_agent");
xmlrpc_server_register_method($xmlrpc_server, "login_to_simulator", "agent_is_coming_home");

$request_xml = file_get_contents("php://input");

$response = xmlrpc_server_call_method($xmlrpc_server, $request_xml, '');

header('Content-Type: text/xml');
echo $response;

xmlrpc_server_destroy($xmlrpc_server);
exit();

///////////////////////////////////////////////////////////////////////////////

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
    
    //log_message('debug', sprintf('Response received from %s POST to %s: %s', $requestMethod, $url, $response));
    
    // JSON decode the response
    $response = json_decode($response, TRUE);
	
	if (!isset($response))
	    $response = array('Message' => 'Invalid or missing response');
	
    return $response;
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

function create_opensim_presence($scene, $userID, $circuitCode, $fullName, $appearance, $attachments,
    $sessionID, $secureSessionID, $startPosition, &$seedCapability)
{
    $regionBaseUrl = $scene->Address;
    if (!ends_with($regionBaseUrl, '/'))
        $regionBaseUrl .= '/';
    $regionUrl = $regionBaseUrl . 'agent/' . $userID . '/';
    
    list($firstName, $lastName) = explode(' ', $fullName);
    $capsPath = UUID::Random();
    
    $wearables = array();
    $attached = array();
    
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
    
    if (isset($attachments))
    {
        $i = 0;
        
        foreach ($attachments as $key => $item)
        {
            if (substr($key, 0, 4) === '_ap_')
            {
                $point = (int)substr($key, 4);
                $attached[$i++] = array('point' => $point, 'item' => $item);
            }
        }
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
        'wearables' => $wearables,
        'attachments' => $attached,
        'teleport_flags' => 128
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
// GateKeeper Service

function link_region($method_name, $params, $user_data)
{
    log_message('info', "$method_name called");
    
    $response["blah"] = 'blah';
    return $response;
}

function get_region($method_name, $params, $user_data)
{
    log_message('info', "$method_name called");
    
    $response["blah"] = 'blah';
    return $response;
}

function foreignagent_handler()
{
    // FIXME: How do we handle this?
}

///////////////////////////////////////////////////////////////////////////////
// UserAgent Service

function get_home_region($method_name, $params, $user_data)
{
    log_message('info', "$method_name called");
    
    $response["blah"] = 'blah';
    return $response;
}

function verify_client($method_name, $params, $user_data)
{
    log_message('info', "$method_name called");
    
    $response["blah"] = 'blah';
    return $response;
}

function verify_agent($method_name, $params, $user_data)
{
    log_message('info', "$method_name called");
    
    $response["blah"] = 'blah';
    return $response;
}

function logout_agent($method_name, $params, $user_data)
{
    log_message('info', "$method_name called");
    
    $response["blah"] = 'blah';
    return $response;
}

function agent_is_coming_home($method_name, $params, $user_data)
{
    log_message('info', "$method_name called");
    
    $response["blah"] = 'blah';
    return $response;
}

function homeagent_handler($method_name, $params, $user_data)
{
    // FIXME: How do we handle this?
}
