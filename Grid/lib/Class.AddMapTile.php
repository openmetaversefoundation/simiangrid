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
 * @author     John Hurliman <http://software.intel.com/en-us/blogs/author/john-hurliman/>
 * @copyright  Open Metaverse Foundation
 * @license    http://www.debian.org/misc/bsd.license  BSD License (3 Clause)
 * @link       http://openmetaverse.googlecode.com/
 */

define("IMAGE_WIDTH", 256);
define("HALF_WIDTH", 128);
define("ZOOM_LEVELS", 8);
define("JPEG_QUALITY", 90);

class AddMapTile implements IGridService
{
    private $dirpath;
    
	public function Execute($unused, $tile)
    {
    	$config =& get_config();
    	
    	$x = (int)$tile->X;
    	$y = (int)$tile->Y;
    	
    	// Get the file path of the full resolution tile
    	$this->dirpath = (!empty($config['map_path'])) ? $config['map_path'] : BASEPATH . 'map/';
    	$filepath = $this->GetFilename(1, $x, $y);
    	
    	// Save the full resolution tile
    	if (!$fp = @fopen($filepath, 'w'))
    	{
    		log_message('error', "Failed to map tile file " . $filepath . " for writing");
    		
    		header("Content-Type: application/json", true);
    		echo '{ "Message": "Unable to store map tile" }';
            exit();
    	}
    	flock($fp, LOCK_EX);
    	fwrite($fp, $tile->Data);
    	flock($fp, LOCK_UN);
    	fclose($fp);
    	
    	// Also save in JPG format
    	$this->Png2Jpg($filepath, $this->GetFilename(1, $x, $y, 'jpg'), JPEG_QUALITY);
    	
    	// Stitch seven more aggregate tiles together
    	for ($zoomLevel = 2; $zoomLevel <= ZOOM_LEVELS; $zoomLevel++)
    	{
    	    // Calculate the width (in full resolution tiles) and bottom-left
    	    // corner of the current zoom level
    	    $width = pow(2, $zoomLevel - 1);
    	    $x1 = $x - ($x % $width);
    	    $y1 = $y - ($y % $width);
    	    
    	    if (!$this->CreateTile($zoomLevel, $x1, $y1))
    	    {
    	        header("Content-Type: application/json", true);
        		echo '{ "Message": "Unable to store zoom level ' . $zoomLevel . '" }';
                exit();
    	    }
    	}
    	
    	header("Content-Type: application/json", true);
        echo '{ "Success": true }';
        exit();
    }
    
    private function OpenInputTile($filename)
    {
        if (file_exists($filename))
            return @imagecreatefrompng($filename);
        else
            return FALSE;
    }
    
    private function OpenOutputTile($filename)
    {
        $output = NULL;
        
        if (file_exists($filename))
            $output = @imagecreatefrompng($filename);
        
        if ($output)
        {
            // Return the existing output tile
            return $output;
        }
        else
        {
            // Create a new output tile with a transparent background
            $output = imagecreatetruecolor(IMAGE_WIDTH, IMAGE_WIDTH);
            $black = imagecolorallocate($output, 0, 0, 0);
            imagecolortransparent($output, $black);
            return $output;
        }
    }
    
    private function GetFilename($zoom, $x, $y, $extension='png')
    {
        return $this->dirpath . "map-$zoom-$x-$y-objects.$extension";
    }
    
    private function CreateTile($zoomLevel, $x, $y)
    {
        $prevWidth = pow(2, $zoomLevel - 2);
        $thisWidth = pow(2, $zoomLevel - 1);
        
        // Convert x and y to the bottom left tile for this zoom level
        $xIn = $x - ($x % $prevWidth);
        $yIn = $y - ($y % $prevWidth);
        
        // Convert x and y to the bottom left tile for the next zoom level
        $xOut = $x - ($x % $thisWidth);
        $yOut = $y - ($y % $thisWidth);
        
        // Try to open the four input tiles from the previous zoom level
        $inputBL = $this->OpenInputTile($this->GetFilename($zoomLevel - 1, $xIn             , $yIn             ));
        $inputBR = $this->OpenInputTile($this->GetFilename($zoomLevel - 1, $xIn + $prevWidth, $yIn             ));
        $inputTL = $this->OpenInputTile($this->GetFilename($zoomLevel - 1, $xIn             , $yIn + $prevWidth));
        $inputTR = $this->OpenInputTile($this->GetFilename($zoomLevel - 1, $xIn + $prevWidth, $yIn + $prevWidth));
        
        // Open the output tile (current zoom level)
        $outputFile = $this->GetFilename($zoomLevel, $xOut, $yOut);
        $output = $this->OpenOutputTile($outputFile);
        
        if (!$output)
            return FALSE;
        
        // Scale the input tiles into the output tile
        if ($inputBL)
        {
            imagecopyresampled($output, $inputBL, 0, HALF_WIDTH, 0, 0, HALF_WIDTH, HALF_WIDTH, IMAGE_WIDTH, IMAGE_WIDTH);
            imagedestroy($inputBL);
        }
        if ($inputBR)
        {
            imagecopyresampled($output, $inputBR, HALF_WIDTH, HALF_WIDTH, 0, 0, HALF_WIDTH, HALF_WIDTH, IMAGE_WIDTH, IMAGE_WIDTH);
            imagedestroy($inputBR);
        }
        if ($inputTL)
        {
            imagecopyresampled($output, $inputTL, 0, 0, 0, 0, HALF_WIDTH, HALF_WIDTH, IMAGE_WIDTH, IMAGE_WIDTH);
            imagedestroy($inputTL);
        }
        if ($inputTR)
        {
            imagecopyresampled($output, $inputTR, HALF_WIDTH, 0, 0, 0, HALF_WIDTH, HALF_WIDTH, IMAGE_WIDTH, IMAGE_WIDTH);
            imagedestroy($inputTR);
        }
        
        // Write the modified output
        imagepng($output, $outputFile);
        imagedestroy($output);
        
        // Also save in JPG format
    	$this->Png2Jpg($outputFile, $this->GetFilename($zoomLevel, $xOut, $yOut, 'jpg'), JPEG_QUALITY);
        
        return TRUE;
    }
    
    private function Png2Jpg($originalFile, $outputFile, $quality)
    {
        $image = imagecreatefrompng($originalFile);
        imagejpeg($image, $outputFile, $quality);
        imagedestroy($image);
    }
}
