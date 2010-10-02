<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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

class MPTT
{
    public $LastError = '';
    
    private $conn;
    private $logger;

    public function __construct($db_conn, $logger)
    {
        $this->logger = $logger;
        if (!$db_conn || !($db_conn instanceof PDO))
        {
            throw new Exception("MPTT::__construct expects first parameter passed to be a valid database resource. " . print_r($db_conn, true));
        }
        
        $this->conn = $db_conn;
    }

    public function GetLastError()
    {
        return $this->LastError;
    }

    public function InsertNode(Inventory $inventory)
    {
        if (!($inventory instanceof InventoryFolder) && !($inventory instanceof InventoryItem))
        {
            $this->LastError = "InsertNode passed an invalid object. Must be either an InventoryFolder or InventoryItem, Not " . gettype($inventory);
            throw new Exception("InsertNode passed an invalid object. Must be either an InventoryFolder or InventoryItem");
        }
        
        $parent_level = 0;
        
        if ($inventory->ParentID == UUID::Zero)
        {
            $parent_level = 1;
            $sql = sprintf("SELECT RightNode + 1 AS RightNode FROM Inventory WHERE OwnerID=:OwnerID ORDER BY RightNode DESC LIMIT 1");
            $sth = $this->conn->prepare($sql);
            if ($sth->execute(array(':OwnerID' => $inventory->OwnerID)))
            {
                if ($sth->rowCount() == 1)
                {
                    $obj = $sth->fetchObject();
                    $parent_level = (int)$obj->RightNode;
                }
            }
        }
        else
        {
            $sql = sprintf("SELECT RightNode FROM Inventory WHERE OwnerID=:OwnerID AND ID=:ParentID AND Type='Folder' ORDER BY RightNode DESC LIMIT 1");
            $sth = $this->conn->prepare($sql);
            if ($sth->execute(array(':OwnerID' => $inventory->OwnerID, ':ParentID' => $inventory->ParentID)) && $sth->rowCount() == 1)
            {
                $obj = $sth->fetchObject();
                $parent_level = (int)$obj->RightNode;
            }
            else
            {
                $this->LastError = sprintf("Unable to locate destinations parent folder: %s for %s", $inventory->ParentID, $inventory->OwnerID);
                return FALSE;
            }
        }
        
        $this->AllocateSpace(2, $parent_level);
        
        if ($inventory instanceof InventoryFolder)
        {
            $sql = "INSERT INTO Inventory (ID, ParentID, OwnerID, CreatorID, Name, Description, ContentType, Version, ExtraData, CreationDate, Type, LeftNode, RightNode)
                    VALUES (:ID, :ParentID, :OwnerID, :OwnerID, :Name, '', :ContentType, '0', '', CURRENT_TIMESTAMP, 'Folder', :ParentLevel, :ParentLevel + 1)
                    ON DUPLICATE KEY UPDATE ParentID=VALUES(ParentID), CreatorID=VALUES(CreatorID), Name=VALUES(Name), Description=VALUES(Description), ContentType=VALUES(ContentType), Version=Version+1";
            if (!empty($inventory->ExtraData))
                $sql .= ", ExtraData=VALUES(ExtraData)";
            
            $sth = $this->conn->prepare($sql);
            if ($sth->execute(array(
                ':ID' => $inventory->ID,
                ':ParentID' => $inventory->ParentID,
                ':OwnerID' => $inventory->OwnerID,
                ':Name' => $inventory->Name,
                ':ContentType' => $inventory->ContentType,
                ':ParentLevel' => $parent_level)))
            {
                /*
                 * Increment the parent folder version
                 */
                $sql = "UPDATE Inventory SET Version=Version+1 WHERE ID=:Parent";
                $sth = $this->conn->prepare($sql);
                $sth->execute(array(':Parent' => $inventory->ParentID));
                
                // Node Inserted!
                return $inventory->ID;
            }
            else
            {
                $this->LastError = sprintf("[MPTT::Add/InventoryFolder] Error occurred during query: %d %s", $sth->errorCode(), print_r($sth->errorInfo(), true));
                return FALSE;
            }
        
        }
        else if ($inventory instanceof InventoryItem)
        {
            $sql = "INSERT INTO Inventory (ID, AssetID, ParentID, OwnerID, CreatorID, Name, Description, ContentType, Version, ExtraData, CreationDate, Type, LeftNode, RightNode)
                    VALUES (:ID, :AssetID, :ParentID, :OwnerID, :CreatorID, :Name, :Description, :ContentType, '0', '', CURRENT_TIMESTAMP , 'Item', :ParentLevel, :ParentLevel + 1)
                    ON DUPLICATE KEY UPDATE AssetID=VALUES(AssetID), ParentID=VALUES(ParentID), CreatorID=VALUES(CreatorID), Name=VALUES(Name), Description=VALUES(Description), ContentType=VALUES(ContentType), Version=Version+1";
            if (!empty($inventory->ExtraData))
                $sql .= ", ExtraData=VALUES(ExtraData)";
            
            $sth = $this->conn->prepare($sql);
            if ($sth->execute(array(
                ':ID' => $inventory->ID,
                ':AssetID' => $inventory->AssetID,
                ':ParentID' => $inventory->ParentID,
                ':OwnerID' => $inventory->OwnerID,
                ':CreatorID' => $inventory->CreatorID,
                ':Name' => $inventory->Name,
                ':Description' => $inventory->Description,
                ':ContentType' => $inventory->ContentType,
                ':ParentLevel' => $parent_level)))
            {
                /*
                 * Increment the parent folder version
                 */
                $sql = "UPDATE Inventory SET Version=Version+1 WHERE ID=:Parent";
                $sth = $this->conn->prepare($sql);
                $sth->execute(array(':Parent' => $inventory->ParentID));
                
                return $inventory->ID;
            }
            else
            {
                $this->LastError = sprintf("[MPTT::Add/InventoryItem] Error occurred during query: %d %s", $sth->errorCode(), print_r($sth->errorInfo(), true));
                return FALSE;
            }
        }
        else
        {
            // This should never be reached as it was checked for at the very beginning
            $this->LastError = "[MPTT::Add] Must be either an InventoryFolder or InventoryItem, Not " . gettype($inventory);
            return FALSE;
        }
    }
    
    private function GetDescendant($item)
    {
        $descendant = NULL;
        
        if ($item->Type == 'Folder')
        {
            $descendant = new InventoryFolder(UUID::Parse($item->ID));
            $descendant->ParentID = UUID::Parse($item->ParentID);
            $descendant->OwnerID = UUID::Parse($item->OwnerID);
            $descendant->Name = $item->Name;
            $descendant->ContentType = $item->ContentType;
            $descendant->Version = $item->Version;
            $descendant->ExtraData = $item->ExtraData;
        }
        else
        {
            $descendant = new InventoryItem(UUID::Parse($item->ID));
            $descendant->AssetID = UUID::Parse($item->AssetID);
            $descendant->ParentID = UUID::Parse($item->ParentID);
            $descendant->OwnerID = UUID::Parse($item->OwnerID);
            $descendant->CreatorID = UUID::Parse($item->CreatorID);
            $descendant->Name = $item->Name;
            $descendant->Description = $item->Description;
            $descendant->ContentType = $item->ContentType;
            $descendant->Version = $item->Version;
            $descendant->ExtraData = $item->ExtraData;
            $descendant->CreationDate = gmdate('U', (int)$item->CreationDate);
        }
        
        return $descendant;
    }

    public function FetchDescendants($folderID, $fetchFolders = TRUE, $fetchItems = TRUE, $childrenOnly = FALSE)
    {
        global $logger;
        //            $logger->debug("MPTT::FetchDescendants FolderID: $folderID");
        $sql = "SELECT * FROM Inventory WHERE ID=:Parent";
        $sth = $this->conn->prepare($sql);
        
        $Results = array();
        
        if ($sth->execute(array(':Parent' => $folderID)))
        {
            if ($obj = $sth->fetchObject())
            {
                $Results[] = $this->GetDescendant($obj);
                //$logger->debug("MPTT::FetchDescendants obj: " . print_r($obj, true));
                
                if ($fetchFolders && $fetchItems)
                    $fetchTypes = "'Folder','Item'";
                else if ($fetchFolders)
                    $fetchTypes = "'Folder'";
                else
                    $fetchTypes = "'Item'";
                
                $psq = "";
                if ($childrenOnly)
                {
                    //                    $psq = "(ParentID='" . $obj->ID . "' OR ID='". $obj->ID ."') AND ";
                }
                
                $sql = "SELECT * FROM Inventory WHERE (" . $psq . " OwnerID=:OwnerID AND Type IN (" . $fetchTypes . ")
                        AND (LeftNode BETWEEN :Left AND :Right)) ORDER BY LeftNode ASC";
                
                $sth = $this->conn->prepare($sql);
                
                if ($sth->execute(array(':OwnerID' => $obj->OwnerID , ':Left' => (int)$obj->LeftNode , ':Right' => (int)$obj->RightNode)))
                {
                    //                  $logger->debug("MPTT::FetchDescendants count: " . $sth->rowCount());
                    //                  $logger->debug("MPTT::FetchDescendants sql: " . $sql);
                    //                  $logger->debug("MPTT::FetchDescendants types: " . $fetchTypes);
                    while ($item = $sth->fetchObject())
                    {
                        $Results[] = $this->GetDescendant($item);
                    }
                }
                else
                {
                    $logger->err(sprintf("Error occurred during query: %d %s SQL:'%s'", $sth->errorCode(), print_r($sth->errorInfo(), true), $sql));
                    $this->LastError = '[MPTT::Fetch] SQL Query Error ' . sprintf("Error occurred during query: %d %s %s", $sth->errorCode(), print_r($sth->errorInfo(), true), $sql);
                }
            }
        }
        else
        {
            $logger->err(sprintf("Error occurred during query: %d %s SQL:'%s'", $sth->errorCode(), print_r($sth->errorInfo(), true), $sql));
            $this->LastError = '[MPTT::Fetch] SQL Query Error ' . sprintf("Error occurred during query: %d %s %s", $sth->errorCode(), print_r($sth->errorInfo(), true), $sql);
        }
        
        return $Results;
    }

    public function MoveNode(UUID $sourceID, UUID $newParentID)
    {

    }

    public function RemoveNode(UUID $itemID, $recursive = TRUE)
    {
        $sql = "SELECT LeftNode, RightNode, Type FROM Inventory WHERE ID=:ID";
        $sth = $this->conn->prepare($sql);
        if ($sth->execute(array(':ID' => $itemID)))
        {
            $obj = $sth->fetchObject();
            $deleted_left = $obj->LeftNode;
            $deleted_right = $obj->RightNode;
            
            if ($obj->Type == 'Item')
            {
                $recursive = FALSE;
            }
        }
        
        if (empty($deleted_left) || empty($deleted_right))
        {
            $this->LastError = "[MPTT::RemoveNode] Unable to find Node for $itemID";
            return FALSE;
        }
        
        if ($recursive)
        {
            $sql = "DELETE FROM Inventory WHERE LeftNode BETWEEN :Left AND :Right";
            $sth = $this->conn->prepare($sql);
            $sth->execute(array(':Left' => $deleted_left , ':Right' => $deleted_right));
            
            $sql = "UPDATE Inventory SET 
                            LeftNode = CASE
                                WHEN LeftNode > :Left THEN 
                                    LeftNode - (:Right - :Left + 1)
                                ELSE
                                    LeftNode
                                END 
                        WHERE LeftNode > :Left OR RightNode > :Right";
            $sth = $this->conn->prepare($sql);
            $sth->execute(array(':Left' => $deleted_left , ':Right' => $deleted_right));
            
            $sql = "UPDATE Inventory SET 
                            RightNode = CASE
                                WHEN RightNode > :Right THEN
                                    RightNode - (:Right - :Left + 1)
                                ELSE
                                    RightNode
                                END
                        WHERE LeftNode > :Left OR RightNode > :Right";
            $sth = $this->conn->prepare($sql);
            $sth->execute(array(':Left' => $deleted_left , ':Right' => $deleted_right));
        }
        else
        {
            $sql = "DELETE FROM Inventory WHERE LeftNode = :Left AND RightNode = :Right";
            $sth = $this->conn->prepare($sql);
            $sth->execute(array(':Left' => $deleted_left , ':Right' => $deleted_right));
            
            $sql = "UPDATE Inventory SET 
                            LeftNode = CASE
                                WHEN LeftNode > :Left AND RightNode < :Right THEN
                                    LeftNode - 1
                                WHEN LeftNode > :Right THEN
                                    LeftNode - 2
                                ELSE
                                    LeftNode
                                END
                        WHERE LeftNode > :Left OR RightNode > :Right";
            $sth = $this->conn->prepare($sql);
            $sth->execute(array(':Left' => $deleted_left , ':Right' => $deleted_right));
            
            $sql = "UPDATE Inventory SET
                            RightNode = CASE
                                WHEN RightNode < :Right AND LeftNode >= :Left THEN
                                    RightNode - 1
                                WHEN RightNode > :Right THEN
                                    RightNode - 2
                                ELSE
                                    RightNode
                                END
                        WHERE 1";
            $sth = $this->conn->prepare($sql);
            $sth->execute(array(':Left' => $deleted_left , ':Right' => $deleted_right));
        }
        
        return TRUE;
    }

    private function AllocateSpace($num_nodes_x2, $parent_level)
    {
        $sql = "UPDATE Inventory 
                        SET LeftNode = CASE WHEN LeftNode > :Level THEN 
                            LeftNode + :X2 
                        ELSE 
                            LeftNode 
                        END 
                    WHERE RightNode >= :Level";
        
        $sth = $this->conn->prepare($sql);
        if (!$sth->execute(array(':Level' => $parent_level , ':X2' => $num_nodes_x2)))
        {
            return FALSE;
        }
        
        $sql = "UPDATE Inventory
                        SET RightNode = CASE WHEN RightNode >= :Level THEN
                            RightNode + :X2
                        ELSE
                            RightNode
                        END
                    WHERE RightNode >= :Level";
        
        $sth = $this->conn->prepare($sql);
        if (!$sth->execute(array(':Level' => $parent_level , ':X2' => $num_nodes_x2)))
        {
            return FALSE;
        }
        return TRUE;
    }
}
