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
 * @author     Jonathan Freedman <jef@openmetaverse.org>
 * @copyright  Open Metaverse Foundation
 * @license    http://www.debian.org/misc/bsd.license  BSD License (3 Clause)
 * @link       http://openmetaverse.googlecode.com/
 */

define('BASEPATH', str_replace("\\", "/", realpath(dirname(__FILE__)) . '/'));

require_once(BASEPATH . 'common/Config.php');

function getRequestedTileName()
{
    $raw_path_bits = explode('/', $_SERVER['PATH_INFO']);
    $path_bits = array();
    foreach ( $raw_path_bits as $path_bit ) {
        if ( $path_bit != "" ) {
            array_push($path_bits, $path_bit);
        }
    }
    if ( count($path_bits) != 1 ) {
        header("HTTP/1.1 404 Not Found");
        exit();
    }
    return $path_bits[0];
}

function getMimeType($file)
{
	$raw_file = explode(".", $file);
	if ( $raw_file[1] == "jpg" ) {
		return "image/jpeg";
	} else if ( $raw_file[1] == "png" ) {
		return "image/png";
	} else {
        header("HTTP/1.1 500 What Is This");
        exit();
	}
}

function drawBlankTile()
{
	$image = imagecreate(256,256);
	$watercolor = imagecolorallocate($image, 29, 71, 95); #1D475F used by frontend map.js
	imagefill($image, 0, 0 , $watercolor);
	header("Content-Type: image/png");
	imagepng($image);
	imagedestroy($image);
}

$config =& get_config();

$map_tile = getRequestedTileName();

$map_dir =  (!empty($config['map_path'])) ? $config['map_path'] : BASEPATH . 'map/';
$filename = $map_dir. "/$map_tile";

if ( file_exists($filename) ) {
	$mime_type = getMimeType($map_tile);
	header("Content-Type: $mime_type");
	readfile($filename);
} else {
	drawBlankTile();
}

?>