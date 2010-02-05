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
class_exists('UUID') || require_once ('Class.UUID.php');
class_exists('Vector3d') || require_once ('Class.Vector3d.php');

class GetSession implements IGridService
{
    private $SessionID;

    public function Execute($db, $params, $logger)
    {
        if (isset($params["SessionID"]) && UUID::TryParse($params["SessionID"], $this->SessionID))
        {
            $sql = "SELECT * FROM Sessions WHERE SessionID=:SessionID";
            
            $sth = $db->prepare($sql);
            
            if ($sth->execute())
            {
                if ($sth->rowCount() > 0)
                {
                    $obj = $sth->fetchObject();
                    
                    $ScenePosition = Vector3d::Parse($obj->ScenePosition);
                    $SceneLookAt = Vector3d::Parse($obj->SceneLookAt);
                    $ExtraData = "{}";
                    if (strlen($obj->ExtraData) > 0)
                        $ExtraData = $obj->ExtraData;
                    
                    $output = sprintf('{ "Success": true, "UserID": "%s", "SessionID": "%s", "SecureSessionID": "%s", "SceneID": "%s", "ScenePosition": %s, "SceneLookAt": %s, ExtraData: %s }',
                        $obj->UserID, $obj->SessionID, $obj->SecureSessionID, $obj->SceneID, $ScenePosition, $SceneLookAt, $ExtraData);
                    
                    header("Content-Type: application/json", true);
                    echo $output;
                    exit();
                }
                else
                {
                    header("Content-Type: application/json", true);
                    echo '{ "Message": "Session not found" }';
                    exit();
                }
            }
            else
            {
                $logger->err(sprintf("Error occurred during query: %d %s", $sth->errorCode(), print_r($sth->errorInfo(), true)));
                $logger->debug(sprintf("Query: %s", $sql));
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
