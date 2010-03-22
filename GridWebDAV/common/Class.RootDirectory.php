<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/** Simian WebDAV service
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

class RootDirectory extends Sabre_DAV_Directory
{
    private $userID;
    private $childNodes;
    private $fetched;

    function __construct($userID)
    {
        $this->userID = $userID;
        $this->fetched = false;
    }
    
    function initialize()
    {
        if (!$this->fetched)
        {
            // Fetch this root directory
            $this->childNodes = get_node_and_contents($this->userID, $this->userID);
            $this->fetched = true;
        }
    }

    function getChildren()
    {
        $this->initialize();
        
        $children = array();

        // Only one possible child of the root directory, the user's root folder
        if ($this->childNodes AND count($this->childNodes) > 0)
        {
            $children[] = new InventoryDirectory($this->childNodes[0]);
        }
        
        log_message('debug', "RootDirectory: Returning " . count($children) . " children");
        return $children;
    }

    function getChild($name)
    {
        $this->initialize();
        
        if ($this->childNodes AND count($this->childNodes) > 0 && $name === $this->childNodes[0]['Name'])
        {
            return new InventoryDirectory($this->childNodes[0]);
        }
        else
        {
            log_message('warn', "RootDirectory: The file with name: $name could not be found");
            throw new Sabre_DAV_Exception_FileNotFound("The file with name: $name could not be found");
        }
    }

    function getName()
    {
        return 'Inventory';
    }
}
