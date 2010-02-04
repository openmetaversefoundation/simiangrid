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
 * @version    $Id: controller.php 15 2009-07-18 15:33:57Z dasprid $
 */

/**
 * Sytemcheck module
 *
 * @category   Jijawi
 * @package    Jijawi_Module
 * @copyright  Copyright (c) 2009 Ben Scholzen "DASPRiD" (http://www.dasprids.de)
 * @license    http://jijawi.org/license/new-bsd    New BSD License
 */
class SystemcheckController extends Jijawi_Module_Base
      implements Jijawi_Module_Configurable
{
    /**
     * @see    Jijawi_Module_Base::getLabel()
     * @return string
     */
    public function getLabel()
    {
        return 'System-Check';
    }
    
    /**
     * @see    Jijawi_Module_Configurable::configure()
     * @return boolean
     */
    public function configure()
    {
        $requirements = array();
        
        // Parse the phpinfo
        ob_start();
        phpinfo(-1);
        $phpInfoData = ob_get_contents();
        ob_end_clean();
        $phpInfo = $result = array();

        if (preg_match_all('/<tr><td class="e">(.*?)<\/td><td class="v">(.*?)<\/td>(:?<td class="v">(.*?)<\/td>)?<\/tr>/', $phpInfoData, $result, PREG_SET_ORDER)) {
            foreach ($result as $line) {
                if ($line[2] == '<i>no value</i>') {
                    continue;
                }

                $phpInfo[trim($line[1])] = trim($line[2]);
            }
        }
        
        // Check PHP Version
        if (isset($this->_config->{'php-min-version'})) {
            $required = (string) $this->_config->{'php-min-version'};
            $current  = phpversion();
             
            $requirements[] = array(
                'PHP Version',
                $required . ' or higher',
                $current,
                (version_compare($required, $current) <= 0)
            );
        }
        
        // Check for safe-mode off
        if ($this->_config->{'require-safe-mode-off'} === 'true') {
            $required = 'off';
            $current  = in_array(ini_get('safe_mode'), array('', '0', 'off')) ? 'off': 'on';
             
            $requirements[] = array(
                'Safe Mode',
                $required,
                $current,
                ($required === $current)
            );
        }
        
        // Writable directories and files
        if (isset($this->_config->writable)) {
            foreach ($this->_config->writable as $writable) {
                $required = 'yes';
                $current  = is_writable($writable) ? 'yes' : 'no';
                
                $requirements[] = array(
                    'Path \'' . $writable . '\' writable',
                    $required,
                    $current,
                    ($required === $current)
                );
            }
        }
        
        // PHP extensions
        if (isset($this->_config->{'php-modules'})) {
            foreach ($this->_config->{'php-modules'} as $module) {
                $required = 'yes';
                $current  = extension_loaded((string) $module) ? 'yes' : 'no';
                 
                $requirements[] = array(
                    'PHP extension ' . (string) $module . ' installed',
                    $required,
                    $current,
                    ($required === $current)
                );
            }
        }
        
        // Apache modules
        if (isset($this->_config->{'apache-modules'})) {
            foreach ($this->_config->{'apache-modules'} as $module) {
                $required = 'yes';
                if (!isset($phpInfo['Loaded Modules'])) {
                    $current = 'no';
                } else {
                    $current = (strstr($phpInfo['Loaded Modules'], (string) $module) !== false) ? 'yes' : 'no';
                }
                 
                $requirements[] = array(
                    'Apache module ' . (string) $module . ' installed',
                    $required,
                    $current,
                    ($required === $current)
                );
            }
        }

        // Check if there was a failure
        $failure = false;
        foreach ($requirements as $requirement) {
            if (!$requirement[3]) {
                $failure = true;
                break;
            }
        }
        
        // Assign required variables to the installer
        $this->_front->getView()->failure      = $failure;
        $this->_front->getView()->requirements = $requirements;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return (!$failure);
        }
        
        return false;
    }
}
