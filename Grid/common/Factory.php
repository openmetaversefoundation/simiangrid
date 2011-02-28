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
 * @author     John Hurliman <http://software.intel.com/en-us/blogs/author/john-hurliman/>
 * @copyright  Open Metaverse Foundation
 * @license    http://www.debian.org/misc/bsd.license  BSD License (3 Clause)
 * @link       http://openmetaverse.googlecode.com/
 */

function load_class($type)
{
    if (class_exists($type))
        return new $type;
    
    $classFile = BASEPATH . 'lib/Class.' . $type . '.php';
    
    if (file_exists($classFile))
    {
        include_once $classFile;
        return new $type();
    }
    else
    {
        log_message('warn', "$classFile not found");
        return false;
    }
}

$CapabilityMap = array(
                       // Capability operations
                       'AddCapability' => array('admin'),
                       'GetCapability' => array('admin'),
                       'GetUserCapabilities' => array('admin', 'user'),
                       'RemoveCapability' => array('admin'),
                       'RemoveUserCapabilities' => array('admin', 'user'),

                       // Quark and actor operations (Intel)
                       'AddActor' => array('admin', 'region'),
                       'AddEndpoint' => array('admin', 'region'),
                       'AddQuark' => array('admin', 'region'),
                       'GetQuark' => array('admin', 'region'),
                       'GetQuarks' => array('admin', 'region'),
                       'RemoveActor' => array('admin', 'region'),
                       'RemoveEndpoint' => array('admin', 'region'),
                       'RemoveQuark' => array('admin', 'region'),

                       // Asset operations
                       'AddMapTile' => array('admin', 'region'),
                       'AddAsset' => array('admin', 'region'),
                       'GetAsset' => array('admin', 'region'),
                       'RemoveAsset' => array('admin', 'region'),

                       // Generic data operations
                       'AddGeneric' => array('admin', 'region'),
                       'GetGenerics' => array('admin', 'region'),
                       'RemoveGeneric' => array('admin', 'region'),

                       // Inventory operations
                       'AddInventoryFolder' => array('admin', 'region'),
                       'AddInventoryItem' => array('admin', 'region'),
                       'AddInventory' => array('admin', 'region'),
                       'GetFolderForType' => array('admin', 'region'),
                       'GetInventoryNode' => array('admin', 'region'),
                       'MoveInventoryNodes' => array('admin', 'region'),
                       'PurgeInventoryFolder' => array('admin', 'region'),
                       'RemoveInventoryNode' => array('admin', 'region'),

                       // Scene operations
                       'AddScene' => array('admin', 'region'), /* this could be admin only with updatescene being region */
                       'EnableScene' => array('admin', 'region'),
                       'GetScene' => array('admin', 'region'),
                       'GetScenes' => array('admin', 'region'),
                       'GetSceneStats' => array('admin', 'region'),
                       'RemoveScene' => array('admin', 'region'),

                       // Session operations
                       'AddSession' => array('admin'), /* only login can add a session */
                       'GetSession' => array('admin', 'region'),
                       'GetSessions' => array('admin'),
                       'RemoveSession' => array('admin', 'region'),
                       'RemoveSessions' => array('admin', 'region'),
                       'UpdateSession' => array('admin', 'region'),

                       // Identity and user operations
                       'AddIdentity' => array('admin'),
                       'AddUserData' => array('admin', 'region'),
                       'AddUser' => array('admin'),
                       'AuthorizeIdentity' => array('admin'),
                       'GetIdentities' => array('admin'),
                       'GetUser' => array('admin', 'region'),
                       'GetUsers' => array('admin', 'region'),
                       'GetUserStats' => array('admin', 'region'),
                       'RemoveIdentity' => array('admin'),
                       'RemoveUser' => array('admin'),
                       'RemoveUserData' => array('admin', 'region'));
                       
function authorize_command($command,$capability)
{
    global $CapabilityMap;

    // Only perform authorization if configured to do so
    $config =& get_config();
    if (! $config['authorize_commands']) 
        return true;

    if ($capability == null)
        return false;

    if (! in_array($capability->Resource,$CapabilityMap[$command]))
        return false;

    return true;
}

function execute_command($command, $capability, $db, $request)
{
    if (!empty($command) && ($action = load_class($command)))
    {
        try
        {
            if (! authorize_command($command,$capability))
            {
                log_message('error', sprintf("insufficient privileges for operation %s",$command));

                header("Content-Type: application/json", true);
                echo '{"Message":"Insufficient privileges"}';
            }
            else 
            {
                // this is really ugly and should be done by passing
                // the capability separately into the function
                $request['_Capability'] = $capability;
                $action->Execute($db, $request);
            }
        }
        catch (Exception $ex)
        {
            log_message('error', "Service $command threw an exception: " . print_r($ex, true));
            
            header("Content-Type: application/json", true);
            echo '{"Message":"Unhandled error"}';
        }
    }
    else
    {
        log_message('warn', 'An empty or unrecognized command was requested: ' . $command);
        
        header("Content-Type: application/json", true);
        echo '{"Message":"Unsupported or missing RequestMethod"}';
    }
}
