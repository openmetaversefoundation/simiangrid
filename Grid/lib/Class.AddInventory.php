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
require_once(BASEPATH . 'common/Curl.php');
require_once(BASEPATH . 'common/ALT.php');

function update_appearance($userID, $appearance)
{
    $config =& get_config();
    $url = $config['user_service'];
    
    $params = array(
        'RequestMethod' => 'AddUserData',
        'UserID' => $userID,
        'LLAppearance' => json_encode($appearance)
    );
    
    $curl = new Curl();
    $response = json_decode($curl->simple_post($url, $params), TRUE);
    
    if (!isset($response))
    {
        log_message('error', "Update appearance call to $url failed");
	    $response = array('Message' => 'Invalid or missing response');
    }
	
	return $response;
}

function update_attachments($userID, $attachments)
{
    $config =& get_config();
    $url = $config['user_service'];
    
    $params = array(
        'RequestMethod' => 'AddUserData',
        'UserID' => $userID,
        'LLAttachments' => json_encode($attachments)
    );
    
    $curl = new Curl();
    $response = json_decode($curl->simple_post($url, $params), TRUE);
    
    if (!isset($response))
    {
        log_message('error', "Update attachments call to $url failed");
	    $response = array('Message' => 'Invalid or missing response');
    }
	
    return $response;
}

class AddInventory implements IGridService
{
    private $UserID;
    private $Name;
    private $Inventory;

    public function Execute($db, $params)
    {
        if (!isset($params["OwnerID"]) || !UUID::TryParse($params["OwnerID"], $this->UserID))
        {
            header("Content-Type: application/json", true);
            echo '{ "Message": "Invalid parameters" }';
            exit();
        }
        
        $this->Inventory = new ALT($db);
        $this->Name = 'My Inventory';
        
    	$avtype = "DefaultAvatar";
    	if (isset($params["AvatarType"]))
    	    $avtype = $params["AvatarType"];
        
    	log_message('info', "Creating avatar inventory with type $avtype");
        
    	try
	    {
    	    $avtypehandler = AvatarInventoryFolderFactory::Create($avtype, $this->Name, $this->UserID);
    	}
    	catch (Exception $ex)
    	{
    	    log_message('error', sprintf("Error occurred in avcreation %s", $ex));
    	    header("Content-Type: application/json", true);
    	    echo '{ "Message": "Failed loading avatar template "' . $avtype . ': ' . $ex->getMessage() . ' }';
    	    exit();
    	}
    
    	if (!$avtypehandler)
    	{
    	    // Handle error and return
    	    log_message('error', "Failed to create handler for avatar with type $avtype");
            
            header("Content-Type: application/json", true);
            echo '{ "Message": "Invalid parameters" }';
            exit();
    	}
    	
    	$skeleton = $avtypehandler->Folders();
    	$items = $avtypehandler->Items();
        
        $db->beginTransaction();
        
        for ($i = 0; $i < count($skeleton); $i++)
        {
            try
            {
                $Folder = new InventoryFolder($skeleton[$i]["ID"]);
                $Folder->ParentID = UUID::Parse($skeleton[$i]["ParentID"]);
                $Folder->OwnerID = $this->UserID;
                $Folder->Name = $skeleton[$i]["Name"];
                $Folder->ContentType = $skeleton[$i]["PreferredContentType"];
                
                if (!$this->Inventory->InsertNode($Folder))
                {
                    $db->rollBack();
                    log_message('error', sprintf("Error occurred during folder creation: %s", $this->Inventory->LastError));
                    
                    header("Content-Type: application/json", true);
                    echo '{ "Message": "Inventory creation error" }';
                    exit();
                }
            }
            catch (Exception $ex)
            {
                $db->rollBack();
                log_message('error', sprintf("Error occurred during query: %s", $ex));
                
                header("Content-Type: application/json", true);
                echo '{ "Message": "Database query error" }';
                exit();
            }
        }
        
        for ($i = 0; $i < count($items); $i++)
        {
            try
            {
                $item = new InventoryItem($items[$i]['ID']);
                $item->ParentID = UUID::Parse($items[$i]['ParentID']);
                $item->OwnerID = $this->UserID;
                
                $creatorID = $this->UserID;
                if (array_key_exists('CreatorID', $items[$i]))
                    $creatorID = UUID::Parse($items[$i]['CreatorID']);
                $item->CreatorID = $creatorID;
                
                $item->Name = $items[$i]['Name'];
                $item->AssetID = UUID::Parse($items[$i]['AssetID']);
                
                if (!$this->Inventory->InsertNode($item))
                {
                    $db->rollBack();
                    log_message('error', sprintf("Error occurred during item creation: %s", $this->Inventory->LastError));
                    
                    header("Content-Type: application/json", true);
                    echo '{ "Message": "Inventory creation error" }';
                    exit();
                }
            }
            catch (Exception $ex)
            {
                $db->rollBack();
                log_message('error', sprintf("Error occurred during query: %s", $ex));
                
                header("Content-Type: application/json", true);
                echo '{ "Message": "Database query error" }';
                exit();
            }
        }
        
        $db->commit();
        
        // Update this users appearance in the user service
        $appearance = $avtypehandler->Appearance();
        if ($appearance)
        {
            $response = update_appearance($this->UserID, $appearance);
            
            if (empty($response['Success']))
            {
                header("Content-Type: application/json", true);
                echo sprintf('{ "Message": "%s" }', $response['Message']);
                exit();
            }
        }
        
        // Update this users attachments in the user service
        $attachments = $avtypehandler->Attachments();
        if ($attachments)
        {
            $response = update_attachments($this->UserID, $attachments);
            
            if (empty($response['Success']))
            {
                header("Content-Type: application/json", true);
                echo sprintf('{ "Message": "%s" }', $response['Message']);
                exit();
            }
        }

    	// Add any additional customizations
        $avtypehandler->Configure();

    	// And return success
        header("Content-Type: application/json", true);
        echo sprintf('{"Success": true, "FolderID": "%s"}', $this->UserID);
        exit();
    }
}
