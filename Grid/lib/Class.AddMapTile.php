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

define("LOCK_FILE", "tiles.lock");

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
        $filepath = $this->GetFilename(1, $x, $y, $tile->type);
        
        // Acquire a lock on the lock file
        $lockfile = $this->dirpath . LOCK_FILE;
        if (!$lock = @fopen($lockfile, 'ab'))
        {
            log_message('error', "Failed to create or open lock file " . $lockfile);
            
            header("Content-Type: application/json", true);
            echo '{ "Message": "Unable to store map tile" }';
            exit();
        }
        flock($lock, LOCK_EX);
        
        // Save the full resolution tile
        if (!$fp = @fopen($filepath, 'w'))
        {
            log_message('error', "Failed to open map tile " . $filepath . " for writing");
            
            header("Content-Type: application/json", true);
            echo '{ "Message": "Unable to store map tile" }';
            exit();
        }
        fwrite($fp, $tile->Data);
        fclose($fp);
        
        // Also save in JPG format
        if ( $tile->type == 'image/jpeg' ) {
            $this->Jpg2Png($filepath, $this->GetFilename(1, $x, $y, 'png'), JPEG_QUALITY);
        } else {
            $this->Png2Jpg($filepath, $this->GetFilename(1, $x, $y, 'jpg'), JPEG_QUALITY);
        }
        
        // Stitch seven more aggregate tiles together
        for ($zoomLevel = 2; $zoomLevel <= ZOOM_LEVELS; $zoomLevel++)
        {
            // Calculate the width (in full resolution tiles) and bottom-left
            // corner of the current zoom level
            $width = pow(2, $zoomLevel - 1);
            $x1 = $x - ($x % $width);
            $y1 = $y - ($y % $width);
            
            if (!$this->CreateTile($zoomLevel, $x1, $y1, $tile->type))
            {
                header("Content-Type: application/json", true);
                echo '{ "Message": "Unable to store zoom level ' . $zoomLevel . '" }';
                exit();
            }
        }
        
        // Release the lock on the lock file
        flock($lock, LOCK_UN);
        fclose($lock);
        
        header("Content-Type: application/json", true);
        echo '{ "Success": true }';
        exit();
    }
    
    private function OpenInputTile($filename, $type)
    {
        if (file_exists($filename))
            if ( $type == 'image/png' ) {
                return @imagecreatefrompng($filename);
            } else {
                return @imagecreatefromjpeg($filename);
            }
        else
            return FALSE;
    }
    
    private function OpenOutputTile($filename, $type)
    {
        $output = NULL;
        
        if (file_exists($filename))
            if ( $type == 'image/png' ) {
                $output = @imagecreatefrompng($filename);
            } else {
                $output = @imagecreatefromjpeg($filename);
            }
        if ($output)
        {
            // Return the existing output tile
            return $output;
        }
        else
        {
            // Create a new output tile with a transparent background
            $output = imagecreatetruecolor(IMAGE_WIDTH, IMAGE_WIDTH);
#            $black = imagecolorallocate($output, 0, 0, 0);
#            imagecolortransparent($output, $black);
            return $output;
        }
    }
    
    private function GetFilename($zoom, $x, $y, $extension)
    {
        if ( $extension == 'image/jpeg' ) {
            $extension = 'jpg';
        } else if ( $extension == 'image/png' ) {
            $extension = 'png';
        }
        return $this->dirpath . "map-$zoom-$x-$y-objects.$extension";
    }
    
    private function CreateTile($zoomLevel, $x, $y, $type)
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
        $inputBL = $this->OpenInputTile($this->GetFilename($zoomLevel - 1, $xIn             , $yIn             , $type), $type);
        $inputBR = $this->OpenInputTile($this->GetFilename($zoomLevel - 1, $xIn + $prevWidth, $yIn             , $type), $type);
        $inputTL = $this->OpenInputTile($this->GetFilename($zoomLevel - 1, $xIn             , $yIn + $prevWidth, $type), $type);
        $inputTR = $this->OpenInputTile($this->GetFilename($zoomLevel - 1, $xIn + $prevWidth, $yIn + $prevWidth, $type), $type);
        
        // Open the output tile (current zoom level)
        $outputFile = $this->GetFilename($zoomLevel, $xOut, $yOut, $type);
        $output = $this->OpenOutputTile($outputFile, $type);
        
        if (!$output)
            return FALSE;
        $watercolor = imagecolorallocate($output, 29, 71, 95); #1D475F used by frontend map.js
        imagefill($output, 0, 0 , $watercolor);
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
        
        // Also save in JPG format
        if ( $type == 'image/jpeg' ) {
            imagejpeg($output, $outputFile);
            $this->Jpg2Png($outputFile, $this->GetFilename($zoomLevel, $xOut, $yOut, 'png'), JPEG_QUALITY);
        } else {
            imagepng($output, $outputFile);
            $this->Png2Jpg($outputFile, $this->GetFilename($zoomLevel, $xOut, $yOut, 'jpg'), JPEG_QUALITY);
        }
        
        imagedestroy($output);
        
        return TRUE;
    }
    
    private function Png2Jpg($originalFile, $outputFile, $quality)
    {
        $image = imagecreatefrompng($originalFile);
        imagejpeg($image, $outputFile, $quality);
        imagedestroy($image);
    }
    
    private function Jpg2Png($originalFile, $outputFile, $quality)
    {
        $image = imagecreatefromjpeg($originalFile);
        imagepng($image, $outputFile);
        imagedestroy($image);
    }
}
