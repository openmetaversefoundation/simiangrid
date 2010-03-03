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
require_once(BASEPATH . 'common/Scene.php');

class GetScenes implements IGridService
{
    private $MinPosition;
    private $MaxPosition;

    public function Execute($db, $params)
    {
        if (isset($params["NameQuery"]))
        {
            $sql = "SELECT ID, Name, Address, Enabled, ExtraData,
                    CONCAT('<', MinX, ',', MinY, ',', MinZ, '>') AS MinPosition,  
                    CONCAT('<', MaxX, ',', MaxY, ',', MaxZ, '>') AS MaxPosition
                    FROM Scenes WHERE Name LIKE :NameQuery";
            $nameQuery = '%' . $params["NameQuery"] . '%';
            
            if (isset($params["Enabled"]) && $params["Enabled"])
                $sql .= " AND Enabled=1";
            if (isset($params["MaxNumber"]))
                $sql .= " LIMIT " . intval($params["MaxNumber"]);
            
            $sth = $db->prepare($sql);
            
            if ($sth->execute(array(':NameQuery' => $nameQuery)))
            {
                $this->HandleQueryResponse($sth);
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
        else if (isset($params["MinPosition"]) && isset($params["MaxPosition"]) &&
            Vector3::TryParse($params["MinPosition"], $this->MinPosition) &&
            Vector3::TryParse($params["MaxPosition"], $this->MaxPosition))
        {
            $sql = "SELECT ID, Name, Address, Enabled, ExtraData,
					CONCAT('<', MinX, ',', MinY, ',', MinZ, '>') AS MinPosition,
					CONCAT('<', MaxX, ',', MaxY, ',', MaxZ, '>') AS MaxPosition
					FROM Scenes WHERE MBRIntersects(GeomFromText(:XY), XYPlane)";
            
            if (isset($params["Enabled"]) && $params["Enabled"])
                $sql .= " AND Enabled=1";
            if (isset($params["MaxNumber"]))
                $sql .= " LIMIT " . intval($params["MaxNumber"]);
            
            $sth = $db->prepare($sql);
            
            if ($sth->execute(array(':XY' => sprintf("POLYGON((%d %d, %d %d, %d %d, %d %d, %d %d))",
                $this->MinPosition->X, $this->MinPosition->Y,
                $this->MaxPosition->X, $this->MinPosition->Y,
                $this->MaxPosition->X, $this->MaxPosition->Y,
                $this->MinPosition->X, $this->MaxPosition->Y,
                $this->MinPosition->X, $this->MinPosition->Y))))
            {
                $this->HandleQueryResponse($sth);
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
        }
    }

    private function HandleQueryResponse($sth)
    {
        $found = array();
        
        while ($obj = $sth->fetchObject())
        {
            $scene = new Scene();
            $scene->SceneID = $obj->ID;
            $scene->Name = $obj->Name;
            $scene->Enabled = $obj->Enabled;
            $scene->MinPosition = Vector3::Parse($obj->MinPosition);
            $scene->MaxPosition = Vector3::Parse($obj->MaxPosition);
            $scene->Address = $obj->Address;
            if (!is_null($obj->ExtraData))
                $scene->ExtraData = $obj->ExtraData;
            else
                $scene->ExtraData = "{}";
            
            $found[] = $scene->toOSD();
        }
        
        header("Content-Type: application/json", true);
        echo '{ "Success": true, "Scenes": [' . implode(',', $found) . '] }';
        exit();
    }
}
