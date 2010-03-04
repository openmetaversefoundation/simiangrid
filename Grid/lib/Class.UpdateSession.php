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

function handle_uuid_parameter($paramName, $params, &$sql, &$dbValues, &$addComma)
{
    $uuid = null;
    
    if (isset($params[$paramName]) && UUID::TryParse($params[$paramName], $uuid))
    {
        if ($addComma) $sql .= ",";
        else $addComma = TRUE;
        
        $sql .= " $paramName=:$paramName";
        $dbValues[$paramName] = (string)$uuid;
    }
}

function handle_vector_parameter($paramName, $params, &$sql, &$dbValues, &$addComma)
{
    $vector = null;
    
    if (isset($params[$paramName]) && Vector3::TryParse($params[$paramName], $vector))
    {
        if ($addComma) $sql .= ",";
        else $addComma = TRUE;
        
        $sql .= " $paramName=:$paramName";
        $dbValues[$paramName] = (string)$vector;
    }
}

function handle_json_parameter($paramName, $params, &$sql, &$dbValues, &$addComma)
{
    if (isset($params[$paramName]))
    {
        if ($addComma) $sql .= ",";
        else $addComma = TRUE;
        
        $sql .= " $paramName=:$paramName";
        $dbValues[$paramName] = $params[$paramName];
    }
}

class UpdateSession implements IGridService
{
    private $SessionID;
    private $SecureSessionID;
    private $SceneID;
    private $ScenePosition;
    private $SceneLookAt;
    private $ExtraData;

    public function Execute($db, $params)
    {
        if (isset($params["SessionID"]) && UUID::TryParse($params["SessionID"], $this->SessionID))
        {
            $sql = "UPDATE Sessions SET";
            $dbValues = array('SessionID' => $this->SessionID);
            $addComma = FALSE;
            
            handle_uuid_parameter("SecureSessionID", $params, $sql, $dbValues, $addComma);
            handle_uuid_parameter("SceneID", $params, $sql, $dbValues, $addComma);
            handle_vector_parameter("ScenePosition", $params, $sql, $dbValues, $addComma);
            handle_vector_parameter("SceneLookAt", $params, $sql, $dbValues, $addComma);
            handle_json_parameter("ExtraData", $params, $sql, $dbValues, $addComma);
            
            $sql .= " WHERE SessionID=:SessionID";
            
            $sth = $db->prepare($sql);
            
            if ($sth->execute($dbValues))
            {
                if ($sth->rowCount() > 0)
                {
                    header("Content-Type: application/json", true);
                    echo '{ "Success": true }';
                    exit();
                }
                else
                {
                    // FIXME: rowCount() will be 0 if no changes were made. No 
                    // change should still be reported as a success
                    header("Content-Type: application/json", true);
                    echo '{ "Message": "Session does not exist" }';
                    exit();
                }
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
