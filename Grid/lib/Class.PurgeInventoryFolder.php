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
interface_exists('IGridService') || require_once ('Interface.GridService.php');
class_exists('UUID') || require_once ('Class.UUID.php');
class_exists('ALT') || require_once ('Class.ALT.php');

class PurgeInventoryFolder implements IGridService
{
    private $inventory;
    
    public function Execute($db, $params, $logger)
    {
        $ownerID = NULL;
        $folderID = NULL;
        
        if (!isset($params["OwnerID"], $params["FolderID"]) || !UUID::TryParse($params["OwnerID"], $ownerID) || !UUID::TryParse($params["FolderID"], $folderID))
        {
            header("Content-Type: application/json", true);
            echo '{ "Message": "Invalid parameters" }';
            exit();
        }
        
        $this->inventory = new ALT($db, $logger);
        
        if ($this->inventory->RemoveNode($folderID, TRUE))
        {
            header("Content-Type: application/json", true);
            echo '{ "Success": true }';
            exit();
        }
        else
        {
            header("Content-Type: application/json", true);
            echo '{ "Message": "Database query error" }';
            exit();
        }
    }
}
