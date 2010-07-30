<?php if ( ! defined('BASEPATH') or !defined('SIMIAN_INSTALLER') ) exit('No direct script access allowed');
    define("STEP_PHP_REQUIREMENTS", 0);
    define("STEP_DB_CONFIG", 1);
    define("STEP_DB_REQUIREMENTS", 2);
    define("STEP_CONFIG", 3);
    define("STEP_PERMISSION", 4);
    define("STEP_WRITE", 5);
    define("STEP_DONE", 6);
    
    $step_mapping = array(
        'php_requirements' => STEP_PHP_REQUIREMENTS,
        'db_config' => STEP_DB_CONFIG,
        'db_requirements' => STEP_DB_REQUIREMENTS,
        'config' => STEP_CONFIG,
        'permission' => STEP_PERMISSION,
        'write' => STEP_WRITE);
    
    require 'versions.php';
    require 'db.php';
    require 'config.php';
    
    function staticUrl($thing)
    {
        return $_SERVER['SCRIPT_NAME'] . "/stream/$thing";
    }
    
    function imageLink($image)
    {
        echo "<img src=\"" . staticUrl("image/$image") . "\">";
    }
    
    function errorOut($message, $status = 500, $details = array())
    {
        $result['page'] = 'Error';
        $result['message'] = $message;
        $result['details'] = $details;
        $result['error'] = TRUE;
        header("Status: $status");
        require BASEPATH . "tpl/error.tpl.php";
        exit;
    }
    
    function streamContent($file, $type)
    {
        if ( $type == "image" ) {
            $file_bits = preg_split('/\./', $file);
            if ( count($file_bits) == 2 ) {
                if ( $file_bits[1] == "png" ) {
                   $content = "image/png"; 
                } else if ( $file_bits[1] == "gif" ) {
                    $content = "image/gif";
                }
            }
        }
        if ( !isset($content) ) {
            errorOut("Invalid Static Content", 404, array("Type specified - $type", "File specified - $file"));
        }
        $real_file = BASEPATH . "static/$type/" . $file;
        if ( is_file($real_file) ) {
            header("Content-Length:" . filesize($real_file));
            header("Content-Type: " . $content );
            $fh = fopen($real_file, "r");
            fpassthru($fh);
            fclose($fh);
            echo $data;
        } else {
            errorOut("Invalid Static Content", 404, array("Filename - $real_file"));
        }
        exit;
    }
    
    function installerStepSet($step)
    {
        $_SESSION['step'] = $step;
    }

    function isRedirect()
    {
        $is_redirect = FALSE;
        if ( isset($_GET['next']) || isset($_GET['prev']) || isset($_POST['next']) ) {
            $is_redirect = TRUE;
        }
        return $is_redirect;
    }

    function installerStep()
    {
        if ( isset($_SESSION['step']) ) {
            return (int) $_SESSION['step'];
        } else {
            return STEP_PHP_REQUIREMENTS;
        }
    }
    
    function redirectSelf()
    {    
        session_write_close();
        header("Location: " . $_SERVER['SCRIPT_NAME']);
        header("Status: 302");
        exit();
    }

    function nextStep($step)
    {
        if ( $step === STEP_PHP_REQUIREMENTS ) {
            if ( defined("WITH_DB") ) {
                $next_step = STEP_DB_CONFIG;
            } else {
                $next_step = STEP_CONFIG;
            }
        } else if ( $step === STEP_DB_CONFIG ) {
            $next_step = STEP_DB_REQUIREMENTS;
        } else if ( $step === STEP_DB_REQUIREMENTS ) {
            $next_step = STEP_CONFIG;
        } else if ( $step === STEP_CONFIG ) {
            $next_step = STEP_PERMISSION;
        } else if ( $step === STEP_PERMISSION ) {
            $next_step = STEP_WRITE;
        } else if ( $step === STEP_WRITE ) {
            $next_step = STEP_DONE;
        } else {
            $next_step = $step;
        }
        return $next_step;
    }
    
    function prevStep($step)
    {
        if ( $step === STEP_DB_CONFIG ) {
            $prev_step = STEP_PHP_REQUIREMENTS;
        } else if ( $step === STEP_DB_REQUIREMENTS ) {
            $prev_step = STEP_DB_CONFIG;
        } else if ( $step === STEP_CONFIG ) {
            $prev_step = STEP_DB_REQUIREMENTS;
        } else if ( $step === STEP_PERMISSION ) {
            $prev_step = STEP_CONFIG;
        } else if ( $step === STEP_WRITE ) {
            $prev_step = STEP_PERMISSION;
        } else {
            $prev_step = $step;
        } 
        return $prev_step;
    }

    function transitionNextStep()
    {
        if ( installerStep() === STEP_PHP_REQUIREMENTS ) {
            if ( phpRequirementsMet() ) {
                installerStepSet(nextStep(STEP_PHP_REQUIREMENTS));
            }
        } else if ( installerStep() === STEP_DB_CONFIG ) {
            if ( dbConfigValid() ) {
                installerStepSet(nextStep(STEP_DB_CONFIG));
            }
        } else if ( installerStep() === STEP_DB_REQUIREMENTS ) {
            if ( dbRequirementsMet() ) {
                installerStepSet(nextStep(STEP_DB_REQUIREMENTS));
            }
        } else if ( installerStep() === STEP_CONFIG ) {
            if ( configCheck() ) {
                installerStepSet(nextStep(STEP_CONFIG));
            }
        } else if ( installerStep() === STEP_PERMISSION ) {
            if ( permissionCheck() ) {
                installerStepSet(nextStep(STEP_PERMISSION) );
            }
        } else if ( installerStep() === STEP_WRITE ) {
            installerStepSet(nextStep(STEP_WRITE));
        }
    }
    
    function cleanPath($path)
    {
        $new_path = array();
        foreach ( $path as $path_item ) {
            if ( strlen($path_item) > 0 ) {
                array_push($new_path, $path_item);
            }
        }
        return $new_path;
    }
    
    function baseInstallDir()
    {
        $path_bits = preg_split('/\//', $_SERVER['PHP_SELF']);
        $result = "";
        foreach ( $path_bits as $path_bit ) {
            if ( $path_bit == '' ) {
                continue;
            }
            if ( $path_bit == "install.php" ) {
                break;
            }
            $result = "$result/$path_bit";
        }
        return $result;
    }
    
    function sessionInit()
    {
        $base_path = baseInstallDir();
        session_set_cookie_params(3600, $base_path, $_SERVER['SERVER_NAME'], false, false);
        session_start();
        
        if ( ! isset($_SESSION['user_message']) || $_SESSION['user_message'] == null ) {
            $_SESSION['user_message'] = array();
        }
    }
    function installerInit()
    {
        sessionInit();
        
        $result = array();
        if ( configExists() ) {
            if ( installerStep() !== STEP_DONE && installerStep() !== STEP_WRITE ) {
                installerStepSet(STEP_DONE);
                redirectSelf();
            }
        }
        $is_redirect = FALSE;
        
        if ( isset($_SERVER['PATH_INFO']) ) {
            $path_bits = preg_split('/\//', $_SERVER['PATH_INFO']);
            $path_bits = cleanPath($path_bits);
            if ( count($path_bits) == 3 ) {
                if ( $path_bits[0] == "stream" ) {
                    streamContent($path_bits[2], $path_bits[1]);
                }
            } else {
                redirectSelf();
            }
        }
        if ( isset($_GET['restart']) ) {
            session_destroy();
            redirectSelf();
        }
        
        if ( isset($_GET['next']) ) {
            transitionNextStep();
        }
        
        if ( isset($_GET['prev']) ) {
            installerStepSet(prevStep(installerStep()));
        }
        
        if ( defined(WITH_DB) ) {
            $result['with_db'] = TRUE;
        } else {
            $result['with_db'] = FALSE;
        }
        
        $result['step'] = installerStep();
        
        return $result;
    }
    
    function resultPolish($result) 
    {
        if ( isset($_SESSION['user_message']) && $_SESSION['user_message'] != null ) {
            $result['user_message'] = $_SESSION['user_message'];
            $_SESSION['user_message'] = null;
        } else {
            $result['user_message'] = array();
        }
        return $result;
    }
    
    function userMessage($level, $text) 
    {
        if ( $level == "info" ) {
            $level = "information";
        } else if ( $level == "warn" ) {
            $level = "warning";
        } else if ( $level = "error" ) {
            $level = $level;
        } else {
            return;
        }
        $message = array(
            'text'=>$text,
            'level'=>$level
        );
        
        array_push($_SESSION['user_message'], $message);
    }    
?>