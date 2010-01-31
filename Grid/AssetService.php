<?php
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
 * @author     Jim Radford <http://www.jimradford.com/>
 * @copyright  Open Metaverse Foundation
 * @license    http://www.debian.org/misc/bsd.license  BSD License (3 Clause)
 * @link       http://openmetaverse.googlecode.com/
 */

    require_once('lib/Class.Logger.php');
    $L = new Logger('services.ini', "ASSETSERVICE");
    $logger = $L->getInstance();

    $config = parse_ini_file('services.ini', true);

    require_once('lib/Class.ExceptionHandler.php');
    require_once('lib/Class.ErrorHandler.php');
    require_once('lib/Class.MySQL.php');
    require_once('lib/Class.Asset.php');
    require_once('lib/Class.Factory.php');
    require_once('lib/Class.UUID.php');

    // Create an instance of the Asset class
    $Asset = new Asset();
    
    if (stripos($_SERVER['REQUEST_METHOD'], 'GET') !== FALSE 
        || (stripos($_SERVER['REQUEST_METHOD'], 'HEAD') !== FALSE))
    {
        $Action = Factory::CreateInstanceOf('AssetDownload');
        $Asset->ID = $_GET['id'];
    }
    else if (stripos($_SERVER['REQUEST_METHOD'], 'POST') !== FALSE) 
    {
        if(count($_FILES)==1)
        {
            $Action = Factory::CreateInstanceOf('AssetUpload');

            $headers = apache_request_headers();

            if(!isset($headers["X-Asset-Id"]) 
                  || $headers["X-Asset-Id"] == ''
                  || $headers["X-Asset-Id"] == UUID::Zero)
            {
                $Asset->ID = UUID::Random();
            } else {
                $Asset->ID = $headers["X-Asset-Id"];
            }

            $key = key($_FILES);
            $tmpName = $_FILES[$key]['tmp_name'];
            $fp      = fopen($tmpName, 'r');
            $Asset->Data = fread($fp, filesize($tmpName));
            fclose($fp);

            $Asset->MimeType = $_FILES[$key]['type'];
            $Asset->CreatorID = $headers['X-Asset-Creator-Id'];
        } else {
            $logger->err('Asset Upload Failed, POST requested but no file or filedata included in request');
            exit;
        }
    }

    else if (stripos($_SERVER['REQUEST_METHOD'], 'DELETE') !== FALSE) 
    {
        $Action = Factory::CreateInstanceOf('AssetDelete');
        $Asset->ID = $_GET['id'];
    }
    else
    {
        $logger->warning('An Unsupported request method: '. $_SERVER['REQUEST_METHOD'] .' was requested by client');
        header("HTTP/1.0 405 Method Not Allowed");
        exit();
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
        $Action->Execute($db, $Asset, $logger);
    }
    else
    {
        header("HTTP/1.1 405 Method Not Allowed");
        header("X-Powered-By: Simian Grid Services", true);
        exit;
    }
    $db->close();
