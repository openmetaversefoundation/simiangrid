<?php if ( ! defined('BASEPATH') or !defined('SIMIAN_INSTALLER') ) exit('No direct script access allowed');

    function configExists()
    {
        $config_files = configFileList();
        $result = FALSE;
        foreach ( $config_files as $config_file ) {
            if ( is_file($config_file) ) {
                userMessage('warn', "Config file " . getcwd() . "/$config_file already exists.");
                if ( $result == FALSE ) {
                    $result = TRUE;
                }
            }
        }
        return $result;
    }

    function configFindValue($name, $config_entry)
    {
        $config_file = getcwd() . "/" . $config_entry['file'];
        $config_value = $config_entry['default'];
        if ( file_exists($config_file) ) {
            include $config_file;
            if ( isset($config[$name]) ) {
                $config_value = $config[$name];
            }
        }
        return $config_value;
    }

    function configGetValue($name)
    {
        global $configOptions;
        if ( isset($_SESSION['config'][$name] ) ) {
            return $_SESSION['config'][$name];
        } else {
            foreach ( $configOptions as $key => $config ) {
                if ( $key == $name ) {
                    return configFindValue($key, $config);
                }
            }
        }
        return null;
    }
    
    function configGet()
    {
        $result = array();
        global $configOptions;
        foreach ( $configOptions as $key => $options ) {
            $entry = array();
            $entry['name'] = $key;
            $entry['value'] = configGetValue($key, $key);
            $entry['label'] = $options['name'];
            array_push($result, $entry);
        }
        return $result;
    }
    
    function configCheck()
    {
        #todo - actually validate config entries
        return TRUE;
    }
    
    function configFileList()
    {
        global $configOptions;
        $writableFiles = array();
        foreach ( $configOptions as $config => $options ) {
            if ( array_search($options['file'], $writableFiles) === FALSE ) {
                array_push($writableFiles, $options['file']);
            }
        }
        if ( defined("DB_CONFIG_FILE") ) {
            array_push($writableFiles, DB_CONFIG_FILE);
        }
        return $writableFiles;
    }
    
    function permissionCheckSession($name, $type)
    {
        $check = FALSE;
        if ( isset($_SESSION['permission']) ) {
            foreach ( $_SESSION['permission'] as $perm ) {
                if ( $perm['name'] == $name && $perm['type'] == $type ) {
                    $check = $perm['check'];
                    break;
                }
            }
        }
        return $check;
    }
    
    function permissionCheck()
    {
        global $writableDirectories;
        $config_files = configFileList();
        $check = TRUE;
        foreach ( $writableDirectories as $directory ) {
            if ( ! permissionCheckSession($directory, "dir") ) {
                $check = FALSE;
            }
        }
        foreach ( $config_files as $file ) {
            if ( ! permissionCheckSession($file, 'file') ) {
                $check = FALSE;
            }
        }
        return $check;
    }
    
    function permissionProcess()
    {
        $result = array();
        
        global $writableDirectories, $configOptions;
        
        $writableFiles = configFileList();
        
        foreach ( $writableDirectories as $directory ) {
            $check_result = is_writable($directory);
            if ( ! $check_result ) {
                userMessage("error", "Directory " . getcwd() . "/$directory is not writable (It must be writeable by the account the webserver runs under).");
            }
            $item = array (
                'name' => $directory,
                'check' => $check_result,
                'type' => 'dir');
            array_push($result, $item);
        }
        foreach ( $writableFiles as $path ) {
            $check = FALSE;
            if ( ! is_file($path) ) {
                $path_dir = dirname($path);
                if ( is_dir($path_dir) ) {
                    $check = is_writable($path_dir);
                    if ( ! $check ) {
                        userMessage("error", "Directory " . getcwd() . "/$path_dir is not writable (It must be writeable by the account the webserver runs under).");
                    }
                } else {
                    userMessage("error", "Unable to find directory $path.");
                }
            } else {
                $check = is_writable($path);
                if ( ! $check ) {
                    userMessage("error", "File $path is not writable (It must be writeable by the account the webserver runs under).");
                }
            }
            $item = array (
                'name' => $path,
                'check' => $check,
                'type' => 'file');
            array_push($result, $item);
        }
        $_SESSION['permission'] = $result;
        return $result;
    }
    
    function processConfig()
    {
        global $configOptions;
        $results = array();
        $success = TRUE;
        foreach ( $configOptions as $key => $config ) {
            $config_value = $_POST[$key];
            if ( $config_value != null ) {
                $results[$key] = $config_value;
            }
        }
        $_SESSION['config'] = $results;
        return $success;
    }
    
    function getFileConfigs($config_file) {
        global $configOptions;
        $configs = array();
        foreach ( $configOptions as $key => $config ) {
            if ( $config['file'] == $config_file ) {
                $configs[$key] = $config;
            }
        }
        return array_merge($configs, dbConfigs());
    }
    
    function configWrite()
    {
        global $configOptions;
        $config_files = configFileList();
        foreach ( $config_files as $config_file ) {
            $these_configs = getFileConfigs($config_file);
            $file_scratch = file_get_contents("$config_file.tpl");
            foreach ( $these_configs as $key => $config ) {
                if ( strstr($key, "db_") === FALSE ) {
                    $value = configGetValue($key);
                } else {
                    $value = configGetDBValue($key);
                }
                $new_scratch = str_replace($config['string'], $value, $file_scratch);
                $file_scratch = $new_scratch;
            }
            #TODO - error handling !
            $handle = fopen($config_file, "w");
            if ( $handle === FALSE ) {
                userMessage("error", "Unable to open $config_file");
                return FALSE;
            } else {
                $write_result = fwrite($handle, $file_scratch);
                if ( $write_result === FALSE || $write_result < strlen($file_scratch) ) {
                    userMessage("error", "Problem writing " . getcwd() . "/$config_file");
                    return FALSE;
                } else {
                    fclose($handle);
                }
            }
        }
        return TRUE;
    }

?>
