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
 * @copyright  Open Metaverse Foundation
 * @license    http://www.debian.org/misc/bsd.license  BSD License (3 Clause)
 * @link       http://openmetaverse.googlecode.com/
 */

class FSAssets
{
    private $conn;
    private $accessupdate = 0;

    public function __construct($db_conn)
    {
        $config =& get_config();
        if (isset($config['access_update_interval']))
            $this->accessupdate = $config['access_update_interval'];

        if (isset($config['fsassets_path']))
            $this->assets_path = $config['fsassets_path'];

        if (!$db_conn || !($db_conn instanceof PDO))
            throw new Exception("FSAssets::__construct expects first parameter passed to be a valid database resource. " . print_r($db_conn, true));
        
        $this->conn = $db_conn;
    }

    // If you don't want to do this in your RDBMS and want to use file
    // atime instead, just set access_update_interval == 0 in your
    // SimianGrid config file.
    private function UpdateLastAccessedTime($assetID,$age)
    {
        if ($this->accessupdate == 0)
            return false;

        if ($age > $this->accessupdate)
        {
            log_message('debug',"[FSAssets] update LastAccessed for $assetID ($age)");
            $asql = "UPDATE AssetData SET LastAccessed=CURRENT_TIMESTAMP where ID=:ID";
            $asth = $this->conn->prepare($asql);
            if ($asth->execute(array(':ID' => $assetID)))
            {
                return true;
            }
        }
        return false;
    }

    private function GetAssetDirectory($asset_hash)
    {
        $asset_dir = $this->assets_path . "/" .
            substr($asset_hash, 0, 2) . "/" .
            substr($asset_hash, 2, 2);

        return $asset_dir;
    }

    public function GetAssetMetadata($assetID)
    {
        $sql = "SELECT SHA256, UNIX_TIMESTAMP(CreationDate) AS CreationDate, CreatorID, ContentType, Public,
                Temporary FROM AssetData WHERE ID=:ID";
        
        $sth = $this->conn->prepare($sql);
        
        if ($sth->execute(array(':ID' => $assetID)))
        {
            if ($sth->rowCount() == 1)
            {
                $obj = $sth->fetchObject();

                $asset = new Asset();
                $asset->ID = $assetID;
                $asset->CreatorID = $obj->CreatorID;
                $asset->ContentType = $obj->ContentType;
                $asset->CreationDate = $obj->CreationDate;
                $asset->SHA256 = $obj->SHA256;
                $asset->Temporary = $obj->Temporary;
                $asset->Public = $obj->Public;
                
                $asset_file = $this->GetAssetDirectory($asset->SHA256) . "/" .
                    $asset->SHA256;
                $asset->ContentLength = filesize($asset_file);
                $asset->Data = null;

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
        $sql = "SELECT SHA256, TIMESTAMPDIFF(DAY,IFNULL(LastAccessed,MAKEDATE(2000,1)),CURRENT_TIMESTAMP) as Age, UNIX_TIMESTAMP(CreationDate) AS CreationDate, CreatorID, ContentType, Public, Temporary
				FROM AssetData WHERE ID=:ID";

        $sth = $this->conn->prepare($sql);
        
        if ($sth->execute(array(':ID' => $assetID)))
        {
            if ($sth->rowCount() == 1)
            {
                $obj = $sth->fetchObject();
                
                $asset = new Asset();
                $asset->ID = $assetID;
                $asset->CreatorID = $obj->CreatorID;
                $asset->ContentType = $obj->ContentType;
                $asset->CreationDate = $obj->CreationDate;
                $asset->SHA256 = $obj->SHA256;
                $asset->Temporary = $obj->Temporary;
                $asset->Public = $obj->Public;
                
                $asset_file = $this->GetAssetDirectory($asset->SHA256) . "/" .
                    $asset->SHA256;
                $asset->ContentLength = filesize($asset_file);
                $asset->Data = file_get_contents($asset_file);

                $this->UpdateLastAccessedTime($assetID,$obj->Age);

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
        
        $sql = "INSERT INTO AssetData (ID, ContentType, CreatorID, SHA256, Public, Temporary, LastAccessed)
        		VALUES (:ID, :ContentType, :CreatorID, :SHA256, $p, $t, CURRENT_TIMESTAMP)
        		ON DUPLICATE KEY UPDATE SHA256=VALUES(SHA256), Public=VALUES(Public), Temporary=VALUES(Temporary)";
        
        $sth = $this->conn->prepare($sql);
        
        if ($sth->execute(array(
            ':ID' => $asset->ID,
            ':ContentType' => $asset->ContentType,
            ':CreatorID' => $asset->CreatorID,
            ':SHA256' => $asset->SHA256)))
        {
            $asset_dir = $this->GetAssetDirectory($asset->SHA256);

            if (!is_dir($asset_dir))
            {
                if (!mkdir($asset_dir, 0755, true))
                {
                    log_message('error', sprintf("Error occurred during AddAsset file writing"));
                    log_message('debug', sprintf("Failed mkdir: $asset_dir"));
                    return false;
                }
            }

            $asset_file = $asset_dir . "/" .
                $asset->SHA256;
            if (file_put_contents($asset_file, $asset->Data))
            {
                // 0 = No Update to existing asset
                // 1 = A new asset was created
                // 2 = An existing asset was updated
                $created = ($sth->rowCount() == 1);
                return true;
            }
            else
            {
                log_message('error', sprintf("Error occurred during AddAsset file writing"));
                log_message('debug', sprintf("Failed writing: $asset_file"));
            }

            return false;
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
        $asset = GetAssetMetadata($assetID);
        $asset_file = $this->GetAssetDirectory($asset->SHA256) . "/" .
                    $asset->SHA256;

        $sql = "DELETE FROM AssetData WHERE ID=:ID";
        
        $sth = $this->conn->prepare($sql);
        
        if ($sth->execute(array(':ID' => $assetID)))
        {
            if ($sth->rowCount() == 1)
            {
                if (unlink($asset_file))
                {
                    return true;
                }
                else
                {
                    log_message('debug', "RemoveAsset could not delete file: $asset_file (AssetID: $assetID)");
                }
            }
            else
            {
                log_message('debug', "RemoveAsset could not find asset: " . $assetID);
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
