<?php if ( !defined('SIMIAN_INSTALLER') ) exit('No direct script access allowed');

    define("BASEPATH", str_replace("\\", "/", realpath(dirname(__FILE__)) . '/'));

    $cwd = getcwd();
    if ( is_dir($cwd . "/config") ) {
        $lockfile_base_dir = $cwd . "/config";
    } elseif ( is_dir($cwd . "/application/config") ) {
        $lockfile_base_dir = $cwd . "/application/config";
    }
    
    define("INSTALL_LOCK_FILE", $lockfile_base_dir . "/InstallerFinished_" . str_replace(' ', '_', INSTALLER_PROJECT) );

    require 'lib/common.php';
    require_once 'lib/config.php';

    $result = installerInit();

    if ( installerStep() === STEP_PHP_REQUIREMENTS ) {
        if ( isRedirect() ) {
            redirectSelf();
        }
        $result['page'] = "PHP Requirements";
        $result['php_version'] = phpVersionCheck();
        $result['modules'] = phpModuleList();
    } else if ( installerStep() === STEP_DB_CONFIG ) {
        if ( $_SERVER['REQUEST_METHOD'] == "GET" ) {
            if ( isRedirect() ) {
                redirectSelf();
            }
            $result['page'] = "DB Config";
            $result['db_config'] = dbGetConfig();
        } else if ( $_SERVER['REQUEST_METHOD'] == "POST" ) {
            dbProcessConfig();
            transitionNextStep();
            redirectSelf();
        }
    } else if ( installerStep() === STEP_DB_REQUIREMENTS ) {
        if ( isRedirect() ) {
            redirectSelf();
        }   
        $result['page'] = "DB Requirements";
        $result['mysql_check'] = dbVersionCheck();
        $result['db_config'] = dbGetConfig();
    } else if ( installerStep() === STEP_CONFIG ) {
        if ( $_SERVER['REQUEST_METHOD'] == "GET" ) {
            if ( isRedirect() ) {
                redirectSelf();
            }
            $result['page'] = "Configuration";
            $result['configuration'] = configGet();
        } else if ( $_SERVER['REQUEST_METHOD'] == "POST" ) {
            if ( processConfig() ) {
                transitionNextStep();
            }
            redirectSelf();
        }
    } else if ( installerStep() == STEP_PERMISSION ) {    
        if ( isRedirect() ) {
            redirectSelf();
        }
        $result['page'] = 'Check Permission';
        $result['permission'] = permissionProcess();
    } else if ( installerStep() === STEP_WRITE ) {
        $result['page'] = 'Write Files';
        if ( isRedirect() ) {
            redirectSelf();
        }
        $result['config'] = configWrite();
        $result['db'] = dbWrite();
        if ( $result['config'] && $result['db'] ) {
            transitionNextStep();
            redirectSelf();
        }
    } else if ( installerStep() === STEP_DONE ) {
        $result['page'] = 'Done';
    } else {
        installerStepSet(STEP_PHP_REQUIREMENTS);
        redirectSelf();
    } 

    $result = resultPolish($result);
    session_write_close();
    
    if ( installerStep() === STEP_PHP_REQUIREMENTS ) {
        require "tpl/requirements.tpl.php";
    } else if ( installerStep() === STEP_DB_CONFIG ) {
        require "tpl/db_configuration.tpl.php";
    } else if ( installerStep() === STEP_DB_REQUIREMENTS ) {
        require "tpl/db_requirements.tpl.php";
    } else if ( installerStep() === STEP_CONFIG ) {
        require "tpl/configuration.tpl.php";
    } else if ( installerStep() === STEP_PERMISSION ) {
        require "tpl/permission.tpl.php";
    } else if ( installerStep() === STEP_WRITE ) {
        require "tpl/write.tpl.php";
    } else if ( installerStep() === STEP_DONE ) {
        require "tpl/done.tpl.php";
    }

?>
