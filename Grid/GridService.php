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

    /*
     * This section is used for debugging purposes, for production use it should be disabled
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
    /* End of developer debugging */

    require_once('lib/Class.Logger.php');
    $L = new Logger('services.ini', "GRIDSERVICE");
    $logger = $L->getInstance();

    $config = parse_ini_file('services.ini', true);

    require_once('lib/Class.ExceptionHandler.php');
    require_once('lib/Class.ErrorHandler.php');
    require_once('lib/Class.MySQL.php');
    require_once('lib/Class.Factory.php');

    if($_SERVER['CONTENT_TYPE']=='application/x-www-form-urlencoded')
    {
        $REQUEST_DATA = $_POST;
    }
    else if($_SERVER['CONTENT_TYPE']=='application/json')
    {
        $json = json_decode($HTTP_RAW_POST_DATA,true);
        if(json_last_error()==JSON_ERROR_NONE)
        {
            $REQUEST_DATA = $json;
        } else {
            $logger->debug("error decoding json data: " . json_last_error());
            header("X-Powered-By: Simian Grid Services", true);
            header("Content-Type: application/json", true);
            echo '{"Success":false, "Message":"Error while decoding json data"}';
            exit;
        }
    }
    else
    {
        $logger->debug("Invalid Content-Type sent: " . $_SERVER['CONTENT_TYPE']);
        header("X-Powered-By: Simian Grid Services", true);
        header("Content-Type: application/json", true);
        echo '{"Success":false, "Message":"Content-type not set or invalid"}';
        exit;
    }

    $command = trim($REQUEST_DATA['RequestMethod']);

    if(empty($command))
    {
        $logger->warning('An Unsupported command, or empty command or parameter was requested by client');
        $logger->debug(print_r($REQUEST_DATA, true));
        header("X-Powered-By: Simian Grid Services", true);
        header("Content-Type: application/json", true);
        echo '{"Success":false, "Message":"Unsupported or missing RequestMethod"}';
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
            header("X-Powered-By: Simian Grid Services", true);
            header("Content-Type: application/json", true);
            echo '{"Success":false, "Message":"Exception while trying to create instance of '.$command.'"}';
            $logger->debug("Exception in Factory: " . print_r($ex,true));
            exit;
        }
    }
    else
    {
        $logger->warning('An Unsupported request method: '. $_SERVER['REQUEST_METHOD'] .' was requested by client');
        header("X-Powered-By: Simian Grid Services", true);
        echo '{"Success":false, "Message":"Unsupported http request method '.$_SERVER['REQUEST_METHOD'].'"}';
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
        $logger->crit(sprintf("Database Exception: %d %s", mysqli_connect_errno(), mysqli_connect_error()));
        $logger->debug(sprintf("Database Exception: %s", print_r($ex,true)));
        header("X-Powered-By: Simian Grid Services", true);
        echo '{"Success":false, "Message":"An error ocurred while attempting to connect or communicate with the database"}';
        exit();
    }

    // Execute!
    if($Action != NULL)
    {
        try 
        {
            $Action->Execute($db, $REQUEST_DATA, $logger);
        }
        catch (Exception $ex)
        {
            header("X-Powered-By: Simian Grid Services", true);
            $logger->debug(sprintf("Call Execute Exception: %s", print_r($ex,true)));
            echo '{"Success":false, "Message":"An exception ocurred trying to Execute class"}';
            exit();
        }
    }
    else
    {
        header("X-Powered-By: Simian Grid Services", true);
        echo '{"Success":false, "Message":"Unable to load requested action"}';
        exit;
    }
?>
