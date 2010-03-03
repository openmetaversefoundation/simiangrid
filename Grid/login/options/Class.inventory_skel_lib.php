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

class inventory_skel_lib
{
    private $User;

    function __construct($user)
    {
        $this->User = $user;
    }

    public function GetResults()
    {
        $mimes =& get_mimes();

        $folders = array();
        
        // FIXME:
        return $folders;
        
//        $jsonStr = NULL;
//        if (try_make_service_request(array('RequestMethod' => 'GetInventory' , 'OwnerID' => $this->Config["shared_folder_owner_id"] , 'ItemID' => $this->Config["shared_folder_id"] , 'IncludeFolders' => TRUE , 'IncludeItems' => FALSE , 'ChildrenOnly' => TRUE), $jsonStr))
//        {
//            $jsonObj = json_decode($jsonStr, true);
//            if (json_last_error() == JSON_ERROR_NONE)
//            {
//                for ($i = 0; $i < count($jsonObj); $i++)
//                {
//                    $type = isset($InventoryMimeMap[$jsonObj[$i]["ContentType"]]) ? $InventoryMimeMap[$jsonObj[$i]["ContentType"]] : -1;
//                    $folders[] = array('folder_id' => $jsonObj[$i]["ID"] , 'name' => $jsonObj[$i]["Name"] , 'parent_id' => $jsonObj[$i]["ParentID"] , 'version' => $jsonObj[$i]["Version"] , 'type_default' => $type);
//                }
//            }
//            else
//            {
//                $logger->err(sprintf("JSON Decode Error: %s. string: '%s'", json_last_error(), $jsonStr));
//            }
//        }
//        
//        return $folders;
    }
}
