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
require_once(BASEPATH . 'common/Session.php');

class GetSession implements IGridService
{
    private $ID;

    public function Execute($db, $params)
    {
        $sql = "SELECT * FROM Sessions WHERE";
        
        if (isset($params["UserID"]) && UUID::TryParse($params["UserID"], $this->ID))
        {
            $sql .= " UserID=:ID";
        }
        else if (isset($params["SessionID"]) && UUID::TryParse($params["SessionID"], $this->ID))
        {
            $sql .= " SessionID=:ID";
        }
        else
        {
            header("Content-Type: application/json", true);
            echo '{ "Message": "Invalid parameters" }';
            exit();
        }
        
        $sth = $db->prepare($sql);
        
        if ($sth->execute(array(':ID' => $this->ID)))
        {
            if ($sth->rowCount() > 0)
            {
                $obj = $sth->fetchObject();
                
                $session = new Session();
                $session->UserID = $obj->UserID;
                $session->SessionID = $obj->SessionID;
                $session->SecureSessionID = $obj->SecureSessionID;
                $session->SceneID = $obj->SceneID;
                $session->ScenePosition = Vector3::Parse($obj->ScenePosition);
                $session->SceneLookAt = Vector3::Parse($obj->SceneLookAt);
                $session->ExtraData = $obj->ExtraData;
                if (empty($session->ExtraData))
                    $session->ExtraData = "{}";
                
                $output = sprintf(
                	'{ "Success": true, "UserID": "%s", "SessionID": "%s", "SecureSessionID": "%s", "SceneID": "%s", "ScenePosition": %s, "SceneLookAt": %s, "ExtraData": %s }',
                    $session->UserID, $session->SessionID, $session->SecureSessionID, $session->SceneID,
                    $session->ScenePosition->toOSD(), $session->SceneLookAt->toOSD(), $session->ExtraData);
                
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
            log_message('error', sprintf("Error occurred during query: %d %s", $sth->errorCode(), print_r($sth->errorInfo(), true)));
            log_message('debug', sprintf("Query: %s", $sql));
            
            header("Content-Type: application/json", true);
            echo '{ "Message": "Database query error" }';
            exit();
        }
    }
}
