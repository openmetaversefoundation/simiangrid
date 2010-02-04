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
 * @version    $Id: Base.php 14 2009-07-14 00:09:18Z dasprid $
 */

/**
 * Abstract module class
 *
 * @category   Jijawi
 * @package    Jijawi_Module
 * @copyright  Copyright (c) 2009 Ben Scholzen "DASPRiD" (http://www.dasprids.de)
 * @license    http://jijawi.org/license/new-bsd    New BSD License
 */
abstract class Jijawi_Module_Base
{
    /**
     * Front instance
     *
     * @var Jijawi_Front
     */
    protected $_front;
    
    /**
     * Configuration for this module
     *
     * @var Jijawi_Config
     */
    protected $_config;
    
    /**
     * Status of the module ('done' or 'current')
     * 
     * Will be set by the installer
     *
     * @var string
     */
    private $_status;
    
    /**
     * Store the configuration and front instance
     *
     * @param Jijawi_Front     $front
     * @param SimpleXMLElement $config
     */
    public function __construct(Jijawi_Front $front, Jijawi_Config $config)
    {
        $this->_front  = $front;
        $this->_config = $config;
    }
    
    /**
     * Set the status
     *
     * @param string $status
     */
    final public function setStatus($status)
    {
        $this->_status = $status;
    }
    
    /**
     * Return the status
     *
     * @return string
     */
    final public function getStatus()
    {
        return $this->_status;
    }
    
    /**
     * Get the label for the output
     *
     * @return string
     */
    public function getLabel()
    {
        $classname = get_class($this);
        
        if (preg_match('#^(.*?)Controller$#', $classname, $match) === 1) {
            return $match[1];
        } else {
            return $className;
        }        
    }
}
