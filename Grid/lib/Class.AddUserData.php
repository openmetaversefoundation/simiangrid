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

function escape_json($json)
{
    $json = str_replace('\n', '\\n', $json);
    $json = str_replace('\t', '\\t', $json);
    $json = str_replace('\\', '\\\\', $json);
    $json = str_replace('"', '\"', $json);
    $json = str_replace("'", "\\'", $json);
    
    return $json;
}

class AddUserData implements IGridService
{
    private $UserID;

    public function Execute($db, $params)
    {
        if (isset($params["UserID"]) && UUID::TryParse($params["UserID"], $this->UserID))
        {
            unset($params["RequestMethod"]);
            unset($params["UserID"]);
            
            if (count($params) > 0)
            {
                $sql = "REPLACE INTO UserData (ID, `Key`, `Value`) VALUES";
                $values = array(":ID" => $this->UserID);
                $i = 0;
                
                foreach ($params as $key => $value)
                {
                    if ($key === "UserID" || $key === "Name" || $key === "Email")
                    {
                        header("Content-Type: application/json", true);
                        echo '{ "Message": "Field name is reserved" }';
                        exit();
                    }
                    
                    if ($i > 0)
                        $sql .= ',';
                    $sql .= '(:ID, :Key' . $i . ', :Value' . $i . ')';
                    
                    $values[':Key' . $i] = preg_replace('/[^a-zA-Z0-9\s]/', '', $key);
                    $values[':Value' . $i] = escape_json($value);
                    
                    ++$i;
                }
                
                $sth = $db->prepare($sql);
                
                if ($sth->execute($values))
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
                header("Content-Type: application/json", true);
                echo '{ "Message": "No fields specified" }';
                exit();
            }
        }
        else
        {
            header("Content-Type: application/json", true);
            echo '{ "Message": "Missing or invalid UserID" }';
            exit();
        }
    }
}
