<?php
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

$gStartTime = microtime(true);
$gMethodName = "(Unknown)";

define('BASEPATH', str_replace("\\", "/", realpath(dirname(__FILE__)) . '/'));

require_once(BASEPATH . 'common/Config.php');
require_once(BASEPATH . 'common/Errors.php');
require_once(BASEPATH . 'common/Log.php');
require_once(BASEPATH . 'common/Database.php');
require_once(BASEPATH . 'common/Interfaces.php');
require_once(BASEPATH . 'common/Factory.php');
require_once(BASEPATH . 'common/UUID.php');
require_once(BASEPATH . 'common/Vector3.php');
require_once(BASEPATH . 'common/Curl.php');

// Performance profiling/logging
function shutdown()
{
    global $gStartTime, $gMethodName;
    $elapsed = microtime(true) - $gStartTime;
    log_message('debug', "Executed $gMethodName in $elapsed seconds");
}
register_shutdown_function('shutdown');

// Configuration loading
$config =& get_config();

// Disable magic quotes
if (get_magic_quotes_gpc())
{
    log_message('debug', "Magic quotes detected, disabling");
    
    function stripslashes_gpc(&$value) { $value = stripslashes($value); }
    
    array_walk_recursive($_GET, 'stripslashes_gpc');
    array_walk_recursive($_POST, 'stripslashes_gpc');
    array_walk_recursive($_COOKIE, 'stripslashes_gpc');
    array_walk_recursive($_REQUEST, 'stripslashes_gpc');
}

// Connect to the database
try
{
    $db = new Database();
}
catch (Exception $ex)
{
    log_message('error', sprintf("Database Exception: %d %s", mysqli_connect_errno(), mysqli_connect_error()));
    log_message('debug', sprintf("Database Exception: %s", print_r($ex, true)));
    
    header("Content-Type: application/json", true);
    echo '{"Message":"An error ocurred while attempting to connect or communicate with the database"}';
    exit();
}

// Determine the request type
if (isset($_GET['cap']))
{
    // Capability routing
    $resourceURL = '';
    
    $sql = "SELECT Resource FROM Capabilities WHERE ID=:ID AND ExpirationDate > NOW() LIMIT 1";
    
    $sth = $db->prepare($sql);
    
    if ($sth->execute(array(':ID' => $_GET['cap'])))
    {
        if ($sth->rowCount() > 0)
        {
            $obj = $sth->fetchObject();
            $resourceURL = $obj->Resource;
            
            // TODO: Handle relative resource URLs
            
            if (stripos($_SERVER['REQUEST_METHOD'], 'POST') !== FALSE)
            {
                // FIXME: Implement POST capability routing
                $curl = new Curl();
                $curl->create($resourceURL);
            }
            else if (stripos($_SERVER['REQUEST_METHOD'], 'GET') !== FALSE)
            {
                // FIXME: Properly proxy response codes
                $curl = new Curl();
                $curl->create($resourceURL);
                echo $curl->execute();
                exit();
            }
            else
            {
                log_message('warn', "Don't know how to route method " . $_SERVER['REQUEST_METHOD'] . " for capability $resourceURL");
                
                header("HTTP/1.1 400 Bad Request");
                echo 'Request method not understood';
                exit();
            }
        }
        else
        {
            log_message('warn', "Capability " . $_GET['cap'] . " not found");
            
            header("HTTP/1.1 404 Not Found");
            echo 'Capability not found';
            exit();
        }
    }
    else
    {
        log_message('error', sprintf("Error occurred during query: %d %s", $sth->errorCode(), print_r($sth->errorInfo(), true)));
        log_message('debug', sprintf("Query: %s", $sql));
        
        header('HTTP/1.1 500 Internal Server Error');
        echo 'Internal server error';
        exit();
    }
}
else if ((stripos($_SERVER['REQUEST_METHOD'], 'GET') !== FALSE || (stripos($_SERVER['REQUEST_METHOD'], 'HEAD') !== FALSE)))
{
    $uuid = null;
    
    if (isset($_GET['id']) && UUID::TryParse(ltrim($_GET['id'], '/'), $uuid))
    {
        // Asset download
        $gMethodName = 'GetAsset';
        $asset = new Asset();
        $asset->ID = $uuid;
        execute_command('GetAsset', $db, $asset);
        exit();
    }
    else
    {
        // Regular GET request
        $gMethodName = '(Webpage)';
        echo 'SimianGrid';
        exit();
    }
}
else if (isset($_SERVER['CONTENT_TYPE']) && stripos($_SERVER['CONTENT_TYPE'], 'multipart/form-data') !== FALSE)
{
    if (isset($_FILES['Asset']) && count($_FILES) == 1)
    {
    	// Asset upload
        $asset = new Asset();
        
        if (!isset($_POST['AssetID']) || !UUID::TryParse($_POST['AssetID'], $asset->ID))
            $asset->ID = UUID::Random();
        
        if (!isset($_POST['CreatorID']) || !UUID::TryParse($_POST['CreatorID'], $asset->CreatorID))
            $asset->CreatorID = UUID::Zero;
        
        if (!empty($_FILES['Asset']['tmp_name']))
        {
            $tmpName = $_FILES['Asset']['tmp_name'];
            $fp = fopen($tmpName, 'r');
            $asset->Data = fread($fp, filesize($tmpName));
            fclose($fp);
            
            $asset->SHA256 = hash_file('sha256', $tmpName);
            $asset->ContentLength = filesize($tmpName);
            $asset->ContentType = $_FILES['Asset']['type'];
            $asset->Temporary = !empty($_POST['Temporary']);
            if (isset($_POST['Public']))
                $asset->Public = ($_POST['Public']) ? TRUE : FALSE;
            else
                $asset->Public = TRUE;
            
			$gMethodName = 'AddAsset';
            execute_command('AddAsset', $db, $asset);
            exit();
        }
        else
        {
            log_message('error', 'Asset Upload Failed, Asset specified but no filedata included in request: ' . print_r($_FILES, TRUE));
            
            header("Content-Type: application/json", true);
            echo '{ "Message": "Missing asset data" }';
            exit();
        }
    }
    else if (isset($_FILES['Tile']) && count($_FILES) == 1 &&
    	$_FILES['Tile']['type'] == "image/png" &&
    	isset($_POST['X']) && isset($_POST['Y']))
    {
    	// Map tile upload
        $tile = new MapTile();
        
        $tile->X = (int)$_POST['X'];
        $tile->Y = (int)$_POST['Y'];
        
    	if (!empty($_FILES['Tile']['tmp_name']))
        {
            $tmpName = $_FILES['Tile']['tmp_name'];
            $fp = fopen($tmpName, 'r');
            $tile->Data = fread($fp, filesize($tmpName));
            fclose($fp);
            
			$gMethodName = 'AddMapTile';
            execute_command('AddMapTile', null, $tile);
            exit();
        }
        else
        {
            log_message('error', 'Asset Upload Failed, Asset specified but no filedata included in request: ' . print_r($_FILES, TRUE));
            
            header("Content-Type: application/json", true);
            echo '{ "Message": "Missing asset data" }';
            exit();
        }
    }
    else
    {
        log_message('error', 'Upload Failed, POST requested but no file or filedata included in request: ' . print_r($_POST, TRUE));
        
        header("Content-Type: application/json", true);
        echo '{ "Message": "Invalid parameters for upload" }';
        exit();
    }
}
else if (stripos($_SERVER['REQUEST_METHOD'], 'DELETE') !== FALSE)
{
    $uuid = null;
    
    if (isset($_GET['id']) && UUID::TryParse(ltrim($_GET['id'], '/'), $uuid))
    {
        // Asset delete
        $asset = new Asset();
        $asset->ID = $uuid;
		$gMethodName = 'RemoveAsset';
        execute_command('RemoveAsset', $db, $asset);
        exit();
    }
    else
    {
        // DELETE with no asset ID
        log_message('warn', "DELETE request received with no asset ID: " . $_SERVER['REQUEST_URI']);
        
        header("Content-Type: application/json", true);
        echo '{ "Message": "Invalid parameters for asset delete" }';
        exit();
    }
}
else if (stripos($_SERVER['REQUEST_METHOD'], 'POST') !== FALSE)
{
    // Grid service call
    if (!isset($_SERVER['CONTENT_TYPE']) || $_SERVER['CONTENT_TYPE'] == 'application/x-www-form-urlencoded')
    {
        $request = $_POST;
    }
    else if ($_SERVER['CONTENT_TYPE'] == 'application/json')
    {
        $data = file_get_contents("php://input");
        $json = json_decode($data, true);
        
        if ($json)
        {
            $request = $json;
        }
        else
        {
            log_message('warn', "Error decoding JSON request");
            log_message('debug', "Invalid JSON request data: " . $data);
            $gMethodName = 'Invalid JSON';
            
            header("Content-Type: application/json", true);
            echo '{"Message":"Error decoding JSON request"}';
            exit();
        }
    }
    else
    {
        log_message('warn', "Invalid Content-Type in request: " . $_SERVER['CONTENT_TYPE']);
        
        header("Content-Type: application/json", true);
        echo '{"Message":"Content-Type not set or invalid"}';
        exit();
    }
    
    if (isset($request['RequestMethod']))
    {
        $command = trim($request['RequestMethod']);
        $gMethodName = $command;
        if ($gMethodName == 'GetGenerics')
            $gMethodName .= ' (' . $request['Type'] . ')';
        execute_command($command, $db, $request);
    }
    else
    {
        log_message('warn', "Request does not contain a RequestMethod: " . print_r($request, true));
    }
    exit();
}
else
{
    log_message('warn', "Unhandled request method: " . $_SERVER['REQUEST_METHOD']);
    
    header("Content-Type: application/json", true);
    echo '{"Message":"Unhandled request method"}';
    exit();
}
