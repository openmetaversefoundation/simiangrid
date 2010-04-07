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
 * @author     John Hurliman <http://software.intel.com/en-us/blogs/author/john-hurliman/>
 * @copyright  Open Metaverse Foundation
 * @license    http://www.debian.org/misc/bsd.license  BSD License (3 Clause)
 * @link       http://openmetaverse.googlecode.com/
 */

class GetGenerics implements IGridService
{
    public function Execute($db, $params)
    {
        $ownerID = null;
        
        if (isset($params["Type"]))
        {
            $dbValues = array(':Type' => $params["Type"]);
            
            if (isset($params["OwnerID"]) && UUID::TryParse($params["OwnerID"], $ownerID))
            {
                $sql = "SELECT `OwnerID`, `Key`, `Value` FROM Generic WHERE `OwnerID`=:OwnerID AND `Type`=:Type";
                $dbValues[':OwnerID'] = $ownerID;
                
                if (isset($params["Key"]))
                {
                    $sql .= " AND `Key`=:Key";
                    $dbValues[':Key'] = $params["Key"];
                }
            }
            else if (isset($params["Key"]))
            {
                $sql = "SELECT `OwnerID`, `Key`, `Value` FROM Generic WHERE `Key`=:Key AND `Type`=:Type";
                $dbValues[':Key'] = $params["Key"];
            }
            else
            {
                header("Content-Type: application/json", true);
                echo '{ "Message": "Invalid parameters" }';
                exit();
            }
            
            $sth = $db->prepare($sql);
            
            if ($sth->execute($dbValues))
            {
                $found = array();
                
                while ($obj = $sth->fetchObject())
                {
                    $found[] = json_encode(array('OwnerID' => $obj->OwnerID, 'Key' => $obj->Key, 'Value' => $obj->Value));
                }
                
                header("Content-Type: application/json", true);
                echo '{ "Success": true, "Entries": [' . implode(',', $found) . '] }';
                exit();
            }
            else
            {
                log_message('error', sprintf("Error occurred during query: %d %s", $sth->errorCode(), print_r($sth->errorInfo(), true)));
                log_message('debug', sprintf("Query: %s", $sql));
                
                header("Content-Type: application/json", true);
                echo '{ "Message": "Database query error" }';
                exit();
            }
        }
        else
        {
            header("Content-Type: application/json", true);
            echo '{ "Message": "Invalid parameters" }';
            exit();
        }
    }
}
