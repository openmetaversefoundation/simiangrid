<?php

class DefaultAvatar implements IAvatarInventoryFolder
{
    private $gFolders;
    private $gItems;
    private $gAppearance;

    private function FindItemID($folderid,$iname)
    {
        foreach ($this->gItems as $ind => $item)
        {
            if ($item['ParentID'] == $folderid && $item['Name'] == $iname)
                return $item['ID'];
        }

        log_message('error',"unable to locate item $iname in folder $folderid");
        return false;
    }

    private function FindAssetID($folderid,$iname)
    {
        foreach ($this->gItems as $ind => $item)
        {
            if ($item['ParentID'] == $folderid && $item['Name'] == $iname)
                return $item['AssetID'];
        }
    
        log_message('error',"unable to locate asset item $iname in folder $folderid");
        return false;
    }

    private function MakeFolder($id,$pid,$name,$type)
    {
        $flist = array();
        $flist['ID'] = $id;
        $flist['ParentID'] = $pid;
        $flist['Name'] = $name;
        $flist['PreferredContentType'] = $type;
        return $flist;
    }

    private function MakeItem($pid,$name,$asset,$creator,$data)
    {
        $ilist = array();
        $ilist['ID'] = UUID::Random();
        $ilist['ParentID'] = $pid;
        $ilist['Name'] = $name;
        $ilist['AssetID'] = $asset;
        $ilist['CreatorID'] = $creator;
        $ilist['ExtraData'] = $data;
        return $ilist;
    }

    private function MakeWearable($pid,$name,$asset)
    {
        $wlist = array();
        $wlist['item'] = $this->FindItemID($pid,$name);
        $wlist['asset'] = $asset;
        return $wlist;
    }

    public function __construct($name,$userid)
    {
        /* folder information */
        $Name = $name;
        $RootID = $userid;

        $parent_0 = UUID::Random();
        $parent_1 = UUID::Random();
        $parent_2 = UUID::Random();
        $parent_3 = UUID::Random();
        $parent_4 = UUID::Random();
        $parent_5 = UUID::Random();
        $parent_6 = UUID::Random();
        $parent_7 = UUID::Random();
        $parent_8 = UUID::Random();
        $parent_9 = UUID::Random();
        $parent_10 = UUID::Random();
        $parent_11 = UUID::Random();
        $parent_12 = UUID::Random();
        $parent_13 = UUID::Random();
        $parent_14 = UUID::Random();

        $this->gFolders =
            array(
                  $this->MakeFolder($RootID, UUID::Parse(UUID::Zero),$Name,'application/vnd.ll.folder'),
                  $this->MakeFolder($parent_0,  $RootID, 'Objects', 'application/vnd.ll.primitive'),
                  $this->MakeFolder($parent_1,  $RootID, 'Gestures', 'application/vnd.ll.gesture'),
                  $this->MakeFolder($parent_2,  $RootID, 'Sounds', 'application/ogg'),
                  $this->MakeFolder($parent_3,  $RootID, 'Landmarks', 'application/vnd.ll.landmark'),
                  $this->MakeFolder($parent_4,  $RootID, 'Clothing', 'application/vnd.ll.clothing'),
                  $this->MakeFolder($parent_5,  $RootID, 'Calling Cards', 'application/vnd.ll.callingcard'),
                  $this->MakeFolder($parent_6,  $RootID, 'Photo Album', 'application/vnd.ll.snapshotfolder'),
                  $this->MakeFolder($parent_7,  $RootID, 'Scripts', 'application/vnd.ll.lsltext'),
                  $this->MakeFolder($parent_8,  $RootID, 'Notecards', 'application/vnd.ll.notecard'),
                  $this->MakeFolder($parent_9,  $RootID, 'Textures', 'image/x-j2c'),
                  $this->MakeFolder($parent_10,  $RootID, 'Trash', 'application/vnd.ll.trashfolder'),
                  $this->MakeFolder($parent_11,  $RootID, 'Lost and Found', 'application/vnd.ll.lostandfoundfolder'),
                  $this->MakeFolder($parent_12,  $RootID, 'Body Parts', 'application/vnd.ll.bodypart'),
                  $this->MakeFolder($parent_13,  $RootID, 'Animations', 'application/vnd.ll.animation'),
                  $this->MakeFolder($parent_14,  $parent_4, 'Default Outfit', 'application/octet-stream')
                  );

        $this->gItems =
            array(
                  $this->MakeItem($parent_14,'Default Eyes','78d20332-9b07-44a2-bf74-3b368605f4b5','fd6e9c85-2fc3-478d-ad1b-e9bd382b78a9','{}'),
                  $this->MakeItem($parent_14,'Default Shape','530a2614-052e-49a2-af0e-534bb3c05af0','fd6e9c85-2fc3-478d-ad1b-e9bd382b78a9','{}'),
                  $this->MakeItem($parent_14,'Default Shirt','6a714f37-fe53-4230-b46f-8db384465981','fd6e9c85-2fc3-478d-ad1b-e9bd382b78a9','{}'),
                  $this->MakeItem($parent_14,'Default Skin','5f787f25-f761-4a35-9764-6418ee4774c4','fd6e9c85-2fc3-478d-ad1b-e9bd382b78a9','{}'),
                  $this->MakeItem($parent_14,'Default Hair','dc675529-7ba5-4976-b91d-dcb9e5e36188','fd6e9c85-2fc3-478d-ad1b-e9bd382b78a9','{}'),
                  $this->MakeItem($parent_14,'Default Pants','3e8ee2d6-4f21-4a55-832d-77daa505edff','fd6e9c85-2fc3-478d-ad1b-e9bd382b78a9','{}')
                  );
    
        $this->gAppearance = 
            array(
                  'serial' => 1,
                  'height' => 1.8,
                  'hipoffset' => 0,
                  'wearables' =>
                  array(
                        array($this->MakeWearable($parent_14,'Default Shape','530a2614-052e-49a2-af0e-534bb3c05af0')),
                        array($this->MakeWearable($parent_14,'Default Skin','5f787f25-f761-4a35-9764-6418ee4774c4')),
                        array($this->MakeWearable($parent_14,'Default Hair','dc675529-7ba5-4976-b91d-dcb9e5e36188')),
                        array($this->MakeWearable($parent_14,'Default Eyes','78d20332-9b07-44a2-bf74-3b368605f4b5')),
                        array($this->MakeWearable($parent_14,'Default Shirt','6a714f37-fe53-4230-b46f-8db384465981')),
                        array($this->MakeWearable($parent_14,'Default Pants','3e8ee2d6-4f21-4a55-832d-77daa505edff')),
                        array(),
                        array(),
                        array(),
                        array(),
                        array(),
                        array(),
                        array(),
                        array(),
                        array()
                        )
                  );
    }


    /* ----------------------------------------------------------------- */
    public function Folders()
    {
        return $this->gFolders;
    }

    /* ----------------------------------------------------------------- */
    public function Items()
    {
        return $this->gItems;
    }

    /* ----------------------------------------------------------------- */
    public function Appearance()
    {
        return $this->gAppearance;
    }

    /* ----------------------------------------------------------------- */
    public function Configure()
    {
    }
}
