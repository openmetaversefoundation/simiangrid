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
    require_once('Interface.OSD.php');
    require_once('Class.Vector3d.php');
    
    class Scene implements IOSD
    {
        public $SceneID;
        public $Name;
        public $MinPosition;
        public $MaxPosition;
        public $Address;
        public $Enabled;
        public $ExtraData;

        public function toOSD()
        {
            $out = "{";
            foreach($this as $key => $value)
            {
                $out .= sprintf("\"%s\":", $key);
                if ($key === "ExtraData")
                {
                    $out .= $value;
                }
                else if(method_exists($value, "toOSD"))
                {
                    $out .= $value->toOSD();
                }
                else
                {
                    if(is_null($value))
                    {
                        $out .= "\"\"";
                    }
                    else if(is_array($value))
                    {

                    }
                    else if(is_numeric($value))
                    {
                        $out .= $value;
                    }
                    else if(is_string($value))
                    {
                        $out .= sprintf("\"%s\"", $value);
                    }
                    else
                    {
                        $out .= sprintf("?\"%s\"?", $value);
                    }
                }
                $out .= ',' . "";
            }
                $out = rtrim($out, ",");
                $out .= "}";
                return $out;
        }

        public static function fromOSD($osdStr)
        {
            $osdObj = json_decode($osdStr, true);
            $scene = new Scene();
            $scene->ID = $osdObj["SceneID"];
            $scene->Name = $osdObj["Name"];
            $scene->MinPosition = Vector3d::Parse('<' . implode(',', $osdObj["MinPosition"]) . '>');
            $scene->MaxPosition = Vector3d::Parse('<' . implode(',', $osdObj["MaxPosition"]) . '>');
            $scene->Address = $osdObj["Address"];
            $scene->Enabled = $osdObj["Enabled"];
            $scene->ExtraData = $osdObj["ExtraData"];
            return $scene;
        }
    }
