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

class SQLAssets
{
    private $conn;

    public function __construct($db_conn)
    {
        if (!$db_conn || !($db_conn instanceof PDO))
            throw new Exception("SQLAssets::__construct expects first parameter passed to be a valid database resource. " . print_r($db_conn, true));
        
        $this->conn = $db_conn;
    }
    
    public function GetAssetMetadata($assetID)
    {
        $sql = "SELECT SHA256, UNIX_TIMESTAMP(CreationDate) AS CreationDate, CreatorID, ContentType, Public,
				Temporary, LENGTH(Data) as ContentLength FROM AssetData WHERE ID=:ID";
        
        $sth = $this->conn->prepare($sql);
        
        if ($sth->execute(array(':ID' => $assetID)))
        {
            if ($sth->rowCount() == 1)
            {
                $obj = $sth->fetchObject();
                
                $asset = new Asset();
                $asset->ID = $assetID;
                $asset->CreatorID = $obj->CreatorID;
                $asset->ContentLength = $obj->ContentLength;
                $asset->ContentType = $obj->ContentType;
                $asset->CreationDate = $obj->CreationDate;
                $asset->Data = null;
                $asset->SHA256 = $obj->SHA256;
                $asset->Temporary = $obj->Temporary;
                $asset->Public = $obj->Public;
                
                return $asset;
            }
        }
        else
        {
            log_message('error', sprintf("Error occurred during GetAssetMetadata query: %d %s",
                $sth->errorCode(), print_r($sth->errorInfo(), true)));
            log_message('debug', sprintf("Query: %s", $sql));
        }
        
        return null;
    }
    
    public function GetAsset($assetID)
    {
        $sql = "SELECT SHA256, UNIX_TIMESTAMP(CreationDate) AS CreationDate, CreatorID, ContentType, Public,
				LENGTH(Data) as ContentLength, Data FROM AssetData WHERE ID=:ID";
        
        $sth = $this->conn->prepare($sql);
        
        if ($sth->execute(array(':ID' => $assetID)))
        {
            if ($sth->rowCount() == 1)
            {
                $obj = $sth->fetchObject();
                
                $asset = new Asset();
                $asset->ID = $assetID;
                $asset->CreatorID = $obj->CreatorID;
                $asset->ContentLength = $obj->ContentLength;
                $asset->ContentType = $obj->ContentType;
                $asset->CreationDate = $obj->CreationDate;
                $asset->Data = $obj->Data;
                $asset->SHA256 = $obj->SHA256;
                $asset->Temporary = $obj->Temporary;
                $asset->Public = $obj->Public;
                
                return $asset;
            }
        }
        else
        {
            log_message('error', sprintf("Error occurred during GetAsset query: %d %s",
                $sth->errorCode(), print_r($sth->errorInfo(), true)));
            log_message('debug', sprintf("Query: %s", $sql));
        }
        
        return null;
    }
    
    public function AddAsset($asset, &$created)
    {
        $p = ($asset->Public) ? '1' : '0';
        $t = ($asset->Temporary) ? '1' : '0';
        
        $sql = "INSERT INTO AssetData (ID, Data, ContentType, CreatorID, SHA256, Public, Temporary)
        		VALUES (:ID, :Data, :ContentType, :CreatorID, :SHA256, $p, $t)
        		ON DUPLICATE KEY UPDATE Data=VALUES(Data), SHA256=VALUES(SHA256), Public=VALUES(Public), Temporary=VALUES(Temporary)";
        
        $sth = $this->conn->prepare($sql);
        
        if ($sth->execute(array(
        	':ID' => $asset->ID,
        	':Data' => trim($asset->Data),
        	':ContentType' => $asset->ContentType,
        	':CreatorID' => $asset->CreatorID,
        	':SHA256' => $asset->SHA256)))
        {            
            // 0 = No Update to existing asset
            // 1 = A new asset was created
            // 2 = An existing asset was updated
            $created = ($sth->rowCount() == 1);
            return true;
        }
        else
        {
            log_message('error', sprintf("Error occurred during AddAsset query: %d %s",
                $sth->errorCode(), print_r($sth->errorInfo(), true)));
            log_message('debug', sprintf("Query: %s", $sql));
        }
        
        return false;
    }
    
    public function RemoveAsset($assetID)
    {
        $sql = "DELETE FROM AssetData WHERE ID=:ID";
        
        $sth = $this->conn->prepare($sql);
        
        if ($sth->execute(array(':ID' => $assetID)))
        {
            if ($sth->rowCount() == 1)
            {
                return true;
            }
            else
            {
                log_message('debug', "RemoveAsset could not find asset " . $asset->ID);
            }
        }
        else
        {
            log_message('error', sprintf("Error occurred during RemoveAsset query: %d %s",
                $sth->errorCode(), print_r($sth->errorInfo(), true)));
            log_message('debug', sprintf("Query: %s", $sql));
        }
        
        return false;
    }
}
