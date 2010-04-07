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

class MongoAssets
{
    private $mongo;
    private $db;
    private $coll;

    public function __construct($db)
    {
        $config =& get_config();
        $host = $config['mongo_server'];
        $dbName = $config['mongo_database'];
        
        $this->mongo = new Mongo($host, array('persist' => 'SimianGrid'));
        $this->db = $this->mongo->$dbName;
        $this->coll = $this->db->AssetData;
        
        // Build an index on AssetData.ID if one does not already exist
        $this->coll->ensureIndex(array('ID' => 1), array('unique' => 1));
    }
    
    public function GetAssetMetadata($assetID)
    {
        $obj = $this->coll->findOne(array('ID' => $assetID), array('Data' => 0));
        
        if ($obj)
        {
            $asset = new Asset();
            $asset->ID = $assetID;
            $asset->CreatorID = $obj['CreatorID'];
            $asset->ContentLength = $obj['ContentLength'];
            $asset->ContentType = $obj['ContentType'];
            $asset->CreationDate = $obj['_id']->getTimestamp();
            $asset->Data = null;
            $asset->SHA256 = $obj['SHA256'];
            $asset->Temporary = $obj['Temporary'];
            $asset->Public = $obj['Public'];
            
            return $asset;
        }
        else
        {
            log_message('debug', "Asset $assetID not found in MongoDB");
        }
        
        return null;
    }
    
    public function GetAsset($assetID)
    {
        $obj = $this->coll->findOne(array('ID' => $assetID));
        
        if ($obj)
        {
            $asset = new Asset();
            $asset->ID = $assetID;
            $asset->CreatorID = $obj['CreatorID'];
            $asset->ContentLength = $obj['ContentLength'];
            $asset->ContentType = $obj['ContentType'];
            $asset->CreationDate = $obj['_id']->getTimestamp();
            $asset->Data = $obj['data']->bin;
            $asset->SHA256 = $obj['SHA256'];
            $asset->Temporary = $obj['Temporary'];
            $asset->Public = $obj['Public'];
            
            return $asset;
        }
        else
        {
            log_message('debug', "Asset $assetID not found in MongoDB");
        }
        
        return null;
    }
    
    public function AddAsset($asset, &$created)
    {
        $obj = array(
            'CreatorID' => $asset->CreatorID,
            'ContentLength' => $asset->ContentLength,
            'ContentType' => $asset->ContentType,
            'Data' => new MongoBinData($asset->Data),
            'SHA256' => $asset->SHA256,
            'Temporary' => (bool)$asset->Temporary,
            'Public' => (bool)$asset->Public
        );
        
        if ($this->coll->update(array('ID' => $asset->ID), $obj, array('upsert' => true)))
        {
            // TODO: Why does this always return null?
            //$lastError = $this->db->lastError();
            //if (isset($lastError, $lastError['updatedExisting']) && $lastError['updatedExisting'])
            //    $created = false;
            //else
            //    $created = true;
            
            $created = true;
            log_message('debug', "Uploaded asset " . $asset->ID . " to MongoDB");
            
            return true;
        }
        else
        {
            $lastError = $this->db->lastError();
            log_message('error', "Failed to add asset " . $asset->ID . " to MongoDB: " . print_r($lastError, true));
            
            $created = false;
            return false;
        }
    }
    
    public function RemoveAsset($assetID)
    {
        if ($this->coll->remove(array('ID' => $assetID), true))
        {
            return true;
        }
        else
        {
            $lastError = $this->db->lastError();
            log_message('error', "Failed to remove asset " . $assetID . " from MongoDB: " . print_r($lastError, true));
            
            return false;
        }
    }
}
