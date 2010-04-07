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

    public function GetResults()
    {
        $config =& get_config();
        $mimes =& get_mimes();
        
        $rootFolderID = NULL;
        $items = NULL;
        
        $folders = array();
        
        if (get_inventory($config['library_owner_id'], $rootFolderID, $items))
        {
            foreach ($items as $item)
            {
                $type = isset($mimes[$item['ContentType']]) ? $mimes[$item['ContentType']] : -1;
                $folders[] = array(
                    'folder_id' => $item['ID'],
                    'name' => $item['Name'],
                    'parent_id' => $item['ParentID'],
                    'version' => (int)$item['Version'],
                    'type_default' => (int)$type
                );
            }
        }
        else
        {
            log_message('warn', 'Failed to fetch inventory skeleton for library owner ' . $config['library_owner_id']);
            
            /*$folders[] = array(
                'folder_id' => $config['library_owner_id'],
                'name' => 'Library',
                'parent_id' => '00000000-0000-0000-0000-000000000000',
                'version' => 1,
                'type_default' => 8
            );*/
        }
        
        log_message('debug', 'Returning ' . count($folders) . ' inventory folders in the library skeleton');
        return $folders;
    }
}
