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

class AddUser implements IGridService
{
    private $UserID;

    public function Execute($db, $params)
    {
        if (isset($params["UserID"], $params["Name"], $params["Email"]) && UUID::TryParse($params["UserID"], $this->UserID))
        {
            $sql = "REPLACE INTO Users (ID, Name, Email, AccessLevel) VALUES (:ID, :Name, :Email, :AccessLevel)";
            
            // Set the AccessLevel for this user
            if (isset($params["AccessLevel"]) && is_numeric($params["AccessLevel"]))
            {
                $accessLevel = (int)$params["AccessLevel"];
                if ($accessLevel > 255)
                    $accessLevel = 255;
                else if ($accessLevel < 0)
                    $accessLevel = 0;
            }
            else
            {
                $accessLevel = 0;
            }
            
            $sth = $db->prepare($sql);
            
            if ($sth->execute(array('ID' => $this->UserID, 'Name' => $params["Name"], 'Email' => $params["Email"], 'AccessLevel' => $accessLevel)))
            {
                if ($sth->rowCount() > 0)
                {
                    header("Content-Type: application/json", true);
                    echo '{ "Success": true }';
                    exit();
                }
                else
                {
                    log_message('error', "Failed updating the database");
                    
                    header("Content-Type: application/json", true);
                    echo '{ "Message": "Database update failed" }';
                    exit();
                }
            }
            else
            {
                log_message('error', sprintf("Error occurred during query: %d %s", $sth->errorCode(), print_r($sth->errorInfo(), true)));
                
                header("Content-Type: application/json", true);
                echo '{ "Message": "Database query error" }';
                exit();
            }
        }
        else
        {
            log_message('error', sprintf("Missing or invalid parameters: %s", print_r($params, true)));
            
            header("Content-Type: application/json", true);
            echo '{ "Message": "Missing or invalid parameters" }';
            exit();
        }
    }
}
