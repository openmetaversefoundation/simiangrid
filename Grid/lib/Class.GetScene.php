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

class GetScene implements IGridService
{
    private $SceneID;
    private $Position;

    public function Execute($db, $params)
    {
        $sql = "";
        
        if (isset($params["SceneID"]) && UUID::TryParse($params["SceneID"], $this->SceneID))
        {
            $sql = "SELECT ID, Name, Address, Enabled, ExtraData,
                    CONCAT('<', MinX, ',', MinY, ',', MinZ, '>') AS MinPosition,  
                    CONCAT('<', MaxX, ',', MaxY, ',', MaxZ, '>') AS MaxPosition
                    FROM Scenes WHERE ID='" . $this->SceneID . "'";
            
            if (isset($params["Enabled"]) && $params["Enabled"])
                $sql .= " AND Enabled=1";
        }
        else if (isset($params["Position"]) && Vector3::TryParse($params["Position"], $this->Position))
        {
            if (isset($params["FindClosest"]) && $params["FindClosest"])
            {
                $sql = "SELECT ID, Name, Address, Enabled, ExtraData,
                		CONCAT('<', MinX, ',', MinY, ',', MinZ, '>') AS MinPosition,  
                        CONCAT('<', MaxX, ',', MaxY, ',', MaxZ, '>') AS MaxPosition,
                        GLength(LineString(GeomFromText('POINT(" . $this->Position->X . " " . $this->Position->Y . ")'), Centroid(XYPlane)))
                        AS dist FROM Scenes";
                
                if (isset($params["Enabled"]) && $params["Enabled"])
                    $sql .= " WHERE Enabled=1";
                
                $sql .= " ORDER BY dist LIMIT 1";
            }
            else
            {
                $sql = "SELECT ID, Name, Address, Enabled, ExtraData,
                		CONCAT('<', MinX, ',', MinY, ',', MinZ, '>') AS MinPosition,  
                        CONCAT('<', MaxX, ',', MaxY, ',', MaxZ, '>') AS MaxPosition
                        FROM Scenes WHERE MBRContains(XYPlane, GeomFromText('POINT(" . $this->Position->X . " " . $this->Position->Y . ")'))";
                
                if (isset($params["Enabled"]) && $params["Enabled"])
                    $sql .= " AND Enabled=1";
                
                $sql .= " LIMIT 1";
            }
        }
        else
        {
            header("Content-Type: application/json", true);
            echo '{ "Message": "Invalid parameters" }';
            exit();
        }
        
        $sth = $db->prepare($sql);
        
        if ($sth->execute())
        {
            if ($sth->rowCount() > 0)
            {
                $obj = $sth->fetchObject();
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
                
                $out = $scene->toOSD();
                $out = substr($out, 0, -1);
                $out .= ',"Success":true}';
                
                header("Content-Type: application/json", true);
                echo $out;
                exit();
            }
            else
            {
                header("Content-Type: application/json", true);
                echo '{ "Message": "No matching scene found" }';
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
