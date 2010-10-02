<?php if ( ! defined('BASEPATH') or !defined('SIMIAN_INSTALLER') ) exit('No direct script access allowed');

    function phpModuleList()
    {
        global $requiredModules, $optionalModules;
        $result = array();
        $result['optional'] = array();
        $result['required'] = array();
        foreach ( $requiredModules as $module ) {
            $check = extension_loaded($module);
            $result['required'][$module] = $check;
        }
        
        if ( ! isset($optionalModules) || $optionalModules == null ) {
            $optionalModules = array();
        }
        foreach ( $optionalModules as $module ) {
            $check = extension_loaded($module);
            if ( ! $check ) {
                userMessage('warn', "The module $module is not required for basic operation.");
            }
            $result['optional'][$module] = $check;
        }

        $_SESSION['module_list'] = $result;

        return $result;
    }

    function phpVersionCheck()
    {
        $result['current'] = phpversion();
        $result['required'] = NEED_PHP_VERSION;
        $php_check = version_compare($result['required'], $result['current']);
        if ( $php_check <= 0 ) {
            $result['check'] = TRUE;
        } else {
            $result['check'] = FALSE;
        }
        $_SESSION['php_version_check'] = $result['check'];
        return $result;
    }

    function phpRequirementsMet()
    {
        $result = FALSE;
        if ( isset($_SESSION['php_version_check']) && $_SESSION['php_version_check'] === TRUE ) {
            $result = TRUE ;
            foreach ( $_SESSION['module_list']['required'] as $module => $enabled ) {
                if ( ! $enabled ) {
                    $result = FALSE;
                    userMessage('error', "Please check your configured php modules.");
                    break;
                }
            }
        } else {
            userMessage('error', "Please update your php installation.");
        }
        return $result;
    }
?>