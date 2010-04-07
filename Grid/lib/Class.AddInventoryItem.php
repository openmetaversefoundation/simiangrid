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
require_once(BASEPATH . 'common/ALT.php');

class AddInventoryItem implements IGridService
{
    private $Item;
    private $inventory;

    public function Execute($db, $params)
    {
        $this->inventory = new ALT($db);
        
        $itemid = null;
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
        $this->Item->Description = (isset($params["Description"])) ? $params["Description"] : '';
        $this->Item->ExtraData = (isset($params["ExtraData"])) ? $params["ExtraData"] : '';
        
        // If the CreatorID is not set, invalid, or zero, we set CreatorID to NULL so the database
        // layer will fetch CreatorID information based on AssetID 
        if (!isset($params["CreatorID"]) ||
            !UUID::TryParse($params["CreatorID"], $this->Item->CreatorID) ||
            $this->Item->CreatorID == '00000000-0000-0000-0000-000000000000')
        {
            $this->Item->CreatorID = null;
        }
        
        // If ContentType is not given the database layer will fetch ContentType information based
        // on AssetID
        if (isset($params["ContentType"]))
            $this->Item->ContentType = $params["ContentType"];
            
        try
        {
            $result = $this->inventory->InsertNode($this->Item);
            if ($result != false)
            {
                header("Content-Type: application/json", true);
                echo sprintf('{ "Success": true, "ItemID": "%s" }', $result);
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
            log_message('error', sprintf("Error occurred during query: %s", $ex));
            
            header("Content-Type: application/json", true);
            echo '{ "Message": "Database query error" }';
            exit();
        }
    }
}
