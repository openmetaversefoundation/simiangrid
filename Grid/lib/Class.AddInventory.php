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

class AddInventory implements IGridService
{
    private $UserID;
    private $Name;
    private $mptt;

    public function Execute($db, $params, $logger)
    {
        $this->mptt = new MPTT($db, $logger);
        
        if (!isset($params["OwnerID"]) || !UUID::TryParse($params["OwnerID"], $this->UserID))
        {
            header("Content-Type: application/json", true);
            echo '{ "Message": "Invalid parameters" }';
            exit();
        }
        
        $this->Name = 'My Inventory';
        $RootID = $this->UserID;
        
        $UserSkel = array(
            array('ID' => $RootID, 'ParentID' => UUID::Parse(UUID::Zero), 'Name' => $this->Name, 'PreferredContentType' => 'application/vnd.ll.rootfolder'),
            array('ID' => UUID::Random(), 'ParentID' => $RootID, 'Name' => 'Animations', 'PreferredContentType' => 'application/vnd.ll.animation'),
            array('ID' => UUID::Random(), 'ParentID' => $RootID, 'Name' => 'Body Parts', 'PreferredContentType' => 'application/vnd.ll.bodypart'),
            array('ID' => UUID::Random(), 'ParentID' => $RootID, 'Name' => 'Calling Cards', 'PreferredContentType' => 'application/vnd.ll.callingcard'),
            array('ID' => UUID::Random(), 'ParentID' => $RootID, 'Name' => 'Clothing', 'PreferredContentType' => 'application/vnd.ll.clothing'),
            array('ID' => UUID::Random(), 'ParentID' => $RootID, 'Name' => 'Gestures', 'PreferredContentType' => 'application/vnd.ll.gesture'),
            array('ID' => UUID::Random(), 'ParentID' => $RootID, 'Name' => 'Landmarks', 'PreferredContentType' => 'application/vnd.ll.landmark'),
            array('ID' => UUID::Random(), 'ParentID' => $RootID, 'Name' => 'Lost and Found', 'PreferredContentType' => 'application/vnd.ll.lostandfoundfolder'),
            array('ID' => UUID::Random(), 'ParentID' => $RootID, 'Name' => 'Notecards', 'PreferredContentType' => 'application/vnd.ll.notecard'),
            array('ID' => UUID::Random(), 'ParentID' => $RootID, 'Name' => 'Objects', 'PreferredContentType' => 'application/vnd.ll.primitive'),
            array('ID' => UUID::Random(), 'ParentID' => $RootID, 'Name' => 'Photo Album', 'PreferredContentType' => 'application/vnd.ll.snapshotfolder'),
            array('ID' => UUID::Random(), 'ParentID' => $RootID, 'Name' => 'Scripts', 'PreferredContentType' => 'application/vnd.ll.lsltext'),
            array('ID' => UUID::Random(), 'ParentID' => $RootID, 'Name' => 'Sounds', 'PreferredContentType' => 'application/ogg'),
            array('ID' => UUID::Random(), 'ParentID' => $RootID, 'Name' => 'Textures', 'PreferredContentType' => 'image/x-j2c'),
            array('ID' => UUID::Random(), 'ParentID' => $RootID, 'Name' => 'Trash', 'PreferredContentType' => 'application/vnd.ll.trashfolder'));
        
        $Skel = $UserSkel;
        
        $db->beginTransaction();
        for ($i = 0; $i < count($Skel); $i++)
        {
            try
            {
                $Folder = new InventoryFolder($Skel[$i]["ID"]);
                $Folder->ParentID = UUID::Parse($Skel[$i]["ParentID"]);
                $Folder->OwnerID = $this->UserID;
                $Folder->Name = $Skel[$i]["Name"];
                $Folder->ContentType = $Skel[$i]["PreferredContentType"];
                
                if (!$this->mptt->InsertNode($Folder))
                {
                    $db->rollBack();
                    $logger->err(sprintf("Error occurred during node creation: %s", $this->mptt->LastError));
                    header("Content-Type: application/json", true);
                    echo '{ "Message": "Inventory creation error" }';
                    exit();
                }
            }
            catch (Exception $ex)
            {
                $db->rollBack();
                $logger->err(sprintf("Error occurred during query: %s", $ex));
                header("Content-Type: application/json", true);
                echo '{ "Message": "Database query error" }';
                exit();
            }
        }
        $db->commit();
        
        header("Content-Type: application/json", true);
        echo sprintf('{"Success": true, "FolderID": "%s"}', $RootID);
        exit();
    }
}
