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
 * @version    $Id: View.php 18 2009-07-19 16:26:51Z dasprid $
 */

/**
 * View of Jijawi
 *
 * @category   Jijawi
 * @package    Jijawi_Front
 * @copyright  Copyright (c) 2009 Ben Scholzen "DASPRiD" (http://www.dasprids.de)
 * @license    http://jijawi.org/license/new-bsd    New BSD License
 */
class Jijawi_View
{
    /**
     * Front instance
     *
     * @var Jijawi_Front
     */
    protected $_front;
       
    /**
     * Variables added to the view
     *
     * @var array
     */
    protected $_variables = array();
    
    /**
     * Headers to send
     *
     * @var array
     */
    protected $_headers = array();
    
    /**
     * Body to send
     *
     * @var string
     */
    protected $_body;
    
    /**
     * Additional files to load in the header
     *
     * @var array
     */
    protected $_additionalFiles = array(
        'css' => array(),
        'js'  => array()
    );
    
    /**
     * Predefined mime types
     *
     * @var string
     */
    protected $_mimeTypes = array(
        'js'  => 'text/javascript; charset=utf-8',
        'css' => 'text/css; charset=utf-8',
        'gif' => 'image/gif',
        'png' => 'image/png',
        'jpg' => 'image/jpeg'
    );
    
    /**
     * Instantiate the view
     *
     * @param Jijawi_Front $front
     */
    public function __construct(Jijawi_Front $front)
    {
        $this->_front = $front;
    }
    
    /**
     * Render a PHTML file
     * 
     * @param  string $filename
     * @return void
     */
    public function render($filename)
    {
        $this->addHeader('Content-Type: text/html; charset=utf-8');

        $this->content = $this->_renderFile($filename);
        $this->_body   = $this->_renderFile('views/layout.phtml');
        
        $this->_sendResponse();
    }
    
    /**
     * Display a public file
     *
     * @param  string $filename
     * @return void
     */
    public function displayFile($filename)
    {
        $pathinfo = pathinfo($filename);
        
        if (array_key_exists($pathinfo['extension'], $this->_mimeTypes)) {
            $contentType = $this->_mimeTypes[$pathinfo['extension']];
        } else {
            $contentType = 'text/plain; charset=utf-8';
        }
        
        $modified = filemtime($this->_front->getBasename());

        $this->addHeader('Last-Modified: ' . gmdate('D, d M Y H:i:s \G\M\T', $modified));
        $this->addHeader('ETag: ' . md5($modified));
        $this->addHeader('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', (time() + 3600 * 24)));
        $this->addHeader('Cache-Control: max-age=' . (3600 * 24));
        $this->addHeader('Content-Type: ' . $contentType);
        
        if ((isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && $modified < strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE'])) ||
            (isset($_SERVER['HTTP_IF_NONE_MATCH']) && strpos($_SERVER['HTTP_IF_NONE_MATCH'], md5($modified)) !== false))
        {
            $this->addHeader('Not Modified', true, 304);
        } else {           
            $this->_body = $this->_front->getLoader()->get('public/' . $filename);
        }
        
        $this->_sendResponse();
    }
    
    /**
     * Escape a string for the view
     *
     * @param  string $string
     * @return string
     */
    public function escape($string)
    {
        return htmlspecialchars($string);
    }
    
    /**
     * Create the URL to the requested static file
     *
     * @param  string $filename
     * @return string
     */
    public function staticFile($filename)
    {
        return $this->escape($this->_front->getBasename() . '?file=' . $filename);
    }
    
    /**
     * Enable another CSS file
     *
     * @param  string $file
     * @param  string $module
     * @return void
     */
    public function enableCss($file, $module = null)
    {
        $this->_additionalFiles['css'][] = ($module === null ? $file : '~' . $module . '/' . $file);
    }
    
    /**
     * Enable another JavaScript file
     *
     * @param  string $file
     * @param  string $module
     * @return void
     */
    public function enableJavascript($file, $module = null)
    {
        $this->_additionalFiles['js'][] = ($module === null ? $file : '~' . $module . '/' . $file);
    }
    
    /**
     * Add a header to the response
     *
     * @param  string  $string
     * @param  boolean $replace
     * @param  integer $responseCode
     * @return void
     */
    public function addHeader($string, $replace = true, $responseCode = null)
    {
        $this->_headers[] = array($string, $replace, $responseCode);
    }
    
    /**
     * Set a variable
     *
     * @param  string $name
     * @param  string $value
     * @return void
     */
    public function __set($name, $value)
    {
        $this->_variables[$name] = $value;
    }
    
    /**
     * Get a variable
     *
     * @param  string $name
     * @return mixed
     */
    public function __get($name)
    {
        return isset($this->_variables[$name]) ? $this->_variables[$name] : null;
    }
    
    /**
     * Check if a variable is set
     *
     * @param  string $name
     * @return boolean
     */
    public function __isset($name)
    {
        return isset($this->_variables[$name]);
    }
    
    /**
     * Unset a variable
     *
     * @param  string $name
     * @return void
     */
    public function __unset($name)
    {
        unset($this->_variables[$name]);
    }
    
    /**
     * Render a file and return the content
     *
     * @param  string $filename
     * @return string
     */
    protected function _renderFile($filename)
    {
        $loader = $this->_front->getLoader();
        
        ob_start();
        
        $tempname = $loader->prepareLoad($filename);
        include $tempname;
        $loader->cleanupLoad($tempname);
        
        $content = ob_get_contents();
        ob_end_clean();
        
        return $content;
    }
    
    /**
     * Send a response to the user agent
     *
     * @return void
     */
    protected function _sendResponse()
    {
        foreach ($this->_headers as $header) {
            if ($header[2] !== null) {
                header($header[0], $header[1], $header[2]);
            } else {
                header($header[0], $header[1]);
            }
        }
        
        echo $this->_body;
    }
}
