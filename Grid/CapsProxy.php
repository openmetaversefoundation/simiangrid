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

require_once ('lib/Class.Logger.php');
$L = new Logger('services.ini', "PROXYSERVICE");
$logger = $L->getInstance();

$config = parse_ini_file('services.ini', true);

require_once ('lib/Class.ExceptionHandler.php');
require_once ('lib/Class.ErrorHandler.php');
require_once ('lib/Class.MySQL.php');

if (!empty($_GET['id']) && (stripos($_SERVER['REQUEST_METHOD'], 'POST') !== FALSE || stripos($_SERVER['REQUEST_METHOD'], 'GET') !== FALSE))
{
    // Connect to the database
    try
    {
        $db = new MySQL($config['Database']['host'], $config['Database']['username'], $config['Database']['password'], $config['Database']['database']);
    }
    catch (Exception $ex)
    {
        $logger->crit(sprintf("Database Exception: %d %s", mysqli_connect_errno(), mysqli_connect_error()));
        $logger->debug(sprintf("Database Exception: %s", print_r($ex, true)));
        header("HTTP/1.1 500 Internal Server Error");
        exit();
    }
    
    $resourceURL = '';
    
    $sql = "SELECT Resource FROM Capabilities WHERE ID=:ID AND ExpirationTime > NOW() LIMIT 1";
    
    $sth = $db->prepare($sql);
    
    if ($sth->execute(array(':ID' => $_GET['id'])))
    {
        if ($sth->rowCount() > 0)
        {
            $obj = $sth->fetchObject();
            $resourceURL = $obj->Resource;
        }
        else
        {
            header("HTTP/1.1 404 Not Found");
            exit();
        }
    }
    else
    {
        $logger->err(sprintf("Error occurred during query: %d %s", $sth->errorCode(), print_r($sth->errorInfo(), true)));
        $logger->debug(sprintf("Query: %s", $sql));
        header("X-Powered-By: Simian Grid Services", true);
        exit();
    }
    
    if (stripos($_SERVER['REQUEST_METHOD'], 'POST') !== FALSE)
    {
        $logger->warning("Proxy POST at " . $resourceURL);
        $r = new HttpRequest($this->server_url, HttpRequest::METH_POST);
        if (!empty($HTTP_RAW_POST_DATA))
            $r->addRawPostData($HTTP_RAW_POST_DATA);
        $r->send();
        echo $r->getResponseBody();
        exit();
    }
    else if (stripos($_SERVER['REQUEST_METHOD'], 'GET') !== FALSE)
    {
        $logger->warning("Proxy GET at " . $resourceURL);
        $r = new HttpRequest($resourceURL, HttpRequest::METH_GET);
        $r->send();
        echo $r->getResponseBody();
        exit();
    }
}
else
{
    $logger->warning('An Unsupported request method: ' . $_SERVER['REQUEST_METHOD'] . ' was requested by client for capability: ' . $_GET['id']);
    header("HTTP/1.0 405 Method Not Allowed");
    exit();
}
