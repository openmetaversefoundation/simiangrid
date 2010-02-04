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
 * @version    $Id: Front.php 18 2009-07-19 16:26:51Z dasprid $
 */

/**
 * Front controller
 *
 * @category   Jijawi
 * @package    Jijawi_Front
 * @copyright  Copyright (c) 2009 Ben Scholzen "DASPRiD" (http://www.dasprids.de)
 * @license    http://jijawi.org/license/new-bsd    New BSD License
 */
class Jijawi_Front
{
    /**
     * Loader instance
     *
     * @var Jijawi_Loader
     */
    protected $_loader;
    
    /**
     * View instance
     *
     * @var Jijawi_View
     */
    protected $_view;
    
    /**
     * Configuration
     *
     * @var Jijawi_Config
     */
    protected $_config;
    
    /**
     * Previosu module name
     *
     * @var string
     */
    protected $_previousModuleName;
    
    /**
     * Current module name
     *
     * @var string
     */
    protected $_currentModuleName;
    
    /**
     * Next module name
     *
     * @var string
     */
    protected $_nextModuleName;
    
    /**
     * Current stage
     *
     * @var string
     */
    protected $_currentStage;
    
    /**
     * Instantiate the front controller
     *
     * @param Jijawi_Loader $loader
     */
    public function __construct(Jijawi_Loader $loader)
    {
        $loader->load('Jijawi/View.php');
        
        $this->_loader = $loader;
        $this->_view   = new Jijawi_View($this);
    }
    
    /**
     * Get the loader instance
     *
     * @return Jijawi_Loader
     */
    public function getLoader()
    {
        return $this->_loader;
    }
    
    /**
     * Get the view instance
     *
     * @return Jijawi_View
     */
    public function getView()
    {
        return $this->_view;
    }
    
    /**
     * Get the configuration
     *
     * @return Jijawi_Config
     */
    public function getConfig()
    {
        if ($this->_config === null) {
            $this->getLoader()->load('Jijawi/Config.php');
            $this->_config = Jijawi_Config::fromXmlString($this->getLoader()->get('config.xml'));
        }
        
        return $this->_config;
    }
    
    /**
     * Get the base filename
     *
     * @return string
     */
    public function getBasename()
    {
        return basename($_SERVER['SCRIPT_FILENAME']);
    }
    
    /**
     * Dispatch the installer
     *
     * @return void
     */
    public function dispatch()
    {
        // If a static file is requested, return it
        if (isset($_GET['file'])) {
            $this->getView()->displayFile($_GET['file']);
        } else {
            // Start the session if not already done
            @session_start();
            
            // Load all modules
            $modules = $this->_loadModules();
            
            // Determine module and stage status
            $this->_determineModuleStatus($modules,
                                          (isset($_SESSION['currentModule']) ? $_SESSION['currentModule'] : null),
                                          (isset($_SESSION['currentStage']) ? $_SESSION['currentStage'] : null));

            // Check for back or cancel action
            $this->_checkForAction();

            // Run the current module
            if ($this->_currentModuleName !== null) {
                $module = $modules[$this->_currentModuleName];
                
                $proceed = $module->configure();
                
                if ($proceed) {
                    $_SESSION['currentModule'] = $this->_nextModuleName;
                    
                    if ($this->_nextModuleName === null) {
                        $_SESSION['currentStage'] = 'installation';
                    }
                    
                    $this->_reload();
                } else {
                    $this->getView()->navigation = array(
                        'back' => ($this->_previousModuleName !== null),
                        'next' => ($this->_nextModuleName !== null)
                    );
                    
                    $this->getView()->render('modules/' . $this->_currentModuleName . '/view.phtml');
                }
            } else {
                if ($this->_currentStage === 'finished') {
                    $this->getView()->render('views/finished.phtml');
                } else {
                    $this->getView()->render('views/installation.phtml');
                }
            }
        }
    }
    
    /**
     * Load all modules from the configuration
     *
     * @return array
     */
    protected function _loadModules()
    {
        // Load the abstract module class and required interfaces
        $this->getLoader()->loadOnce('Jijawi/Module/Base.php');
        $this->getLoader()->loadOnce('Jijawi/Module/Configurable.php');
        $this->getLoader()->loadOnce('Jijawi/Module/Installable.php');

        // Get the configuration
        $config = $this->getConfig();
        
        // Load all modules
        $modules = array();
        
        if (isset($config->modules)) {
            foreach ($config->modules as $moduleName => $moduleConfig) {
                $this->getLoader()->loadOnce('modules/' . $moduleName . '/controller.php');
                
                $classname = $moduleName . 'Controller';
                $module    = new $classname($this, $moduleConfig);
                
                if (!($module instanceof Jijawi_Module_Base)) {
                    throw new Exception('Module ' . $moduleName . ' does not extend Jijawi_Module_Base');
                }
            
                $modules[$moduleName] = $module; 
            }
        }
        
        return $modules;
    }
    
    /**
     * Determine the status of all modules
     *
     * @param  array  $modules
     * @param  string $currentModuleName
     * @param  stirng $currentStage
     * @return void
     */
    protected function _determineModuleStatus($modules, $currentModuleName, $currentStage)
    {
        $progress             = array();
        $previousModuleName   = null;
        $nextModuleName       = null;
        $currentModuleReached = false;
        
        foreach ($modules as $moduleName => $module) {
            if (!($module instanceof Jijawi_Module_Configurable)) {
                continue;
            }

            $progressItem = array('label' => $module->getLabel(), 'status' => null);
            
            if ($currentModuleName === null && $currentStage === null) {
                $currentModuleName      = $moduleName;
                $currentModuleReached   = true;
                $progressItem['status'] = 'current'; 
            } elseif ($moduleName === $currentModuleName) {
                $currentModuleReached   = true;
                $progressItem['status'] = 'current';
            } elseif (!$currentModuleReached) {
                $previousModuleName     = $moduleName;
                $progressItem['status'] = 'done';
            } elseif ($nextModuleName === null) {
                $nextModuleName = $moduleName;
            }
            
            $progress[] = $progressItem;
        }

        $progressItemInstallation = array('label' => 'Installation');
        $progressItemFinished     = array('label' => 'Finished');
        
        if ($currentStage === 'installation') {
            $progressItemInstallation['status'] = 'current';
        } elseif ($currentStage === 'finished') {
            $progressItemInstallation['status'] = 'done';
            $progressItemFinished['status']     = 'current';
        }
        
        $progress[] = $progressItemInstallation;
        $progress[] = $progressItemFinished;
        
        $this->_currentModuleName  = $currentModuleName;
        $this->_previousModuleName = $previousModuleName;
        $this->_nextModuleName     = $nextModuleName;
        $this->_currentStage       = $currentStage;
        $this->getView()->progress = $progress;
    }
    
    /**
     * Check for back and cancel action
     *
     * @return void
     */
    protected function _checkForAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['mode'])) {
                switch ($_POST['mode']) {
                    case 'back':
                        if ($previousModule !== null) {
                            $_SESSION['currentModule'] = null;
                        } else {
                            $_SESSION['currentModule'] = $this->_previousModuleName;
                        }
                        
                        $this->_reload();
                        break;
                    
                    case 'cancel':
                        session_destroy();
                        $this->_reload();
                        break;                    
                }
            }
        }
    }
    
    /**
     * Reload the page
     *
     * @return void
     */
    protected function _reload()
    {
        header('Location: ' . $this->getBasename());
        exit;        
    }
}
