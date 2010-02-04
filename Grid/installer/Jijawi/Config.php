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
 * @version    $Id: Config.php 14 2009-07-14 00:09:18Z dasprid $
 */

/**
 * Config reader
 *
 * @category   Jijawi
 * @package    Jijawi_Front
 * @copyright  Copyright (c) 2009 Ben Scholzen "DASPRiD" (http://www.dasprids.de)
 * @license    http://jijawi.org/license/new-bsd    New BSD License
 */
class Jijawi_Config
{
    /**
     * Children of this config
     *
     * @var array
     */
    protected $_children = array();
    
    /**
     * Create the config from an XML string
     *
     * @param string $xmlString
     */
    public static function fromXmlString($xmlString)
    {
        $xml = @simplexml_load_string($xmlString);
        
        if ($xml === false) {
            throw new Exception('Invalid XML supplied');
        }
        
        return self::_xmlToConfig($xml);
    }
    
    /**
     * Convert an XML object to a config object
     *
     * @param  SimpleXMLElement $xml
     * @return mixed
     */
    protected static function _xmlToConfig(SimpleXMLElement $xml)
    {
        if (count($xml) > 0) {
            $config = new Jijawi_Config();
        
            foreach ($xml as $key => $value) {
                if (isset($value['array-children'])) {
                    $result = array();
                    
                    if (isset($value->{$value['array-children']})) {
                        foreach ($value->{$value['array-children']} as $subKey => $subValue) {
                            if (isset($subValue['name'])) {
                                $result[(string) $subValue['name']] = self::_xmlToConfig($subValue);
                            } else {
                                $result[] = self::_xmlToConfig($subValue);
                            }
                        }
                    }
                    
                    $config->{$key} = $result;
                } else {               
                    if (count($value->children()) > 0) {
                        $value = self::_xmlToConfig($value);
                    } else {
                        $value = (string) $value;
                    }
                    
                    if (isset($config->{$key})) {
                        if (!is_array($config->{$key})) {
                            $oldValue = $config->{$key};
                            $config->{$key} = array($oldValue, $value);
                        } else {
                            $array = $config->{$key};
                            $array[] = $value;
                            $config->{$key} = $array;
                        }
                    } else {
                        $config->{$key} = $value;
                    }
                }
            }
            
            return $config;
        } else {
            return (string) $xml;
        }
    }
    
    /**
     * Set a config key
     *
     * @param  string $name
     * @param  string $value
     * @return void
     */
    public function __set($name, $value)
    {
        $this->_children[$name] = $value;
    }
    
    /**
     * Get a config key
     *
     * @param  string $name
     * @return mixed
     */
    public function __get($name)
    {
        return isset($this->_children[$name]) ? $this->_children[$name] : null;
    }
    
    /**
     * Check if a config key is set
     *
     * @param  string $name
     * @return boolean
     */
    public function __isset($name)
    {
        return isset($this->_children[$name]);
    }
    
    /**
     * Unset a config key
     *
     * @param  string $name
     * @return void
     */
    public function __unset($name)
    {
        unset($this->_children[$name]);
    }
}
