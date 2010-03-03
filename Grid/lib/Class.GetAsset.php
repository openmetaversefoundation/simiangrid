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

class GetAsset implements IGridService
{
    public function Execute($db, $asset)
    {
        $headrequest = (stripos($_SERVER['REQUEST_METHOD'], 'HEAD') !== FALSE);
        
        if ($headrequest)
        {
            $sql = "SELECT SHA256, UNIX_TIMESTAMP(CreationDate) AS CreationDate, CreatorID, ContentType, Public,
                		LENGTH(Data) as ContentLength FROM AssetData WHERE ID=:ID";
        }
        else
        {
            $sql = "SELECT SHA256, UNIX_TIMESTAMP(CreationDate) AS CreationDate, CreatorID, ContentType, Public,
                		LENGTH(Data) as ContentLength, Data FROM AssetData WHERE ID=:ID";
        }
        
        $sth = $db->prepare($sql);
        
        if ($sth->execute(array(':ID' => $asset->ID)))
        {
            if ($sth->rowCount() == 1)
            {
                $obj = $sth->fetchObject();
                
                // TODO: Check authentication once we support one or more auth methods
                if ($obj->Public)
                {
                    header("ETag: " . $obj->SHA256);
                    header("Last-Modified: " . gmdate(DATE_RFC850, $obj->CreationDate));
                    header("X-Asset-Creator-Id: " . $obj->CreatorID);
                    header("Content-Type: " . $obj->ContentType);
                    header("Content-Length: " . $obj->ContentLength);
                    
                    if (!$headrequest)
                        echo $obj->Data;
                    
                    exit();
                }
                else
                {
                    log_message('debug', "Access forbidden to private asset " . $asset->ID);
                    
                    header("HTTP/1.1 403 Forbidden");
                    echo 'Access forbidden';
                    exit();
                }
            }
            else
            {
                header("HTTP/1.1 404 Not Found");
                echo 'Asset not found';
                exit();
            }
        }
        else
        {
            log_message('error', sprintf("Error occurred during query: %d %s", $sth->errorCode(), print_r($sth->errorInfo(), true)));
            log_message('debug', sprintf("Query: %s", $sql));
            
            header("HTTP/1.1 500 Internal Server Error");
            echo 'Internal server error';
            exit();
        }
    }
}
