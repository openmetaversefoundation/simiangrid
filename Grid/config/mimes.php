<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| MIME Types
|--------------------------------------------------------------------------
|
| This file contains an array of mime types to SL inventory type. It is 
| used by the login service to build an SL-compatible inventory skeleton 
|
*/
$mimes = array(
	'image/x-j2c' => 0,
    'image/jp2' => 0,
    'application/ogg' => 1,
    'application/vnd.ll.callingcard' => 2,
    'application/x-metaverse-callingcard' => 2,
    'application/vnd.ll.landmark' => 3,
    'application/x-metaverse-landmark' => 3,
    'application/vnd.ll.clothing' => 5,
    'application/x-metaverse-clothing' => 5,
    'application/vnd.ll.primitive' => 6,
    'application/x-metaverse-primitive' => 6,
    'application/vnd.ll.notecard' => 7,
    'application/x-metaverse-notecard' => 7,
    'application/vnd.ll.folder' => 8,
    'application/vnd.ll.rootfolder' => 9,
    'application/vnd.ll.lsltext' => 10,
    'application/x-metaverse-lsl' => 10,
    'application/vnd.ll.lslbyte' => 11,
    'application/x-metaverse-lso' => 11,
    'image/tga' => 12,
    'application/vnd.ll.bodypart' => 13,
    'application/x-metaverse-bodypart' => 13,
    'application/vnd.ll.trashfolder' => 14,
    'application/vnd.ll.snapshotfolder' => 15,
    'application/vnd.ll.lostandfoundfolder' => 16,
    'audio/x-wav' => 17,
    'image/jpeg' => 19,
    'application/vnd.ll.animation' => 20,
    'application/x-metaverse-animation' => 20,
    'application/vnd.ll.gesture' => 21,
    'application/x-metaverse-gesture' => 21,
    'application/x-metaverse-simstate' => 22,
    'application/octet-stream' => -1);
