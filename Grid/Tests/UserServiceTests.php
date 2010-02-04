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
    require_once('../lib/Class.UUID.php');
    require_once('../lib/Class.User.php');
    require_once('../lib/Class.Scene.php');
    require_once('../lib/Class.Presence.php');
    require_once('../lib/Class.StopWatch.php');

    function genpass($toencode)
    {
        return '$1$' . md5($toencode);
    }
/*
    echo $r->getRawRequestMessage();
    echo "\n";
    echo $r->getRawResponseMessage();
    echo "\n";
*/

class GridServiceTests extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $servicesconf = parse_ini_file('../services.ini', true);
        $this->server_url = $servicesconf["Services"]["server_url"];
    }

    protected function tearDown()
    {
    }

    public function testAddScene()
    {
        $sceneID = UUID::Random();
        $a = array('RequestMethod'=>'AddScene',
                   'ID'=>(string)$sceneID,
                   'Name'=>'Test Scene',
                   'MinPosition'=>'<128, 128, 25>',
                   'MaxPosition'=>'<512, 512, 50>',
                   'Address'=>'http://localhost/scenes/' . (string)$sceneID,
            );

        $r = new HttpRequest($this->server_url, HttpRequest::METH_POST);
        $r->addPostFields($a);
        $r->send();

        $this->assertEquals(200, $r->getResponseCode());
        $Scene = new Scene();
        $Scene->ID = $sceneID;
        $Scene->Name = "Test Scene";

        file_put_contents('scene1.dat', serialize($Scene));
    }

    public function testFindByID()
    {
        $Scene = unserialize(file_get_contents('scene1.dat'));
        $a = array('RequestMethod'=>'GetSceneByID',
                   'ID'=>(string)$Scene->ID
                    );

        $r = new HttpRequest($this->server_url, HttpRequest::METH_POST);
        $r->addPostFields($a);
        $r->send();

        $this->assertEquals(200, $r->getResponseCode());
        $Scene2 = Scene::fromOSD($r->GetResponseBody());
        file_put_contents('scene2.dat', serialize($Scene2));
    }

    public function testFindByName()
    {
        $a = array('RequestMethod'=>'GetSceneByName',
                   'Name'=>'Test Scene'
                  );

        $r = new HttpRequest($this->server_url, HttpRequest::METH_POST);
        $r->addPostFields($a);
        $r->send();
        $this->assertEquals(200, $r->getResponseCode());

        $Scene3 = Scene::fromOSD($r->GetResponseBody());
        file_put_contents('scene3.dat', serialize($Scene3));
    }

    public function testGetSceneInVector()
    {
        $a = array('RequestMethod'=>'GetSceneInVector',
                   'Vector'=>'<256, 256, 30>'
                  );

        $r = new HttpRequest($this->server_url, HttpRequest::METH_POST);
        $r->addPostFields($a);
        $r->send();
        $this->assertEquals(200, $r->getResponseCode());

        $Scene4 = Scene::fromOSD($r->GetResponseBody());
        file_put_contents('scene4.dat', serialize($Scene4));
    
    }

    public function testGetSceneNearVector()
    {
        $a = array('RequestMethod'=>'GetSceneNearVector',
                   'Vector'=>'<256, 256, 75>'
                  );

        $r = new HttpRequest($this->server_url, HttpRequest::METH_POST);
        $r->addPostFields($a);
        $r->send();
        $this->assertEquals(200, $r->getResponseCode());

        $Scene5 = Scene::fromOSD($r->GetResponseBody());
        file_put_contents('scene5.dat', serialize($Scene5));

    }


    public function testCreateUser()
    {
        $a = array('RequestMethod'=>'AddUser',
                   'Name'=>'Test User',
                   'HomeLocation'=>(string)UUID::Random(),
                   'HomePosition'=>'<128, 128, 25>',
                   'HomeLookAt'=>'<0, 0, -1>',
                   'Flags'=>0,
                   'ExtraData'=>''
            );

        $r = new HttpRequest($this->server_url, HttpRequest::METH_POST);
        $r->addPostFields($a);
        $r->send();
        
        $this->assertEquals(200, $r->getResponseCode());

        $User = User::fromOSD($r->GetResponseBody());
        file_put_contents('user1.dat', serialize($User));
    }

    public function testGetFriendList()
    {
        $User = unserialize(file_get_contents('user1.dat'));
        $r = new HttpRequest($this->server_url, HttpRequest::METH_POST);
        $r->addPostFields(array('RequestMethod'=>'GetFriendList',
                                'ID'=>(string)$User->ID));
        $r->send();

        $this->assertEquals(200, $r->getResponseCode());
    }

    public function testUpdateUserField()
    {
        $User = unserialize(file_get_contents('user1.dat'));
        $wearablesMap = array("2"=>"a57b7fae-fbd5-4402-bfa3-5fee57eb970a",
                              "5"=>"017b8abe-5a29-4d4e-9b17-51a6426ba122",
                              "0"=>"9594f487-de0f-49e8-9135-019c6f3a8b1c",
                              "4"=>"7c7d7152-6f04-48fa-b894-a59a6cc53927",
                              "1"=>"0152df9b-4988-4324-8862-db819d628210",
                              "3"=>"1b14d9ac-f24a-460a-a828-e169fd8c59bc");

         $r = new HttpRequest($this->server_url, HttpRequest::METH_POST);
         $r->addPostFields(array('RequestMethod'=>'UpdateUserField',
                                 'ID'=>(string)$User->ID,
                                 'Field'=>'wearables',
                                 'Value'=>json_encode($wearablesMap)));
        $r->send();

        $this->assertEquals(200, $r->getResponseCode());
    }

    public function testGetLibraryRoot()
    {
        $r = new HttpRequest($this->server_url, HttpRequest::METH_POST);
        $r->addPostFields(array('RequestMethod'=>'GetLibraryRoot'));
        $r->send();

        $this->assertEquals(200, $r->getResponseCode());
    }

    public function testAddPresence()
    {
        $User = unserialize(file_get_contents('user1.dat'));
        $Scene = unserialize(file_get_contents('scene1.dat'));

        $a = array('RequestMethod'=>'AddPresence',
                   'ID'=>(string)$User->ID,
                   'LastLocation'=>(string)$Scene->ID,
                   'LastPosition'=>'<24, 48, 96>',
                   'LastLookAt'=>'<0, 1, -1>',
                   'Flags'=>0
                  );

        $r = new HttpRequest($this->server_url, HttpRequest::METH_POST);
        $r->addPostFields($a);
        $r->send();

        $this->assertEquals(200, $r->getResponseCode());

        $Presence = Presence::fromOSD($r->GetResponseBody());
        file_put_contents('presence1.dat', serialize($Presence));
    }

    public function testUpdatePresence()
    {
        $User = unserialize(file_get_contents('user1.dat'));
        $a = array('RequestMethod'=>'UpdatePresence',
                   'UserID'=>(string)$User->ID,
                   'Location'=>(string)UUID::Random(),
                   'Position'=>'<192, 192, 192>',
                   'LookAt'=>'<-1, -1, -1>'
                  );

        $r = new HttpRequest($this->server_url, HttpRequest::METH_POST);
        $r->addPostFields($a);
        $r->send();

        $this->assertEquals(200, $r->getResponseCode());
    }

    public function testGetPresence()
    {
        $User = unserialize(file_get_contents('user1.dat'));
        $a = array('RequestMethod'=>'GetPresence',
                   'ID'=>(string)$User->ID
                  );

        $r = new HttpRequest($this->server_url, HttpRequest::METH_POST);
        $r->addPostFields($a);
        $r->send();

        $this->assertEquals(200, $r->getResponseCode());
    
        $Presence2 = Presence::fromOSD($r->GetResponseBody());
        file_put_contents('presence2.dat', serialize($Presence2));
    }
    // TODO: Verify Changes Stuck

    public function testRemovePresence()
    {
        $User = unserialize(file_get_contents('user1.dat'));
        $a = array('RequestMethod'=>'RemovePresence',
                   'ID'=>(string)$User->ID
                  );

        $r = new HttpRequest($this->server_url, HttpRequest::METH_POST);
        $r->addPostFields($a);
        $r->send();

        $this->assertEquals(200, $r->getResponseCode());
    }

    public function testGetUser()
    {
        $User = unserialize(file_get_contents('user1.dat'));
        $a = array('RequestMethod'=>'GetUser',
                   'ID'=>(string)$User->ID
            );

        $r = new HttpRequest($this->server_url, HttpRequest::METH_POST);
        $r->addPostFields($a);
        $r->send();

        $this->assertEquals(200, $r->getResponseCode());
    
        $GetUser = User::fromOSD($r->GetResponseBody());
        file_put_contents('user2.dat', serialize($GetUser));
    }

    public function testCreateIdentity()
    {
        $User = unserialize(file_get_contents('user2.dat'));
        $a = array('RequestMethod'=>'AddIdentity',
                   'Identifier'=>'Foo Identifier',
                   'Credential'=>genpass("Foo Password"),
                   'Type'=>'Foo Type',
                   'UserID'=>(string)$User->ID);

        $r = new HttpRequest($this->server_url, HttpRequest::METH_POST);
        $r->addPostFields($a);
        $r->send();

        $this->assertEquals(200, $r->getResponseCode());
    }

    public function testAuthorizeIdentity()
    {
        $a = array('RequestMethod'=>'AuthorizeIdentity',
                   'Identifier'=>'Foo Identifier',
                   'Credential'=>genpass("Foo Password"));

        $r = new HttpRequest($this->server_url, HttpRequest::METH_POST);
        $r->addPostFields($a);
        $r->send();

        $this->assertEquals(200, $r->getResponseCode());
    }

    public function testGetIdentitiesFor()
    {
        $User = unserialize(file_get_contents('user2.dat'));
        $a = array('RequestMethod'=>'GetIdentities',
                   'ID'=>(string)$User->ID
                   );

        $r = new HttpRequest($this->server_url, HttpRequest::METH_POST);
        $r->addPostFields($a);
        $r->send();

        $this->assertEquals(200, $r->getResponseCode());

        $jsonObj = json_decode($r->GetResponseBody());
        $this->assertGreaterThanorEqual(count($jsonObj), 1);
    }

    public function testRemoveIdentity()
    {
        $a = array('RequestMethod'=>'RemoveIdentity',
                   'Identifier'=>'Foo Identifier');

        $r = new HttpRequest($this->server_url, HttpRequest::METH_POST);
        $r->addPostFields($a);
        $r->send();

        $this->assertEquals(200, $r->getResponseCode());
    }

    public function testRemoveUser()
    {
        $User = unserialize(file_get_contents('user2.dat'));
        $a = array('RequestMethod'=>'RemoveUser',
                   'ID'=>(string)$User->ID
            );

        $r = new HttpRequest($this->server_url, HttpRequest::METH_POST);
        $r->addPostFields($a);
        $r->send();

        $this->assertEquals(200, $r->getResponseCode());
        
        $GetUser = User::fromOSD($r->GetResponseBody());
    }

    public function testRemoveScene()
    {
        $Scene = unserialize(file_get_contents('scene1.dat'));
        $a = array('RequestMethod'=>'RemoveScene',
                   'ID'=>(string)$Scene->ID
                  );

        $r = new HttpRequest($this->server_url, HttpRequest::METH_POST);
        $r->addPostFields($a);
        $r->send();

        $this->assertEquals(200, $r->getResponseCode());
    }
    
    // Capabilities Tests
    public function testAddCapability()
    {
        $a = array('RequestMethod'=>'AddCapability',
                   'OwnerID'=>'efb00dbb-d4ab-46dc-aebc-4ba83288c3c0',
                   'Resource'=>'http://example.com/cap/',
                   'Expiration'=>time());

        $r = new HttpRequest($this->server_url, HttpRequest::METH_POST);
        $r->addPostFields($a);
        $r->send();

        $this->assertEquals(200, $r->getResponseCode());
    }

    public function testRemoveCapability()
    {
        $a = array('RequestMethod'=>'RemoveCapability',
                   'ID'=>'c006082b-80eb-4d17-90ff-224412c574ea');

        $r = new HttpRequest($this->server_url, HttpRequest::METH_POST);
        $r->addPostFields($a);
        $r->send();

        $this->assertEquals(200, $r->getResponseCode());
    }

    public function testRemoveUsersCapabilities()
    {
        $a = array('RequestMethod'=>'RemoveUserCapabilities',
                   'OwnerID'=>'efb00dbb-d4ab-46dc-aebc-4ba83288c3c0');

        $r = new HttpRequest($this->server_url, HttpRequest::METH_POST);
        $r->addPostFields($a);
        $r->send();

    echo $r->getRawRequestMessage();
    echo "\n";
    echo $r->getRawResponseMessage();
    echo "\n";
        $this->assertEquals(200, $r->getResponseCode());
    }


}
?>
