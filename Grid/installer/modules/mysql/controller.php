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
 * @version    $Id: controller.php 18 2009-07-19 16:26:51Z dasprid $
 */

/**
 * Mysql module
 *
 * @category   Jijawi
 * @package    Jijawi_Module
 * @copyright  Copyright (c) 2009 Ben Scholzen "DASPRiD" (http://www.dasprids.de)
 * @license    http://jijawi.org/license/new-bsd    New BSD License
 */
class MysqlController extends Jijawi_Module_Base
      implements Jijawi_Module_Configurable, Jijawi_Module_Installable
{
    /**
     * @see    Jijawi_Module::getLabel()
     * @return string
     */
    public function getLabel()
    {
        return 'Database';
    }
    
    /**
     * @see    Jijawi_Module_Configurable::view()
     * @return boolean
     */
    public function configure()
    { 
        $view = $this->_front->getView();       
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $hostname = trim($_POST['hostname']);
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);
            $database = trim($_POST['database']);
            
            $view->mysqlHostname = $hostname;
            $view->mysqlUsername = $username;
            $view->mysqlPassword = $password;
            $view->mysqlDatabase = $database;

            $error = null;
            $link  = @mysql_connect($hostname, $username, $password);
            
            if (!$link) {
                $error = mysql_error();
            } else {
                $selected = @mysql_select_db($database);
                
                if (!$selected) {
                    $error = mysql_error();
                }
            }
            
            if ($error !== null) {
                $view->mysqlError = $error;
                return false;
            } else {
                $_SESSION['mysqlHostname'] = $hostname;
                $_SESSION['mysqlUsername'] = $username;
                $_SESSION['mysqlPassword'] = $password;
                $_SESSION['mysqlDatabase'] = $database;
                
                return true; 
            }
        } elseif (isset($_SESSION['mysqlHostname'])) {
            $view->mysqlHostname = $_SESSION['mysqlHostname'];
            $view->mysqlUsername = $_SESSION['mysqlUsername'];
            $view->mysqlPassword = $_SESSION['mysqlPassword'];
            $view->mysqlDatabase = $_SESSION['mysqlDatabase'];
        } else {
            $view->mysqlHostname = 'localhost';
        }
        
        return false;
    }

    /**
     * @see    Jijawi_Module_Installable::install()
     * @return void
     */
    public function install()
    {
        
    }
}