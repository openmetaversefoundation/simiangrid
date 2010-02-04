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
    require_once('PHPUnit/Framework.php');
    require_once('../lib/Class.UUID.php');

class AssetServiceTests extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $servicesconf = parse_ini_file('../services.ini', true);
        $this->server_url = $servicesconf["AssetService"]["server_url"];
        $this->NewAssetID = NULL;
        $this->AssetSHA = NULL;
    }

    protected function tearDown()
    {
    }

    public function testStoreAsset()
    {
        $headers = array(
            'X-Asset-Creator-Id' =>(string)UUID::Random(),
            'X-Asset-Id' =>UUID::Zero
        );

        $r = new HttpRequest($this->server_url, HttpRequest::METH_POST);
        $r->addHeaders($headers);
        $r->AddPostFile(UUID::Random(), "eyewhite.tga", "image/tga");
        $r->send();
        $this->AssetSHA = sha1(file_get_contents('eyewhite.tga'));

        $this->assertEquals(201, $r->getResponseCode());
        if(file_exists('test.assetid')) { unlink('test.assetid'); }
        file_put_contents('test.assetid', (string)UUID::Parse($r->getResponseHeader("X-Asset-Id")));
    }

    public function testGetAsset()
    {
        $assetID = file_get_contents('test.assetid');
        $sha = sha1(file_get_contents('eyewhite.tga'));
        $r = new HttpRequest($this->server_url . $assetID, HttpRequest::METH_GET);
        $r->send();
        $this->assertEquals(200, $r->getResponseCode());
        $assetData = $r->getResponseBody();
        $this->assertEquals(sha1($assetData), $sha);
    }

    public function testDeleteAsset()
    {
        $assetID = file_get_contents('test.assetid');
        $r = new HttpRequest($this->server_url . $assetID, HttpRequest::METH_DELETE);
        $r->send();
        $this->assertEquals(200, $r->getResponseCode());
    }
}

?>
