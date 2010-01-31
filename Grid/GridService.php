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
 ob_start();

      $fh = fopen("debug.log", 'w');
         echo '--- $_SERVER';
         print_r($_SERVER);
         echo '--- $_GET';
         print_r($_GET);
         echo '--- $_POST';
         print_r($_POST);

         fwrite($fh, ob_get_contents());
         ob_end_clean();
      fclose($fh);

    require_once('lib/Class.Logger.php');
    $L = new Logger('services.ini', "GRIDSERVICE");
    $logger = $L->getInstance();

    $config = parse_ini_file('services.ini', true);

    require_once('lib/Class.ExceptionHandler.php');
    require_once('lib/Class.ErrorHandler.php');
    require_once('lib/Class.MySQL.php');
    require_once('lib/Class.Factory.php');

    if(isset($_POST['type']) && $_POST['type'] != 'default')
    {
        $logger->warning('An Unsupported identity type: '. $_POST['type'] .' was requested by client');
        header("HTTP/1.0 405 Method Not Allowed");
        header("X-Powered-By: Simian Grid Services", true);
        exit;
    }

    if(isset($_POST['RequestMethod']) && $_POST['RequestMethod'] != '')
    {
        $command = trim($_POST['RequestMethod']);
    } else {
        $logger->warning('An Unsupported command, or empty command or parameter was requested by client');
        $logger->debug(print_r($_POST, true));
        header("HTTP/1.0 405 Method Not Allowed");
        header("X-Powered-By: Simian Grid Services", true);
        exit;
    }

    if (stripos($_SERVER['REQUEST_METHOD'], 'POST') !== FALSE)
    {
        try
        {
            $Action = Factory::CreateInstanceOf($command);
        }
        catch(Exception $ex)
        {
            header("HTTP/1.1 400 Bad Request");
            header("X-Powered-By: Simian Grid Services", true);
            $logger->debug("Exception in Factory: " . print_r($ex,true));
            exit;
        }
    }
    else
    {
        $logger->warning('An Unsupported request method: '. $_SERVER['REQUEST_METHOD'] .' was requested by client');
        header("HTTP/1.0 405 Method Not Allowed");
        header("X-Powered-By: Simian Grid Services", true);
        exit;
    }

    // Connect to the database
    try
    {
        $db = new MySQL($config['Database']['host'],
                        $config['Database']['username'],
                        $config['Database']['password'],
                        $config['Database']['database']);
    }
    catch (Exception $ex)
    {
        header("HTTP/1.1 500 Internal Server Error");
        header("X-Powered-By: Simian Grid Services", true);
        $logger->crit(sprintf("Database Exception: %d %s", mysqli_connect_errno(), mysqli_connect_error()));
        $logger->debug(sprintf("Database Exception: %s", print_r($ex,true)));
        exit();
    }

    // Execute!
    if($Action != NULL)
    {
        try 
        {
            $Action->Execute($db, $_POST, $logger);
        }
        catch (Exception $ex)
        {
            header("HTTP/1.1 500 Internal Server Error");
            header("X-Powered-By: Simian Grid Services", true);
            $logger->debug(sprintf("Call Execute Exception: %s", print_r($ex,true)));
            exit();
        }
    }
    else
    {
        header("HTTP/1.1 405 Method Not Allowed");
        header("X-Powered-By: Simian Grid Services", true);
        exit;
    }
