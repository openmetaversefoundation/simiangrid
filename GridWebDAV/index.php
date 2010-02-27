<?php
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

require_once 'Sabre.autoload.php';
require_once 'Class.InventoryFile.php';
require_once 'Class.InventoryDirectory.php';
require_once 'Class.RootDirectory.php';
require_once 'Class.Curl.php';
require_once 'config/config.php';

function get_a1_hash($userName, &$userID)
{
    global $config;
    $userID = null;
    
    $response = webservice_post($config['user_service'], array(
    	'RequestMethod' => 'GetIdentities',
        'Identifier' => $userName)
    );
    
    if (!empty($response['Success']) && is_array($response['Identities']))
    {
        $identities = $response['Identities'];
        
        foreach ($identities as $identity)
        {
            if ($identity['Type'] === 'a1hash' && $identity['Enabled'])
            {
                $userID = $identity['UserID'];
                return $identity['Credential'];
            }
        }
        
        // User or WebDAV identity not found
        return null;
    }
    
    error_log('Error fetching identities for user ' . $userName . ': ' . $response['Message']);
    return null;
}

$realm = 'Inventory';

$auth = new Sabre_HTTP_DigestAuth();
$auth->setRealm($realm);
$auth->init();

$userID = null;
$userName = $auth->getUsername();

// Lookup the WebDAV ("a1hash") identity for the given username
$hash = get_a1_hash($userName, $userID);

// Check if the lookup was successful and that the credentials match
if (!$hash || !$auth->validateA1($hash))
{
    error_log("WebDAV Authentication failed for " . $userName);
    $auth->requireLogin();
    echo "Authentication required\n";
    exit();
}

$rootDirectory = new RootDirectory($userID);

$tree = new Sabre_DAV_ObjectTree($rootDirectory);

$server = new Sabre_DAV_Server($tree);

$server->setBaseUri('/');

$lockBackend = new Sabre_DAV_Locks_Backend_FS('tmp');
$lockPlugin = new Sabre_DAV_Locks_Plugin($lockBackend);
$server->addPlugin($lockPlugin);

$server->exec();
