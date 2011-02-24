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
 * @author     John Hurliman <http://software.intel.com/en-us/blogs/author/john-hurliman/>
 * @copyright  Open Metaverse Foundation
 * @license    http://www.debian.org/misc/bsd.license  BSD License (3 Clause)
 * @link       http://openmetaverse.googlecode.com/
 */

class Appearance
{
    private $_wearables;
    private $_attachments;
    private $_packedapp;

    // -----------------------------------------------------------------
    // -----------------------------------------------------------------
    function __construct($user)
    {
        try {

            // log_message('warn',"[APPEARANCE] entering appearance class");
            if (isset($user['LLPackedAppearance']))
            {
                $appearance = json_decode($user['LLPackedAppearance'],true);
                $this->InitializeFromPackedAppearance($appearance);

                return;
            }

            if (isset($user['LLAppearance']) && isset($user['LLAttachments']))
            {
                $wearables = json_decode($user['LLAppearance'],true);
                $attachments = json_decode($user['LLAttachments'],true);
                $this->InitializeFromWearables($wearables,$attachments);

                return;
            }
        }
        catch (Exception $e)
        {
            log_message('error',"[LOGIN] exception: " . $e->getMessage());
            throw $e;
        }

        log_message('warn',"[LOGIN] no appearance found");

        $_wearables = null;
        $_attachments = null;
        $_packedapp = null;
    }

    // -----------------------------------------------------------------
    // -----------------------------------------------------------------
    function GetWearables()
    {
        return $this->_wearables;
    }

    // -----------------------------------------------------------------
    // -----------------------------------------------------------------
    function GetAttachments()
    {
        return $this->_attachments;
    }

    // -----------------------------------------------------------------
    // -----------------------------------------------------------------
    function GetPackedAppearance()
    {
        return $this->_packedapp;
    }

    // -----------------------------------------------------------------
    // -----------------------------------------------------------------
    function InitializeFromPackedAppearance($packed)
    {
        log_message('debug',"[APPEARANCE] initialize from packed appearance");
        $this->_packedapp = $packed;
        $this->PackedAppearance2Wearables();
    }

    // -----------------------------------------------------------------
    // -----------------------------------------------------------------
    private function PackedAppearance2Wearables()
    {
        // convert the wearables
        $this->_wearables = array();
        if (isset($this->_packedapp["wearables"]))
        {
            // Total number of wearables is 15 for viewer2 but old opensim code
            // expects max of 13 and is not tolerant of extras :-(
            // Trust that new opensim distributions use packed_appearance
            for ($i = 0; $i < 13; $i++)
            {
                $itemid = UUID::Zero;
                $assetid = UUID::Zero;

                if (is_array($this->_packedapp["wearables"]) && isset($this->_packedapp["wearables"][$i]))
                {
                    $wearable = $this->_packedapp["wearables"][$i];
                    if (is_array($wearable) && isset($wearable[0]))
                    {
                        $itemid = isset($wearable[0]['item']) ?
                            $wearable[0]['item'] : UUID::Zero;
                        $assetid = isset($wearable[0]['asset']) ?
                            $wearable[0]['asset'] : UUID::Zero;
                    }
                }

                $this->_wearables[] = $itemid;
                $this->_wearables[] = $assetid;
            }
        }

        // convert attachments
        $this->_attachments = array();

        if (isset($this->_packedapp["attachments"]))
        {
            for ($i = 0; $i < count($this->_packedapp["attachments"]); $i++)
            {
                $attachment = $this->_packedapp["attachments"][$i];
                if ($attachment != null)
                {
                    $point = $attachment["point"];
                    $itemid = $attachment["item"];
                    $assetid = isset($attachment["asset"]) ?
                        $attachment["asset"] : UUID::Zero;
                    $this->_attachments[$i] = array('point' => $point, 'item' => $itemid);
                }
            }
        }
    }

    // -----------------------------------------------------------------
    // -----------------------------------------------------------------
    function InitializeFromWearables($wearables, $attachments)
    {
        log_message('debug',"[APPEARANCE] initialize from wearables");

        $this->_wearables = array();
        if (isset($wearables))
        {
            $this->add_wearable($wearables, 'Shape');
            $this->add_wearable($wearables, 'Skin');
            $this->add_wearable($wearables, 'Hair');
            $this->add_wearable($wearables, 'Eyes');
            $this->add_wearable($wearables, 'Shirt');
            $this->add_wearable($wearables, 'Pants');
            $this->add_wearable($wearables, 'Shoes');
            $this->add_wearable($wearables, 'Socks');
            $this->add_wearable($wearables, 'Jacket');
            $this->add_wearable($wearables, 'Gloves');
            $this->add_wearable($wearables, 'Undershirt');
            $this->add_wearable($wearables, 'Underpants');
            $this->add_wearable($wearables, 'Skirt');
        }
    
        log_message('debug',"[APPEARANCE] initialize from attachments");

        $this->_attachments = array();
        if (isset($attachments))
        {
            $i = 0;
        
            foreach ($attachments as $key => $item)
            {
                if (substr($key, 0, 4) === '_ap_')
                {
                    $point = (int)substr($key, 4);
                    $this->_attachments[$i++] = array('point' => $point, 'item' => $item);
                }
            }
        }

        $this->Wearables2PackedAppearance();
    }

    // -----------------------------------------------------------------
    // -----------------------------------------------------------------
    private function Wearables2PackedAppearance()
    {
        $uuid = null;

        $this->_packedapp = array();
        $this->_packedapp["serial"] = 1;

        $this->_packedapp["visualparams"] = array_fill(0,218,150); /* set the default vparam to 150 */
        $this->_packedapp["textures"] = array_fill(0,21,"c228d1cf-4b5d-4ba8-84f4-899a0796aa97");
        $this->_packedapp["hipoffset"] = 0;
        $this->_packedapp["height"] = 0;

        // Convert the wearables
        $this->_packedapp["wearables"] = array();
        for ($i = 0; $i < 15; $i++)
        {
            $itemid = UUID::Zero;
            if (isset($this->_wearables[2 * $i]) && UUID::TryParse($this->_wearables[2 * $i],$uuid))
                $itemid = $uuid;

            $assetid = UUID::Zero;
            if (isset($this->_wearables[2 * $i + 1]) && UUID::TryParse($this->_wearables[2 * $i  + 1],$uuid))
                $assetid = $uuid;

            $this->_packedapp["wearables"][$i] = array(array('item' => $itemid, 'asset' => $assetid));
        }

        // Convert the attachments
        $this->_packedapp["attachments"] = array();
        for ($i = 0; $i < count($this->_attachments); $i++)
        {            
            $point = $this->_attachments[$i]['point'];
            $itemid = $this->_attachments[$i]['item'];
            $assetid = UUID::Zero;
            $this->_packedapp["attachments"][$i] = array('point' => $point, 'item' => $itemid, 'asset' => $assetid);
        }
    }

    // -----------------------------------------------------------------
    // -----------------------------------------------------------------
    private function add_wearable($wearables, $wearableName)
    {
        $uuid = null;
    
        // ItemID
        $itemid = UUID::Zero;
        if (isset($wearables[$wearableName . 'Item']) && UUID::TryParse($wearables[$wearableName . 'Item'], $uuid))
            $itemid = $uuid;
    
        // AssetID
        $assetid = UUID::Zero;
        if (isset($wearables[$wearableName . 'Asset']) && UUID::TryParse($wearables[$wearableName . 'Asset'], $uuid))
            $assetid = $uuid;

        $this->_wearables[] = $itemid;
        $this->_wearables[] = $assetid;
    }

}
