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
class_exists('Inventory') || require_once ('Class.Inventory.php');
class_exists('UUID') || require_once ('Class.UUID.php');

class ALT
{
    public $LastError = '';
    
    private $conn;
    private $logger;

    public function __construct($db_conn, $logger)
    {
        $this->logger = $logger;
        if (!$db_conn || !($db_conn instanceof PDO))
            throw new Exception("ALT::__construct expects first parameter passed to be a valid database resource. " . print_r($db_conn, true));
        
        $this->conn = $db_conn;
    }

    public function GetLastError()
    {
        return $this->LastError;
    }

    public function InsertNode(Inventory $inventory)
    {
        if ($inventory instanceof InventoryFolder)
        {
            $sql = "INSERT INTO Inventory (ID, ParentID, OwnerID, CreatorID, Name, Description, ContentType, Version, 
            			ExtraData, CreationDate, Type, LeftNode, RightNode)
                    VALUES (:ID, :ParentID, :OwnerID, :OwnerID, :Name, '', :ContentType, 0, :ExtraData, CURRENT_TIMESTAMP, 
                    	'Folder', 0, 0)
                    ON DUPLICATE KEY UPDATE ParentID=VALUES(ParentID), CreatorID=VALUES(CreatorID), Name=VALUES(Name), 
                    	Description=VALUES(Description), ContentType=VALUES(ContentType), Version=Version+1";
            if (!empty($inventory->ExtraData))
                $sql .= ", ExtraData=VALUES(ExtraData)";
            
            // ParentID of UUID.Zero means this is a root folder, so we set ParentID to null
            if ($inventory->ParentID == '00000000-0000-0000-0000-000000000000')
                $inventory->ParentID = NULL;
            
            $sth = $this->conn->prepare($sql);
            
            if ($sth->execute(array(
            	':ID' => $inventory->ID,
            	':ParentID' => $inventory->ParentID,
            	':OwnerID' => $inventory->OwnerID,
            	':Name' => $inventory->Name,
            	':ContentType' => $inventory->ContentType,
                ':ExtraData' => $inventory->ExtraData)))
            {
                if ($inventory->ParentID != NULL)
                {
                    // Increment the parent folder version
                    $sql = "UPDATE Inventory SET Version=Version+1 WHERE ID=:ParentID";
                    $sth = $this->conn->prepare($sql);
                    $sth->execute(array(':ParentID' => $inventory->ParentID));
                }
                
                // Node Inserted!
                return $inventory->ID;
            }
            else
            {
                $this->LastError = sprintf("[ALT::Add/InventoryFolder] Error occurred during query: %d %s",
                        $sth->errorCode(), print_r($sth->errorInfo(), true));
                return FALSE;
            }
        }
        else if ($inventory instanceof InventoryItem)
        {
            if (isset($inventory->CreatorID))
                $creatorIDsql = ":CreatorID";
            else
                $creatorIDsql = "(SELECT CreatorID FROM AssetData WHERE ID=:AssetID)";
            
            $sql = "INSERT INTO Inventory (ID, AssetID, ParentID, OwnerID, CreatorID, Name, Description, ContentType, Version, 
            			ExtraData, CreationDate, Type, LeftNode, RightNode)
                    VALUES (:ID, :AssetID, :ParentID, :OwnerID, " . $creatorIDsql . ", :Name, 
                    	:Description, (SELECT ContentType FROM AssetData WHERE ID=:AssetID), 0, :ExtraData, CURRENT_TIMESTAMP , 'Item', 0, 0)
                    ON DUPLICATE KEY UPDATE AssetID=VALUES(AssetID), ParentID=VALUES(ParentID), CreatorID=VALUES(CreatorID), 
                    	Name=VALUES(Name), Description=VALUES(Description), ContentType=VALUES(ContentType), Version=Version+1";
            if (!empty($inventory->ExtraData))
                $sql .= ", ExtraData=VALUES(ExtraData)";
            
            $dbValues = array(
            	':ID' => $inventory->ID,
            	':AssetID' => $inventory->AssetID,
            	':ParentID' => $inventory->ParentID,
            	':OwnerID' => $inventory->OwnerID,
            	':Name' => $inventory->Name,
            	':Description' => $inventory->Description,
                ':ExtraData' => $inventory->ExtraData);
            if (isset($inventory->CreatorID))
                $dbValues['CreatorID'] = $inventory->CreatorID;
            
            $sth = $this->conn->prepare($sql);
            
            if ($sth->execute($dbValues))
            {
                // Increment the parent folder version
                $sql = "UPDATE Inventory SET Version=Version+1 WHERE ID=:ParentID";
                $sth = $this->conn->prepare($sql);
                $sth->execute(array(':ParentID' => $inventory->ParentID));
                
                return $inventory->ID;
            }
            else
            {
                $this->LastError = sprintf("[ALT::Add/InventoryItem] Error occurred during query: %d %s",
                    $sth->errorCode(), print_r($sth->errorInfo(), true));
                return FALSE;
            }
        }
        else
        {
            $this->LastError = "[ALT::Add] Must be either an InventoryFolder or InventoryItem, not " . gettype($inventory);
            return FALSE;
        }
    }

    public function FetchSkeleton($ownerID)
    {
        $sql = "SELECT * FROM Inventory WHERE OwnerID=:OwnerID AND Type='Folder' ORDER BY ParentID ASC";
        $sth = $this->conn->prepare($sql);
        
        $results = array();
        
        if ($sth->execute(array(':OwnerID' => $ownerID)))
        {
            while ($obj = $sth->fetchObject())
            {
                $results[] = $this->GetDescendant($obj);
            }
            
            return $results;
        }
        else
        {
            $this->logger->err(sprintf("Error occurred during query: %d %s SQL:'%s'", $sth->errorCode(), print_r($sth->errorInfo(), true), $sql));
            $this->LastError = '[ALT::Fetch] SQL Query Error ' . sprintf("Error occurred during query: %d %s %s", $sth->errorCode(), print_r($sth->errorInfo(), true), $sql);
            return FALSE;
        }
    }

    public function FetchDescendants($rootID, $fetchFolders = TRUE, $fetchItems = TRUE, $childrenOnly = TRUE)
    {
        if ($fetchFolders && $fetchItems)
            $fetchTypes = "'Folder','Item'";
        else if ($fetchFolders)
            $fetchTypes = "'Folder'";
        else
            $fetchTypes = "'Item'";
        
        $sql = "SELECT * FROM Inventory WHERE (ID=:ParentID OR ParentID=:ParentID) AND Type IN (" . $fetchTypes . ")";
        $sth = $this->conn->prepare($sql);
        
        $results = array();
        $rootFound = FALSE;
        
        // Hold a spot for the item we requested
        $results[] = '!';
        
        if ($sth->execute(array(':ParentID' => $rootID)))
        {
            while ($item = $sth->fetchObject())
            {
                $descendant = $this->GetDescendant($item);
                
                // The item we requested goes in the first slot of the array
                if ($descendant->ID == $rootID)
                {
                    $results[0] = $descendant;
                    $rootFound = TRUE;
                }
                else
                {
                    $results[] = $descendant;
                }
            }
        }
        else
        {
            $this->logger->err(sprintf("Error occurred during query: %d %s SQL:'%s'", $sth->errorCode(), print_r($sth->errorInfo(), true), $sql));
            $this->LastError = '[ALT::Fetch] SQL Query Error ' . sprintf("Error occurred during query: %d %s %s", $sth->errorCode(), print_r($sth->errorInfo(), true), $sql);
        }
        
        if ($rootFound)
            return $results;
        else
            return FALSE;
    }

    public function MoveNodes($sourceIDs, UUID $newParentID)
    {
        if (!is_array($sourceIDs) || count($sourceIDs) < 1)
        {
            $this->LastError = "[ALT::Move] No list of items to be moved was given";
            return FALSE;
        }
        
        $sql = "UPDATE Inventory SET ParentID=:FolderID WHERE ID=:ID0";
        $i = 0;
        
        $dbValues = array();
        $dbValues[':FolderID'] = $newParentID;
        
        foreach ($sourceIDs as $itemID)
        {
            $dbValues[':ID' . $i] = $itemID;
            
            if ($i > 0)
                $sql .= " OR ID=:ID" . $i;
            
            ++$i;
        }
        
        $sth = $this->conn->prepare($sql);
        
        if ($sth->execute($dbValues))
        {
            return TRUE;
        }
        else
        {
            $this->logger->err(sprintf("Error occurred during query: %d %s SQL:'%s'", $sth->errorCode(), print_r($sth->errorInfo(), true), $sql));
            $this->LastError = '[ALT::Move] SQL Query Error ' . sprintf("Error occurred during query: %d %s %s", $sth->errorCode(), print_r($sth->errorInfo(), true), $sql);
            return FALSE;
        }
    }

    public function RemoveNode(UUID $itemID, $childrenOnly = FALSE)
    {
        if (!$childrenOnly)
            $sql = "DELETE FROM Inventory WHERE ID=:ItemID";
        else
            $sql = "DELETE FROM Inventory WHERE ParentID=:ItemID";
        
        $sth = $this->conn->prepare($sql);
        
        if ($sth->execute(array(':ItemID' => $itemID)))
        {
            return TRUE;
        }
        else
        {
            $this->logger->err(sprintf("Error occurred during query: %d %s SQL:'%s'", $sth->errorCode(), print_r($sth->errorInfo(), true), $sql));
            $this->LastError = '[ALT::Remove] SQL Query Error ' . sprintf("Error occurred during query: %d %s %s", $sth->errorCode(), print_r($sth->errorInfo(), true), $sql);
            return FALSE;
        }
    }
    
    private function GetDescendant($item)
    {
        $descendant = NULL;
        
        if ($item->Type == 'Folder')
        {
            $descendant = new InventoryFolder(UUID::Parse($item->ID));
            
            if (!UUID::TryParse($item->ParentID, $descendant->ParentID))
                $descendant->ParentID = UUID::Parse(UUID::Zero);
            
            $descendant->OwnerID = UUID::Parse($item->OwnerID);
            $descendant->Name = $item->Name;
            $descendant->ContentType = $item->ContentType;
            $descendant->Version = $item->Version;
            $descendant->ExtraData = $item->ExtraData;
            $descendant->ChildCount = ((int)$item->RightNode - (int)$item->LeftNode - 1) / 2;
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
}
