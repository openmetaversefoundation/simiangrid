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
    interface_exists('IGridService') || require_once('Interface.GridService.php');
    class_exists('UUID') || require_once('Class.UUID.php');
    class_exists('Scene') || require_once('Class.Scene.php');
    class_exists('Vector3d') || require_once('Class.Vector3d.php');

    class AddScene implements IGridService
    {
        private $Scene;
        public function Execute($db, $params, $logger)
        {
            $this->Scene = new Scene();
            
            if (isset($params["SceneID"], $params["Enabled"]) && !$params["Enabled"] && UUID::TryParse($params["SceneID"], $this->Scene->ID))
            {
                // Scene disabling
                $sql = "UPDATE Scenes SET Enabled=0 WHERE ID=:ID";
                
                $sth = $db->prepare($sql);
                if($sth->execute(array(':ID'=>$this->Scene->ID->UUID)))
                {
                    if($sth->rowCount() > 0)
                    {
                        header("Content-Type: application/json", true);
                        echo '{ "Success": true }';
                        exit;
                    }
                    else
                    {
                        $logger->err("Failed updating the database");
                        header("Content-Type: application/json", true);
                        echo '{ "Message": "Database update failed" }';
                        exit;
                    }
                }
                else
                {
                    $logger->err(sprintf("Error occurred during query: %d %s", $sth->errorCode(), print_r($sth->errorInfo(),true)));
                    header("Content-Type: application/json", true);
                    echo '{ "Message": "Database query error" }';
                    exit;
                }
            }
            else
            {
                // Scene enabling
                if(!isset($params["SceneID"], $params["Name"], $params["MinPosition"], $params["MaxPosition"], $params["Address"], $params["Enabled"])
                    || !UUID::TryParse($params["SceneID"], $this->Scene->ID)
                    || !Vector3d::TryParse($params["MinPosition"], $this->Scene->MinPosition) 
                    || !Vector3d::TryParse($params["MaxPosition"], $this->Scene->MaxPosition))
                {
                    $logger->err(sprintf("AddScene: Unable to parse passed parameters or parameter missing: '%s'", print_r($params,true)));
                    header("Content-Type: application/json", true);
                    echo '{ "Message": "Invalid parameters" }';
                    exit;
                }

                $this->Scene->Address = trim($params["Address"]);
                $this->Scene->Name = trim($params["Name"]);
                $this->Scene->Enabled = $params["Enabled"];
                
                if (isset($params["ExtraData"]))
                    { $this->Scene->ExtraData = $params["ExtraData"]; }
                else
                    { $this->Scene->ExtraData = NULL; }

                $sql = "REPLACE INTO Scenes (ID, Name, MinX, MinY, MinZ, MaxX, MaxY, MaxZ, Address, Enabled, ExtraData, XYPlane) 
                        VALUES (:ID, :Name, :MinX, :MinY,  :MinZ, :MaxX, :MaxY, :MaxZ, :Address, :Enabled, :ExtraData, GeomFromText(:XY))";
    
                $sth = $db->prepare($sql);
                if($sth->execute(array(':ID'=>$this->Scene->ID->UUID,
                                       ':Name'=>$this->Scene->Name,
                                       ':MinX'=>$this->Scene->MinPosition->X,
                                       ':MinY'=>$this->Scene->MinPosition->Y,
                                       ':MinZ'=>$this->Scene->MinPosition->Z,
                                       ':MaxX'=>$this->Scene->MaxPosition->X,
                                       ':MaxY'=>$this->Scene->MaxPosition->Y,
                                       ':MaxZ'=>$this->Scene->MaxPosition->Z,
                                       ':Address'=>$this->Scene->Address,
                                       ':Enabled'=>$this->Scene->Enabled,
                                       ':ExtraData'=>$this->Scene->ExtraData,
                                       ':XY'=>sprintf("POLYGON((%d %d, %d %d, %d %d, %d %d, %d %d))",
                                           $this->Scene->MinPosition->X,
                                           $this->Scene->MinPosition->Y,
                                           $this->Scene->MaxPosition->X,
                                           $this->Scene->MinPosition->Y,
                                           $this->Scene->MaxPosition->X,
                                           $this->Scene->MaxPosition->Y,
                                           $this->Scene->MinPosition->X,
                                           $this->Scene->MaxPosition->Y,
                                           $this->Scene->MinPosition->X,
                                           $this->Scene->MinPosition->Y)
                                )))
                {
                    if($sth->rowCount() > 0)
                    {
                        header("Content-Type: application/json", true);
                        echo '{ "Success": true }';
                        exit;
                    }
                    else
                    {
                        $logger->err("Failed updating the database");
                        header("Content-Type: application/json", true);
                        echo '{ "Message": "Database update failed" }';
                        exit;
                    }
                }
                else
                {
                    $logger->err(sprintf("Error occurred during query: %d %s", $sth->errorCode(), print_r($sth->errorInfo(), true)));
                    header("Content-Type: application/json", true);
                    echo '{ "Message": "Database query error" }';
                    exit;
                }
            }
        }
    }
