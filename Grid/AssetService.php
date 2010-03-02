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
 * @author     Jim Radford <http://www.jimradford.com/>
 * @copyright  Open Metaverse Foundation
 * @license    http://www.debian.org/misc/bsd.license  BSD License (3 Clause)
 * @link       http://openmetaverse.googlecode.com/
 */

require_once ('lib/Class.Logger.php');
$L = new Logger('services.ini', "ASSETSERVICE");
$logger = $L->getInstance();

$config = parse_ini_file('services.ini', true);

require_once ('lib/Class.ExceptionHandler.php');
require_once ('lib/Class.ErrorHandler.php');
require_once ('lib/Class.MySQL.php');
require_once ('lib/Class.Asset.php');
require_once ('lib/Class.Factory.php');
require_once ('lib/Class.UUID.php');

// Create an instance of the Asset class
$asset = new Asset();

if (stripos($_SERVER['REQUEST_METHOD'], 'GET') !== FALSE || (stripos($_SERVER['REQUEST_METHOD'], 'HEAD') !== FALSE))
{
    if (!isset($_GET['id']))
    {
        $logger->warning('Received a GET request to the asset service without an asset id');
        header("HTTP/1.1 404 Not Found");
        echo 'Asset not found';
        exit();
    }
    
    $action = Factory::CreateInstanceOf('GetAsset');
    $asset->ID = ltrim($_GET['id'], '/');
}
else if (stripos($_SERVER['REQUEST_METHOD'], 'POST') !== FALSE)
{
    if (isset($_FILES['Asset']) && count($_FILES) == 1)
    {
        $action = Factory::CreateInstanceOf('AddAsset');
        
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
            $asset->ContentType = $_FILES['Asset']['type'];
            $asset->Temporary = !empty($_POST['Temporary']);
            if (isset($_POST['Public']))
                $asset->Public = ($_POST['Public']) ? TRUE : FALSE;
            else
                $asset->Public = TRUE;
        }
        else
        {
            $logger->err('Asset Upload Failed, Asset specified but no filedata included in request: ' . print_r($_FILES, TRUE));
            header("Content-Type: application/json", true);
            echo '{ "Message": "Missing asset data" }';
            exit();
        }
    }
    else
    {
        $logger->err('Asset Upload Failed, POST requested but no file or filedata included in request');
        header("Content-Type: application/json", true);
        echo '{ "Message": "Invalid parameters" }';
        exit();
    }
}
else if (stripos($_SERVER['REQUEST_METHOD'], 'DELETE') !== FALSE)
{
    $action = Factory::CreateInstanceOf('RemoveAsset');
    $asset->ID = $_GET['id'];
}
else
{
    $logger->warning('An Unsupported request method: ' . $_SERVER['REQUEST_METHOD'] . ' was requested');
    header("HTTP/1.0 405 Method Not Allowed");
    echo 'Method is not allowed';
    exit();
}

// Connect to the database
try
{
    $db = new MySQL($config['Database']['host'], $config['Database']['username'], $config['Database']['password'], $config['Database']['database']);
}
catch (Exception $ex)
{
    header("HTTP/1.1 500 Internal Server Error");
    $logger->crit(sprintf("Database Exception: %d %s", mysqli_connect_errno(), mysqli_connect_error()));
    $logger->debug(sprintf("Database Exception: %s", print_r($ex, true)));
    exit();
}

// Execute!
if ($action != NULL)
{
    $action->Execute($db, $asset, $logger);
}
else
{
    header("HTTP/1.1 405 Method Not Allowed");
    exit();
}

$db->close();
