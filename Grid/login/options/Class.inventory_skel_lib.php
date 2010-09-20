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
    function __construct($user)
    {}
    
    private function GetResults()
    {
        $mimes =& get_mimes();
        
        // Get the library owner ID
        $userID = NULL;
        
        if (! get_library_owner($userID))
        {
            log_message('warn', '[inventory_skel_lib] failed to get library owner');
            return array();
        }
        else
        {
            // Get the root folder ID
            $rootFolderID = $userID;
            
            if (! get_library_root_folder($userID,$rootFolderID))
            {
                log_message('warn', '[inventory_skel_lib] failed to get library root');
                return array();
            }
            
            // Get the items from the inventory folder
            $items = NULL;
            if (! get_inventory_items($userID, $rootFolderID, 0, $items))
            {
                log_message('warn', '[inventory_skel_lib] failed to get library contents');
                return array();
            }
            
            // Build the output folder structure
            $folders = array();
            
            foreach ($items as $item)
            {
                $type = isset($mimes[$item['ContentType']]) ? $mimes[$item['ContentType']] : -1;
                $parentid = $item['ParentID'];
                $folderid = $item['ID'];
                $foldername = $item['Name'];
                
                if ($folderid == $rootFolderID)
                    $parentid = '00000000-0000-0000-0000-000000000000';
                
                $folders[] = array(
                    'folder_id' => $folderid,
                    'name' => $foldername,
                    'parent_id' => $parentid,
                    'version' => (int)$item['Version'],
                    'type_default' => (int)$type);
            }
            
            log_message('debug', sprintf('[inventory_skel_lib] %d folders from %s owned by %s',
                count($folders),$rootFolderID, $userID));
            return $folders;
        }
    }
}
