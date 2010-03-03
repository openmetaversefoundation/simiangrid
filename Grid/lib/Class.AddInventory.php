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

function update_appearance($url, $userID, $appearance)
{
    if (empty($url))
        return array('Message' => 'Missing user service URL');
    
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

class AddInventory implements IGridService
{
    private $UserID;
    private $Name;
    private $inventory;

    public function Execute($db, $params)
    {
        if (!isset($params["OwnerID"]) || !UUID::TryParse($params["OwnerID"], $this->UserID))
        {
            header("Content-Type: application/json", true);
            echo '{ "Message": "Invalid parameters" }';
            exit();
        }
        
        $config = parse_ini_file('services.ini', true);
        $this->inventory = new ALT($db);
        
        $this->Name = 'My Inventory';
        $RootID = $this->UserID;
        
        $clothingID = UUID::Random();
        $outfitID = UUID::Random();
        
        $skeleton = array(
            array('ID' => $RootID, 'ParentID' => UUID::Parse(UUID::Zero), 'Name' => $this->Name, 'PreferredContentType' => 'application/vnd.ll.folder'),
            array('ID' => UUID::Random(), 'ParentID' => $RootID, 'Name' => 'Animations', 'PreferredContentType' => 'application/vnd.ll.animation'),
            array('ID' => UUID::Random(), 'ParentID' => $RootID, 'Name' => 'Body Parts', 'PreferredContentType' => 'application/vnd.ll.bodypart'),
            array('ID' => UUID::Random(), 'ParentID' => $RootID, 'Name' => 'Calling Cards', 'PreferredContentType' => 'application/vnd.ll.callingcard'),
            array('ID' => UUID::Random(), 'ParentID' => $RootID, 'Name' => 'Gestures', 'PreferredContentType' => 'application/vnd.ll.gesture'),
            array('ID' => UUID::Random(), 'ParentID' => $RootID, 'Name' => 'Landmarks', 'PreferredContentType' => 'application/vnd.ll.landmark'),
            array('ID' => UUID::Random(), 'ParentID' => $RootID, 'Name' => 'Lost and Found', 'PreferredContentType' => 'application/vnd.ll.lostandfoundfolder'),
            array('ID' => UUID::Random(), 'ParentID' => $RootID, 'Name' => 'Notecards', 'PreferredContentType' => 'application/vnd.ll.notecard'),
            array('ID' => UUID::Random(), 'ParentID' => $RootID, 'Name' => 'Objects', 'PreferredContentType' => 'application/vnd.ll.primitive'),
            array('ID' => UUID::Random(), 'ParentID' => $RootID, 'Name' => 'Photo Album', 'PreferredContentType' => 'application/vnd.ll.snapshotfolder'),
            array('ID' => UUID::Random(), 'ParentID' => $RootID, 'Name' => 'Scripts', 'PreferredContentType' => 'application/vnd.ll.lsltext'),
            array('ID' => UUID::Random(), 'ParentID' => $RootID, 'Name' => 'Sounds', 'PreferredContentType' => 'application/ogg'),
            array('ID' => UUID::Random(), 'ParentID' => $RootID, 'Name' => 'Textures', 'PreferredContentType' => 'image/x-j2c'),
            array('ID' => UUID::Random(), 'ParentID' => $RootID, 'Name' => 'Trash', 'PreferredContentType' => 'application/vnd.ll.trashfolder'),
            array('ID' => $clothingID, 'ParentID' => $RootID, 'Name' => 'Clothing', 'PreferredContentType' => 'application/vnd.ll.clothing'),
            array('ID' => $outfitID, 'ParentID' => $clothingID, 'Name' => 'Default Outfit', 'PreferredContentType' => 'application/octet-stream')
        );
        
        $hairID = UUID::Random();
        $pantsID = UUID::Random();
        $shapeID = UUID::Random();
        $shirtID = UUID::Random();
        $skinID = UUID::Random();
        $eyesID = UUID::Random();
        
        $items = array(
            array('ID' => $hairID, 'ParentID' => $outfitID, 'Name' => 'Default Hair', 'AssetID' => 'dc675529-7ba5-4976-b91d-dcb9e5e36188'),
            array('ID' => $pantsID, 'ParentID' => $outfitID, 'Name' => 'Default Pants', 'AssetID' => '3e8ee2d6-4f21-4a55-832d-77daa505edff'),
            array('ID' => $shapeID, 'ParentID' => $outfitID, 'Name' => 'Default Shape', 'AssetID' => '530a2614-052e-49a2-af0e-534bb3c05af0'),
            array('ID' => $shirtID, 'ParentID' => $outfitID, 'Name' => 'Default Shirt', 'AssetID' => '6a714f37-fe53-4230-b46f-8db384465981'),
            array('ID' => $skinID, 'ParentID' => $outfitID, 'Name' => 'Default Skin', 'AssetID' => '5f787f25-f761-4a35-9764-6418ee4774c4'),
            array('ID' => $eyesID, 'ParentID' => $outfitID, 'Name' => 'Default Eyes', 'AssetID' => '78d20332-9b07-44a2-bf74-3b368605f4b5')
        );
        
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
                
                if (!$this->inventory->InsertNode($Folder))
                {
                    $db->rollBack();
                    log_message('error', sprintf("Error occurred during folder creation: %s", $this->inventory->LastError));
                    
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
                $item->CreatorID = $this->UserID;
                $item->Name = $items[$i]['Name'];
                $item->AssetID = UUID::Parse($items[$i]['AssetID']);
                
                if (!$this->inventory->InsertNode($item))
                {
                    $db->rollBack();
                    log_message('error', sprintf("Error occurred during item creation: %s", $this->inventory->LastError));
                    
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
        $appearance = array(
            'Height' => 1.771488,
            'ShapeItem' => (string)$shapeID,
            'ShapeAsset' => '530a2614-052e-49a2-af0e-534bb3c05af0',
            'EyesItem' => (string)$eyesID,
            'EyesAsset' => '78d20332-9b07-44a2-bf74-3b368605f4b5',
            //'GlovesItem' => '',
            //'GlovesAsset' => '',
            'HairItem' => (string)$hairID,
            'HairAsset' => 'dc675529-7ba5-4976-b91d-dcb9e5e36188',
            //'JacketItem' => '',
            //'JacketAsset' => '',
            'PantsItem' => (string)$pantsID,
            'PantsAsset' => '3e8ee2d6-4f21-4a55-832d-77daa505edff',
            'ShirtItem' => (string)$shirtID,
            'ShirtAsset' => '6a714f37-fe53-4230-b46f-8db384465981',
            //'ShoesItem' => '',
            //'ShoesAsset' => '',
            'SkinItem' => (string)$skinID,
            'SkinAsset' => '5f787f25-f761-4a35-9764-6418ee4774c4'
            //'SkirtItem' => '',
            //'SkirtAsset' => '',
            //'SocksItem' => '',
            //'SocksAsset' => '',
            //'UnderpantsItem' => '',
            //'UnderpantsAsset' => '',
            //'UndershirtItem' => '',
            //'UndershirtAsset' => ''
        );
        
        $userService = $config['UserService']['server_url'];
        $response = update_appearance($userService, $this->UserID, $appearance);
        
        if (!empty($response['Success']))
        {
            header("Content-Type: application/json", true);
            echo sprintf('{"Success": true, "FolderID": "%s"}', $RootID);
            exit();
        }
        else
        {
            header("Content-Type: application/json", true);
            echo sprintf('{ "Message": "%s" }', $response['Message']);
            exit();
        }
    }
}
