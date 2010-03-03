<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Simian grid services
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

class Session implements IOSD
{
    public $UserID;
    public $SessionID;
    public $SecureSessionID;
    public $SceneID;
    public $ScenePosition;
    public $SceneLookAt;
    public $ExtraData;

    public function __construct()
    {
        $this->UserID = new UUID();
        $this->SessionID = new UUID();
        $this->SecureSessionID = new UUID();
        $this->SceneID = new UUID();
        $this->ScenePosition = new Vector3();
        $this->SceneLookAt = new Vector3();
        $this->ExtraData = '';
    }

    public function toOSD()
    {
        $out = "{";
        foreach ($this as $key => $value)
        {
            $out .= sprintf("\"%s\":", $key);
            if (method_exists($value, "toOSD"))
            {
                $out .= $value->toOSD();
            }
            else
            {
                if (is_null($value))
                {
                    $out .= "\"\"";
                }
                else if (is_array($value))
                {

                }
                else if (is_numeric($value))
                {
                    $out .= $value;
                }
                else if (is_string($value))
                {
                    $out .= sprintf("\"%s\"", $value);
                }
                else if (is_bool($value))
                {
                    $out .= ($value) ? "true" : "false";
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

    public static function fromOSD($osd)
    {
        if (!isset($osd)) return NULL;
        if (!is_array($osd)) $osd = json_decode($osd, true);
        if (!isset($osd)) return NULL;
        
        $session = new Session();
        $session->UserID = UUID::Parse($osd['UserID']);
        $session->SessionID = UUID::Parse($osd['SessionID']);
        $session->SecureSessionID = UUID::Parse($osd['SecureSessionID']);
        $session->SceneID = UUID::Parse($osd['SceneID']);
        $session->ScenePosition = Vector3::Parse($osd['ScenePosition']);
        $session->SceneLookAt = Vector3::Parse($osd['SceneLookAt']);
        $session->ExtraData = json_decode($osd['ExtraData']);
        
        return $session;
    }
}
