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
interface_exists('IGridService') || require_once ('Interface.GridService.php');
class_exists('MPTT') || require_once ('Class.MPTT.php');
class_exists('Inventory') || require_once ('Class.Inventory.php');

class AddInventoryItem implements IGridService
{
    private $Item;
    private $mptt;
    
    // TODO: This isn't necessary. We could do this fetch as part of the insert query in MPTT
    private function GetAssetMetadata($db)
    {
        // Fetch metadata for the asset this item points to
        $sql = "SELECT CreatorID, ContentType FROM AssetData WHERE ID=:ID";
        $sth = $db->prepare($sql);
        if($sth->execute(array(':ID' => $this->Item->AssetID)))
        {
            if($sth->rowCount() == 1)
            {
                $obj = $sth->fetchObject();
                
                $this->Item->CreatorID = UUID::Parse($obj->CreatorID);
                $this->Item->ContentType = $obj->ContentType;
                return TRUE;
            }
            else
            {
                return "Asset not found";
            }
        }
        else
        {
            return "Error occurred during asset query";
        }
    }

    public function Execute($db, $params, $logger)
    {
        $this->mptt = new MPTT($db, $logger);
        
        $itemid = NULL;
        if (!isset($params["ItemID"]) || !UUID::TryParse($params["ItemID"], $itemid))
            $itemid = UUID::Random();
        
        $this->Item = new InventoryItem($itemid);
        
        if (!isset($params, $params["AssetID"], $params["Name"], $params["ParentID"], $params["OwnerID"]) ||
            !UUID::TryParse($params["ParentID"], $this->Item->ParentID) ||
            !UUID::TryParse($params["AssetID"], $this->Item->AssetID) ||
            !UUID::TryParse($params["OwnerID"], $this->Item->OwnerID))
        {
            header("Content-Type: application/json", true);
            echo '{ "Message": "Invalid parameters" }';
            exit();
        }
        
        $this->Item->Name = trim($params["Name"]);
        $this->Item->Description = (isset($params["Description"])) ? trim($params["Description"]) : '';
        $this->Item->ExtraData = (isset($params["ExtraData"])) ? trim($params["ExtraData"]) : '';
        
        $response = $this->GetAssetMetadata($db);
        if ($response !== TRUE)
        {
            header("Content-Type: application/json", true);
            echo sprintf('{ "Message": "%s" }', $response);
            exit();
        }
        
        try
        {
            $result = $this->mptt->InsertNode($this->Item);
            if ($result != FALSE)
            {
                header("Content-Type: application/json", true);
                echo sprintf('{ "Success": true, "ItemID": "%s" }', $result->UUID);
                exit();
            }
            else
            {
                header("Content-Type: application/json", true);
                echo '{ "Message": "Item creation failed" }';
                exit();
            }
        }
        catch (Exception $ex)
        {
            $logger->err(sprintf("Error occurred during query: %s", $ex));
            header("Content-Type: application/json", true);
            echo '{ "Message": "Database query error" }';
            exit();
        }
    }
}
