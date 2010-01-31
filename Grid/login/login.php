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
    $path = '..:../lib';
    set_include_path(get_include_path() . PATH_SEPARATOR . $path);

    class_exists('StopWatch') || require_once('lib/Class.StopWatch.php');
    $Watches = array();
    $Watches["TotalExecution"] = new StopWatch(true);

    class_exists('Logger') || require_once('lib/Class.Logger.php');
    class_exists('User') || require_once('lib/Class.User.php');
    class_exists('Presence') || require_once('lib/Class.Presence.php');
    class_exists('BitFlags') || require_once('lib/Class.BitFlags.php');
    class_exists('Scene') || require_once('lib/Class.Scene.php');

    $L = new Logger('../services.ini', "LOGINCLIENT");
    $logger = $L->getInstance();

    $config = parse_ini_file('../services.ini', true);

    $xmlrpc_server = xmlrpc_server_create();
    xmlrpc_server_register_method($xmlrpc_server, "login_to_simulator", "process_login");

    if(!isset($HTTP_RAW_POST_DATA))
    {
        $fp = fopen("login.xml", "r");
        $request_xml = fread($fp, filesize("login.xml"));
        fclose($fp);
    }
    else
    {
        $request_xml = $HTTP_RAW_POST_DATA;
    }

    $response = xmlrpc_server_call_method($xmlrpc_server, $request_xml, '');
    header('X-Powered-By: Simian Grid Services');
    header ('Content-Type: text/xml');
    echo $response;
    
    xmlrpc_server_destroy($xmlrpc_server);
    exit;

    function process_login($method_name,$params,$user_data)
    {
        global $config;
        global $logger;
        global $UserFlags;
        global $Watches;

        $req = $params[0];
   
        $logger->debug("########## Processing new login request ##########");

        // check if logins are restricted
        if(isset($config["UserService"]["restrict_logins"]) && $config["UserService"]["restrict_logins"]==true)
        {
            $logger->debug("Unrestricted: " . print_r($config["UserService"]["unrestricted_users"], true));
            $logger->debug(print_r(explode(',', $config["UserService"]["unrestricted_users"]), true));
            if(!isset($config["UserService"]["unrestricted_users"]) 
                || !in_array($req["first"] . " " . $req["last"], explode(',', $config["UserService"]["unrestricted_users"])))
            {
                return  array('reason' => 'presence', 
                              'login' => 'false', 
                              'message' => "Logins are currently restricted. Please try again later");
            }
        }

        // sanity check the request, make sure its somewhat valid
        // todo: this could be expanded to look for any required fields
        if(!isset($req["first"], $req["last"], $req["passwd"]) || (isset($req["first"]) && $req["first"]=="")
           || (isset($req["last"]) && $req["last"]=="")
           || (isset($req["passwd"]) && $req["passwd"]==""))
        {
            return  array('reason' => 'key', 
                          'login' => 'false', 
                          'message' => "Error connecting to grid. Login request must contain first, last, and passwd fields and they cannot be blank");
        }

        $logger->debug("########## AUTHENTICATE USER ##########");
        $Watches["Authenticate"] = new StopWatch(true);
        $auth_arr = array('Identifier'=> $req["first"] . " " . $req["last"],
                          'Credential'=> $req["passwd"], 
                          'Type'=>"default",
                          'RequestMethod' => 'AuthorizeIdentity');

        $auth = new HttpRequest($config['UserService']['server_url'], HttpRequest::METH_POST);
        $auth->addPostFields($auth_arr);
        $auth->send();
        $logger->debug($auth->getRawResponseMessage());

        /**
         * Check if Non-Existant accounts should be automatically created
         */
        if(isset($config["LindenView"]["autocreate_accounts"]) 
            && $config["LindenView"]["autocreate_accounts"]
            && $auth->getResponseCode() == 404)
        {
            // create account based on credentials passed
             if(try_make_service_request(array('RequestMethod'=>'AddUser',
                              'Name'=>$req["first"] . " " . $req["last"],
                              'Password'=>$req["passwd"],
                              'HomeLocation'=>'099e4032-66a6-48d2-b7e1-578f1b0b120c',
                              'HomePosition'=>'<256128,256128,25>',
                              'HomeLookAt'=>'<1,0,0>',
                              'ExtraData'=>'',
                              'Flags'=>1), $str))
            {
                $User = User::fromOSD($str);
                if(!try_make_service_request(array('RequestMethod'=>'AddIdentity',
                            'Identifier'=> $req["first"] . " " . $req["last"],
                            'Credential'=> $req["passwd"],
                            'Type'=>"default",
                            'UserID'=>(string)$User->ID), $null))
                {
                    $logger->crit("Unable to Auto Create account for " . $req["first"] . " " . $req["last"]);
                    return  array('reason' => 'key', 
                                  'login' => 'false', 
                                  'message' => "Sorry! We couldn't log you in. Auto Account Creation Failed.");
                }
            }
            $logger->info(sprintf("Created new Account and Identity for %s %s [%s] ", $req["first"], $req["last"], (string)$User->ID));
        }
        else if($auth->getResponseCode() != 200)
        {
            return  array('reason' => 'key', 
                          'login' => 'false', 
                          'message' => "Sorry! We couldn't log you in.
                Please check to make sure you entered the right
                    * Account name
                    * Password
                Also, please make sure your Caps Lock key is off.");
        }

        /**
         * If Auto Account Creation is enabled $User should be valid, no point in
         * Re-requesting the user account information
         */
        if(Empty($User))
        {
            $authResponse = json_decode($auth->getResponseBody(),true);
        
            if(!UUID::TryParse($authResponse["UserID"], $UserID))
            {
                return  array('reason' => 'key', 
                              'login' => 'false', 
                              'message' => "Identity Authenticated, but Account not found.");
            }

            if(try_make_service_request(array('RequestMethod'=>'GetUser', 'ID'=>$UserID->UUID), $strUser))
            {

//                $osdObj = json_decode($strUser, true);
//                if(json_last_error() != JSON_ERROR_NONE)
//                {
//                    $logger->debug(json_last_error());
//                }
                $User = User::fromOSD($strUser);

//                $logger->debug(print_r($User,true));
//                exit;
            }
            else
            {
                return  array('reason' => 'key', 
                              'login' => 'false', 
                              'message' => "Account not found.");
            }
        }

        // disallow suspended accounts
        if($User->Flags->CheckFlag($UserFlags['Suspended']))
        {
            return  array('reason' => 'key', 
                          'login' => 'false', 
                          'message' => "This account is suspended.");
        }

        // disallow deleted accounts
        if($User->Flags->CheckFlag($UserFlags['Deleted']))
        {
            return  array('reason' => 'key', 
                          'login' => 'false', 
                          'message' => "This account is deleted.");
        }

        $Watches["Authenticate"]->Stop();

        $logger->debug(print_r($User,true));

        $logger->debug("########## CHECK PRESENCE ##########");
        $Watches["Presence"] = new StopWatch(true);
        if(try_make_service_request(array('RequestMethod'=>'GetPresence', 
                                          'ID'=>(string)$User->ID), $strPres))
        {
            /**
             * User is in presence database, so we'll do the following:
             * * Request the Scene data for the users LastLocation
             * * Send a SceneMessage to the Scene informing the scene it should disconnect the user
             *   * If Success: Continue
             *   * If Failed: Force removal of the presence in the Presence table
             *     * If Success: Continue
             *     * If Failed: Inform Viewer and discontinue login process
             */
            $Presence = Presence::fromOSD($strPres);
            $logger->debug(print_r($Presence,true));

            // get the scene info
            $strScene;
            if(try_make_service_request(array('RequestMethod'=>'GetSceneByID', 'ID'=>$Presence->LastLocation->UUID), $strScene))
            {
                $Scene= Scene::fromOSD($strScene);
                $puntResponse = array();
                $puntRequest = array('agent_id'=>$Presence->UserID->UUID, 'reason'=>"You have logged in from another location");
                if(!try_send_scene_message($Scene, 'puntuser', $puntRequest, $puntResponse))
                {
                    // force presence removal
                    if(!try_make_service_request(array('RequestMethod'=>'RemovePresence', 'ID'=>$Presence->UserID->UUID), $str))
                    {
                        return  array('reason' => 'presence', 
                                      'login' => 'false', 
                                      'message' => "You are already logged in at another location. Sending disconnect to other location, please try logging in again.");
                    }
                }

            }

        }

        $Watches["Presence"]->Stop();


        $logger->debug("########## START LOCATION ##########");
        $Watches["StartLocation"] = new StopWatch(true);
        $Scene = NULL;
        $StartLocation = NULL;
        $StartPosition = NULL;
        $StartLookAt = NULL;

        if(strtolower($req["start"])=="last")
        {
            $logger->debug(sprintf("Finding Start Location (last) with GetSceneByID for '%s' (%s)", $User->LastLocation, $User->Name));
            if(isset($User->LastLocation) && $User->LastLocation != '')
            {   
                $strScene = NULL;
                if(try_make_service_request(array('RequestMethod'=>'GetSceneByID', 'ID'=>$User->LastLocation->UUID), $strScene))
                {
                    if(isset($strScene, $User->LastPosition, $User->LastLookAt) && $User->LastPosition != '' && $User->LastLookAt != '')
                    {
                        $Scene= Scene::fromOSD($strScene);

                        $StartLocation =  (isset($User->LastLocation) && $User->LastLocation !='') ? $User->LastLocation : NULL;
                        $StartPosition = (isset($User->LastPosition) && $User->LastPosition !='') ? $User->LastPosition : NULL;
                        $StartLookAt = (isset($User->LastLookAt) && $User->LastLookAt !='') ? $User->LastLookAt : NULL;
                    }
                }
            }
        } 
        else if(strtolower($req["start"])=="home")
        {
            $logger->debug(sprintf("Finding Start Location (home) with FindByID for '%s' (%s)", $User->HomeLocation, $User->Name));
            if(isset($User->HomeLocation) && $User->HomeLocation != "")
            {
                $strScene = NULL;
                if(try_make_service_request(array('RequestMethod'=>'GetSceneByID', 'ID'=>$User->HomeLocation->UUID), $strScene))
                {
                    if(isset($strScene, $User->HomePosition, $User->HomeLookAt) && $User->HomePosition != '' && $User->HomeLookAt != '')
                    {
                        $Scene= Scene::fromOSD($strScene);

                        $StartLocation = (isset($User->HomeLocation) && $User->HomeLocation != '') ? $User->HomeLocation : NULL;
                        $StartPosition = (isset($User->HomePosition) && $User->HomePosition != '') ? $User->HomePosition : NULL;
                        $StartLookAt = (isset($User->HomeLookAt) && $User->HomeLookAt != '') ? $User->HomeLookAt : NULL;
                    }
                }
            }
        }
        else if(preg_match('/^([a-zA-Z\s]+)\/?(\d+)?\/?(\d+)?\/?(\d+)?$/', $req["start"], $matches))
        {
            $logger->debug(sprintf("Finding Start Location (%s) with FindByName for '%s' (%s)", $req["start"], $matches[1], $User->Name));
            $logger->debug("Lookup Specified position FindByName: " . $User->Name);
            $strScene = NULL;
            if(try_make_service_request(array('RequestMethod'=>'GetSceneByName', 'Name'=>$matches[1]), $strScene))
            {
                $Scene = Scene::fromOSD($strScene);

                $StartLocation =  $Scene->ID;
                $StartPosition = Vector3::Parse(sprintf("<%s, %s, %s>", $Scene->MinPosition->X + $Scene->MaxPosition->X / 2, 
                                        $Scene->MinPosition->Y + $Scene->MaxPosition->Y / 2, 25));
                $StartLookAt = Vector3d::Parse("<0, 0, 0>");
            }
        }

        // lookup of last resort!
        if(!isset($Scene))
        { 
            $logger->debug(sprintf("Finding Start Location (last resort) with GetSceneNearVector for '%s' (%s)", Vector3::Zero(), $User->Name));
            $strScene = NULL;
            if(try_make_service_request(array('RequestMethod'=>'GetSceneNearVector', 'Vector'=> (string)Vector3::Zero()), $strScene))
            {
                $Scene = Scene::fromOSD($strScene);
                $StartLocation =  $Scene->ID;
                $StartPosition = Vector3::Parse(sprintf("<%s, %s, %s>", $Scene->MinPosition->X + $Scene->MaxPosition->X / 2, 
                                        $Scene->MinPosition->Y + $Scene->MaxPosition->Y / 2, 25));
                $StartLookAt = Vector3d::Parse("<0, 0, 0>");
            }
        }

        // If last resort lookup didn't return anything, something has gone horribly wrong and
        // we need to bail out
        if(!isset($Scene))
        {
            return  array('reason' => 'key', 
                          'login' => 'false', 
                          'message' => "Error connecting to grid. No Suitable region located to connect to.");
        }
        $Watches["StartLocation"]->Stop();

        /* Start building the response array */
        $response = array();
        $response["seconds_since_epoch"] = time();
        $response["message"] = $config["UserService"]["motd"];
        $response["login"] = "true";
        $response["agent_id"] = $User->ID;
        list($response["first_name"], $response["last_name"]) = explode(" ", $User->Name);
        $response["udp_blacklist"] = $config["UserService"]["udp_blacklist"];

/*
        if(isset($StartLookAt, $StartPosition))
        {
            $response["look_at"] = sprintf("[r%s, r%s, r%s]", $StartLookAt->X, $StartLookAt->Y, $StartLookAt->Z);
            $response["home"] = sprintf("{'region_handle':[r%s, r%s], 'position':[r%s, r%s, r%s], 'look_at':[r%s, r%s, r%s]}",
                $Scene->MinPosition->X, $Scene->MinPosition->Y,
                $StartPosition->X, $StartPosition->Y, $StartPosition->Z,
                $StartLookAt->X, $StartLookAt->Y, $StartLookAt->Z);
        } else {
            $StartX = $Scene->MinPosition->X + $Scene->MaxPosition->X / 2;
            $StartY = $Scene->MinPosition->Y + $Scene->MaxPosition->Y / 2;
            $response["look_at"] = "[r0, r0, r0]";
            $response["home"] = sprintf("{'region_handle':[r%s, r%s], 'position':[r%s, r%s, r0], 'look_at':[r0, r0, r0]}",
                        $Scene->MinPosition->X, $Scene->MinPosition->Y,
                        $StartX, $StartY);

            $StartPosition = Vector3::Parse(sprintf("<%s, %s, 25>", $StartX, $StartY));
            $StartLookAt = Vector3d::Parse("<0, 0, 0>");
        }*/
   
        // TODO: this should be handled by the scene service 
        /*
        if(try_make_service_request(array('RequestMethod'=>'AddPresence',
                                          'ID'=>(string)$User->ID,
                                          'LastLocation'=>(string)$StartLocation,
                                          'LastPosition'=>(string)$StartPosition,
                                          'LastLookAt'=>(string)$StartLookAt,
                                          'Flags'=>0), $addPres)) 
        { 
            $Presence = Presence::fromOSD($addPres);
        } else {
            $logger->debug("Unable to add presence");
            return  array('reason' => 'presence', 
                          'login' => 'false', 
                          'message' => "An error occurred while trying to add your presence. Login cannot continue :(");
        }
        */
        // FIXME: need to get this directly from the region we are trying to connect to

        srand(make_seed());
        $simReq = array();
        $simReq["id"] = (string)$User->ID;
        $simReq["session_id"] = (string)UUID::Random();
        $simReq["secure_session_id"] = (string)UUID::Random();
        $simReq["name"] = $User->Name;
        $simReq["extra_data"] = array("circuit_code"=>rand());
        $simReq["verified"] = TRUE;
        $simReq["last_login"] = (int)$User->LastLogin;
        $simReq["home_location"] = $User->HomeLocation->toOSD();
        $simReq["home_position"] = $User->HomePosition->toOSD();
        $simReq["home_look_at"] = $User->HomeLookAt->toOSD();
        $simReq["last_location"] = $User->LastLocation->toOSD();
        $simReq["last_position"] = $User->LastPosition->toOSD();
        $simReq["last_look_at"] = $User->LastLookAt->toOSD();

        $simResponse = array();
        if(!try_send_scene_message($Scene, 'lindenlogin', $simReq, $simResponse))
        {
            return  array('reason' => 'presence',
                          'login' => 'false',
                          'message' => "Unable to find any valid regions to connect to. Login cannot continue");
        }

        $response["circuit_code"] = $simReq["extra_data"]["circuit_code"];
        $response["sim_ip"] = /*'24.113.169.75';*/ $simResponse["lludp_address"];
        $response["sim_port"] = $simResponse["lludp_port"];
        $response["seed_capability"] = $simResponse["seed_capability"];
//        $startPos = Vector3d::Parse($simResponse["start_position"]);
        $response["region_x"] = (string)$Scene->MinPosition->X;
        $response["region_y"] = (string)$Scene->MinPosition->Y;

        $startPos = Vector3d::Parse($simResponse["start_position"]);
        $startLat = Vector3::Parse($simResponse["look_at"]);
        $response["look_at"] = sprintf("[r%s, r%s, r%s]", $startLat->X, $startLat->Y, $startLat->Z);
        $response["home"] = sprintf("{'region_handle':[r%s, r%s], 'position':[r%s, r%s, r%s], 'look_at':[r%s, r%s, r%s]}",
            $Scene->MinPosition->X, $Scene->MinPosition->Y,
            $startPos->X, $startPos->Y, $startPos->Z,
            $startLat->X, $startLat->Y, $startLat->Z);

        $response["secure_session_id"] = $simReq["secure_session_id"];
        $response["session_id"] = $simReq["session_id"];

        $logger->debug("########## INVENTORY ##########");
        $Watches["Inventory"] = new StopWatch(true);

        $rootStr = NULL;
        if(try_make_service_request(array('RequestMethod'=>'GetRootFolder', 'ID'=>$User->ID), $rootStr))
        {
            $invResp = json_decode($rootStr,true);
            $InventoryRoot = UUID::Parse($invResp["FolderID"]);
            $config["LindenView"]["user_inventory_root_id"] = (string)$InventoryRoot;
            $logger->debug(print_r($InventoryRoot, true));
        } else {
            $logger->crit("No Inventory Root Found");
            if(try_make_service_request(array('RequestMethod'=>'AddInventorySkeleton', 'ID'=>$User->ID, 'Name'=>'My Inventory'), $rootStr))
            {
                $logger->info("New Skeleton created for user");
                $invResp = json_decode($rootStr,true);
                $InventoryRoot = UUID::Parse($invResp["FolderID"]);
                $config["LindenView"]["user_inventory_root_id"] = (string)$InventoryRoot;
                $logger->debug(print_r($InventoryRoot, true));
            } else {
                return  array('reason' => 'presence', 
                              'login' => 'false', 
                              'message' => "An error occurred while trying to access your inventory. Login cannot continue :(");
            }
        }
        $Watches["Inventory"]->Stop();
        // Options
        $logger->debug("########## LOGIN OPTIONS ##########");
        $Watches["OptionsTotal"] = new StopWatch(true);
        $req["options"][] = "initial-outfit";
        for($i = 0; $i < count($req["options"]); $i++)
        {
            $option = str_replace('-', '_', $req["options"][$i]);
            $Watches[$option] = new StopWatch(true);
            if(file_exists("options/Class." . $option . ".php"))
            {
                if(include_once 'options/Class.' . $option . ".php")
                {
                    $instance = new $option($User, $config["LindenView"]);
                    $response[$req["options"][$i]] = $instance->GetResults();
                } 
                else 
                {
                    $logger->debug("Unable to process login option: " . $option);
                }
            }
            else
            {
                $logger->debug("Option " . $option . " not implemented.");
            }
            $Watches[$option]->Stop();
        }
        
        $Watches["OptionsTotal"]->Stop();

        $logger->debug(print_r($Scene,true));
        $response["start_location"] = $req["start"];
        $response["agent_access"] = ($User->Flags->CheckFlag($UserFlags['Adult'])) ? "A" : "M";
        $response["agent_region_access"] = "M";
        $response["agent_access_max"] = "M";
        $response["agent_flags"] = 0;
        $response["ao_transition"] = 0;

        $response["inventory_host"] = "127.0.0.1";

        $logger->notice(sprintf("Login User=%s %s Channel=%s Start=%s Platform=%s Viewer=%s id0=%s Mac=%s",
                    $req["first"], $req["last"], $req["channel"], $req["start"], $req["platform"], $req["version"], $req["id0"], $req["mac"]));

        $Watches["TotalExecution"]->Stop();

        $timing = "Timing: ";
        foreach($Watches as $key=>$value)
        {
            $timing .= sprintf("%s=%0.2f ms ", $key, $value->Elapsed());
        }
        $logger->info($timing);

        return $response;    
    }

    function make_seed()
    {
        list($usec, $sec) = explode(' ', microtime());
        return (float) $sec + ((float) $usec * 100000);
    }

    function try_make_service_request($fields, &$result)
    {
        global $logger;
        global $config;
        $r = new HttpRequest($config['UserService']['server_url'], HttpRequest::METH_POST,
                array("timeout"=>5,"useragent"=>"Simian Login Client"));
        $r->addPostFields($fields);
        $r->send();
        $logger->debug(" Service Request: \n" . $r->getRawRequestMessage());
        $logger->debug("Service Response: \n" .$r->getRawResponseMessage());
        if($r->getResponseCode() == 200 && $r->getResponseHeader("Content-Type") == "application/json")
        {
            $result = $r->getResponseBody();
            return TRUE;
        } else {
            $result = NULL;
            return FALSE;
        }
    }

    function try_send_scene_message($scene, $messageKey, $body, &$result)
    {
        global $logger;
        $request = array();
        $request["body"] = $body;
        $request["message"] = $messageKey;

        $r = new HttpRequest($scene->Address . 'scenes/' . $scene->ID . '/', HttpRequest::METH_POST,
                array("timeout"=>5,"useragent"=>"Simian Login Client"));
        $r->setContentType('application/json');
        $r->setRawPostData(json_encode($request));

        try
        {
            $r->send();
        }
        catch (HttpInvalidParamException $ex)
        {
            $logger->crit("Unable to connect to scene at " . $scene->Address . " Is it running?");
            return FALSE;
        }

        $logger->debug("Scene Message [".$messageKey."] Request:" . $r->getRawRequestMessage());
        $logger->debug("Scene Message [".$messageKey."] Response:" . $r->getRawResponseMessage());
        $result = json_decode(trim($r->getResponseBody()),true);

        if(json_last_error() == JSON_ERROR_NONE)
        {
            return TRUE;
        } else {
             $logger->crit(sprintf("JSON Decode Error: %s. string: '%s'", json_last_error(), $r->getResponseBody()));
        }

        $result = NULL;
        return FALSE;
    }
