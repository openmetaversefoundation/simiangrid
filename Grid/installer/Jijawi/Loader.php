<?php
/**
 * Jijawi
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to mail@dasprids.de so I can send you a copy immediately.
 *
 * @category   Jijawi
 * @copyright  Copyright (c) 2009 Ben Scholzen "DASPRiD" (http://www.dasprids.de)
 * @license    http://jijawi.org/license/new-bsd    New BSD License
 * @version    $Id: Loader.php 14 2009-07-14 00:09:18Z dasprid $
 */

/**
 * File laoder
 *
 * @category   Jijawi
 * @package    Jijawi_Loader
 * @copyright  Copyright (c) 2009 Ben Scholzen "DASPRiD" (http://www.dasprids.de)
 * @license    http://jijawi.org/license/new-bsd    New BSD License
 */
class Jijawi_Loader
{
    /**
     * TAR archive containing data
     *
     * @var string
     */
    protected $_data;
    
    /**
     * Path to data directory
     *
     * @var string
     */
    protected $_path;
    
    /**
     * List of already loaded files
     *
     * @var array
     */
    protected $_loaded = array();
    
    /**
     * Instantiate the loader
     *
     * @param string $data
     * @param string $isPath
     */
    public function __construct($data, $isPath = false)
    {
        if ($isPath) {
            $this->_path = $data;
        } else {
            $this->_data = base64_decode($data);
        }
    }

    /**
     * Include a file
     *
     * @param  string $filename
     * @return mixed
     */
    public function load($filename)
    {
        $tempname = $this->prepareLoad($filename);
        $return   = include($tempname);
        $this->cleanupLoad($tempname);
        
        return $return;
    }
    
    /**
     * Prepare a load
     *
     * This method should be used when an include in a locale scope is required.
     * Afert doing the include, you should cleanup the load again with the
     * cleanupLoad() method.
     * 
     * @param  string $filename
     * @return string
     */
    public function prepareLoad($filename)
    {
        $tempname = tempnam(dirname(__FILE__), 'jijawi');

        $file = fopen($tempname, 'w+');
        fwrite($file, $this->get($filename));
        fclose($file);

        return $tempname;
    }
    
    /**
     * Cleanup a load after include
     * 
     * @param string $tempname
     */
    public function cleanupLoad($tempname)
    {
        unlink($tempname);
    }
    
    /**
     * Include a filename only if not already loaded
     *
     * @param  string $filename
     * @return mixed
     */
    public function loadOnce($filename)
    {
        if (!isset($this->_loaded[$filename])) {
            $this->_loaded[$filename] = true;
            
            return $this->load($filename);
        }
        
        return null;
    }
    
    /**
     * Get a file's content
     *
     * @param  string $filename
     * @return string
     */
    public function get($filename)
    {
        if ($this->_path !== null) {
            $data = @file_get_contents($this->_path . '/' . $filename);
            
            if ($data === false) {
                return null;
            } else {
                return $data;
            }
        }
               
        $pos = strpos($this->_data, $filename . "\x00");

        if ($pos === false) {
            return null;
        }

        $head = substr($this->_data, $pos, 512);
        $size = octdec(substr($this->_data, $pos + 124, 12));
        $data = substr($this->_data, $pos + 512, $size);

        return $data;
    }
}