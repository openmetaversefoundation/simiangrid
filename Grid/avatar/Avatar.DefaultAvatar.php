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
 * @author     Intel Corporation <http://www.intel.com/>
 * @copyright  Open Metaverse Foundation
 * @license    http://www.debian.org/misc/bsd.license  BSD License (3 Clause)
 * @link       http://openmetaverse.googlecode.com/
 */

/* Implements the default "ruth" avatar */
class DefaultAvatar implements IAvatarInventoryFolder
{
  private $Name;
  private $RootID;

  private $ClothingID;
  private $OutfitID;

  private $HairID;
  private $PantsID;
  private $ShapeID;
  private $ShirtID;
  private $SkinID;
  private $EyesID;

  public function __construct($name,$userid)
  {
    /* folder information */
    $this->Name = $name;
    $this->RootID = $userid;

    /* handle for the outfit folder */
    $this->ClothingID = UUID::Random();
    $this->OutfitID = UUID::Random();

    /* handles for all the appearance items */
    $this->HairID = UUID::Random();
    $this->PantsID = UUID::Random();
    $this->ShapeID = UUID::Random();
    $this->ShirtID = UUID::Random();
    $this->SkinID = UUID::Random();
    $this->EyesID = UUID::Random();
  }

  public function Folders()
  {
    $skeleton =
      array(
	    array('ID' => $this->RootID, 'ParentID' => UUID::Parse(UUID::Zero), 'Name' => $this->Name,
		  'PreferredContentType' => 'application/vnd.ll.folder'),
	    array('ID' => UUID::Random(), 'ParentID' => $this->RootID, 'Name' => 'Animations',
		  'PreferredContentType' => 'application/vnd.ll.animation'),
	    array('ID' => UUID::Random(), 'ParentID' => $this->RootID, 'Name' => 'Body Parts',
		  'PreferredContentType' => 'application/vnd.ll.bodypart'),
	    array('ID' => UUID::Random(), 'ParentID' => $this->RootID, 'Name' => 'Calling Cards',
		  'PreferredContentType' => 'application/vnd.ll.callingcard'),
	    array('ID' => UUID::Random(), 'ParentID' => $this->RootID, 'Name' => 'Gestures',
		  'PreferredContentType' => 'application/vnd.ll.gesture'),
	    array('ID' => UUID::Random(), 'ParentID' => $this->RootID, 'Name' => 'Landmarks',
		  'PreferredContentType' => 'application/vnd.ll.landmark'),
	    array('ID' => UUID::Random(), 'ParentID' => $this->RootID, 'Name' => 'Lost and Found',
		  'PreferredContentType' => 'application/vnd.ll.lostandfoundfolder'),
	    array('ID' => UUID::Random(), 'ParentID' => $this->RootID, 'Name' => 'Notecards',
		  'PreferredContentType' => 'application/vnd.ll.notecard'),
	    array('ID' => UUID::Random(), 'ParentID' => $this->RootID, 'Name' => 'Objects',
		  'PreferredContentType' => 'application/vnd.ll.primitive'),
	    array('ID' => UUID::Random(), 'ParentID' => $this->RootID, 'Name' => 'Photo Album',
		  'PreferredContentType' => 'application/vnd.ll.snapshotfolder'),
	    array('ID' => UUID::Random(), 'ParentID' => $this->RootID, 'Name' => 'Scripts',
		  'PreferredContentType' => 'application/vnd.ll.lsltext'),
	    array('ID' => UUID::Random(), 'ParentID' => $this->RootID, 'Name' => 'Sounds',
		  'PreferredContentType' => 'application/ogg'),
	    array('ID' => UUID::Random(), 'ParentID' => $this->RootID, 'Name' => 'Textures',
		  'PreferredContentType' => 'image/x-j2c'),
	    array('ID' => UUID::Random(), 'ParentID' => $this->RootID, 'Name' => 'Trash',
		  'PreferredContentType' => 'application/vnd.ll.trashfolder'),
	    array('ID' => $this->ClothingID, 'ParentID' => $this->RootID, 'Name' => 'Clothing',
		  'PreferredContentType' => 'application/vnd.ll.clothing'),
	    array('ID' => $this->OutfitID, 'ParentID' => $this->ClothingID, 'Name' => 'Default Outfit',
		  'PreferredContentType' => 'application/octet-stream')
	    );

    return $skeleton;
  }
        
  public function Items()
  {
    $items =
      array(
	    array('ID' => $this->HairID, 'ParentID' => $this->OutfitID, 'Name' => 'Default Hair',
		  'AssetID' => 'dc675529-7ba5-4976-b91d-dcb9e5e36188'),
	    array('ID' => $this->PantsID, 'ParentID' => $this->OutfitID, 'Name' => 'Default Pants',
		  'AssetID' => '3e8ee2d6-4f21-4a55-832d-77daa505edff'),
	    array('ID' => $this->ShapeID, 'ParentID' => $this->OutfitID, 'Name' => 'Default Shape',
		  'AssetID' => '530a2614-052e-49a2-af0e-534bb3c05af0'),
	    array('ID' => $this->ShirtID, 'ParentID' => $this->OutfitID, 'Name' => 'Default Shirt',
		  'AssetID' => '6a714f37-fe53-4230-b46f-8db384465981'),
	    array('ID' => $this->SkinID, 'ParentID' => $this->OutfitID, 'Name' => 'Default Skin',
		  'AssetID' => '5f787f25-f761-4a35-9764-6418ee4774c4'),
	    array('ID' => $this->EyesID, 'ParentID' => $this->OutfitID, 'Name' => 'Default Eyes',
		  'AssetID' => '78d20332-9b07-44a2-bf74-3b368605f4b5')
	    );
    
    return $items;
  }

  public function Appearance()
  {
    // Update this users appearance in the user service
    $appearance =
      array(
	    'Height' => 1.771488,
	    'ShapeItem' => $this->ShapeID,
	    'ShapeAsset' => '530a2614-052e-49a2-af0e-534bb3c05af0',
	    'EyesItem' => $this->EyesID,
	    'EyesAsset' => '78d20332-9b07-44a2-bf74-3b368605f4b5',
	    //'GlovesItem' => '',
	    //'GlovesAsset' => '',
	    'HairItem' => $this->HairID,
	    'HairAsset' => 'dc675529-7ba5-4976-b91d-dcb9e5e36188',
	    //'JacketItem' => '',
	    //'JacketAsset' => '',
	    'PantsItem' => $this->PantsID,
	    'PantsAsset' => '3e8ee2d6-4f21-4a55-832d-77daa505edff',
	    'ShirtItem' => $this->ShirtID,
	    'ShirtAsset' => '6a714f37-fe53-4230-b46f-8db384465981',
	    //'ShoesItem' => '',
	    //'ShoesAsset' => '',
	    'SkinItem' => $this->SkinID,
	    'SkinAsset' => '5f787f25-f761-4a35-9764-6418ee4774c4'
	    //'SkirtItem' => '',
	    //'SkirtAsset' => '',
	    //'SocksItem' => '',
	    //'SocksAsset' => '',
	    //'UnderpantsItem' => '',
	    //'UnderpantsAsset' => '',
	    //'UndershirtItem' => '',
	    //'UndershirtAsset' => ''
	    );

    return $appearance;
  }

  public function Attachments()
  {
    return array();
  }

  public function Configure()
  {
  }
}
