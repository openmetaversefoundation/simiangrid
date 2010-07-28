<?php if ( ! defined('BASEPATH') or !defined('SIMIAN_INSTALLER') ) exit('No direct script access allowed');

    function phpModuleList($requiredModules)
    {
        $result = array();
        foreach ( $requiredModules as $module ) {
            $check = extension_loaded($module);
            $result[$module] = $check;
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
        if ( $_SESSION['php_version_check'] === TRUE ) {
            $result = TRUE ;
            foreach ( $_SESSION['module_list'] as $module => $enabled ) {
                if ( ! $enabled ) {
                    $result = FALSE;
                    userMessage('error', "Please check your configured php modules");
                    break;
                }
            }
        } else {
            userMessage('error', "Please update your php installation.");
        }
        return $result;
    }
?>