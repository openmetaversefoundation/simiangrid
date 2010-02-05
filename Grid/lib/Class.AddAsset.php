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

class AddAsset implements IGridService
{
    public function Execute($db, $asset, $logger)
    {
        $p = ($asset->Public) ? '1' : '0';
        $t = ($asset->Temporary) ? '1' : '0';
        
        $sql = "INSERT INTO AssetData (ID, Data, ContentType, CreatorID, SHA256, Public, Temporary)
        		VALUES (:ID, :Data, :ContentType, :CreatorID, :SHA256, " . $p . ", " . $t . ")
        		ON DUPLICATE KEY UPDATE Data=VALUES(Data), ContentType=VALUES(ContentType), CreatorID=VALUES(CreatorID),
        		SHA256=VALUES(SHA256), Public=VALUES(Public), Temporary=VALUES(Temporary)";
        
        $sth = $db->prepare($sql);
        
        if ($sth->execute(array(
        	':ID' => $asset->ID,
        	':Data' => trim($asset->Data),
        	':ContentType' => $asset->ContentType,
        	':CreatorID' => $asset->CreatorID,
        	':SHA256' => $asset->SHA256)))
        {
            $status = "";
            
            // 0 = No Update to existing asset
            // 1 = A new asset was created
            // 2 = An existing asset was updated
            if ($sth->rowCount() == 0)
            {
                $status = "Unchanged";
            }
            else if ($sth->rowCount() == 1)
            {
                $status = "Created";
            }
            else if ($sth->rowCount() == 2)
            {
                $status = "Modified";
            }
            else
            {
                $logger->err(sprintf("Unable to store asset: %s", $asset->ID));
                header("Content-Type: application/json", true);
                echo '{ "Message": "Unable to store asset" }';
                exit();
            }
            
            header("Content-Type: application/json", true);
            echo '{ "Success": true, "AssetID": "' . $asset->ID . '", "Status": "' . $status . '" }';
            exit();
        }
    }
}
