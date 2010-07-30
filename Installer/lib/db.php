<?php if ( ! defined('BASEPATH') or !defined('SIMIAN_INSTALLER') ) exit('No direct script access allowed');
    
    function dbGetConfig()
    {
        $db_session = $_SESSION['db_config'];
        
        if ( $db_session != null ) {
            return $db_session;
        } else {
            global $defaultDB;
            return $defaultDB;
        }
    }
    
    function configGetDBValue($key) {
        $dbconf = dbGetConfig();
        if ( $key === "db_user" ) {
            return $dbconf['user'];
        } else if ( $key === "db_pass" ) {
            return $dbconf['password'];
        } else if ( $key === "db_host" ) {
            return $dbconf['host'];
        } else if ( $key === "db_name" ) {
            return $dbconf['db'];
        } else {
            return null;
        }
    }
    
    function dbConfigs()
    {
        $result = array();
        $dbconf = dbGetConfig();
        
        $result['db_user']['default'] = $dbconf['user'];
        $result['db_user']['string'] = "@@DB_USER@@";
        $result['db_pass']['default'] = $dbconf['password'];
        $result['db_pass']['string'] = "@@DB_PASSWORD@@";
        $result['db_name']['default'] = $dbconf['db'];
        $result['db_name']['string'] = "@@DB_NAME@@";
        $result['db_host']['default'] = $dbconf['host'];
        $result['db_host']['string'] = "@@DB_HOST@@";
        
        return $result;
    }
    
    function dbConfigValid()
    {
        $result = FALSE;
        
        $result['user'] = $_SESSION['db_config']['user'];
        $result['password'] = $_SESSION['db_config']['password'];
        $result['host'] = $_SESSION['db_config']['host'];
        $result['db'] = $_SESSION['db_config']['db'];
        
        if ( $user != null && $db != null ) {
            $result = TRUE;
        }
        return $result;
    }
    
    function dbProcessConfig()
    {
        $user = $_POST['user'];
        $password = $_POST['password'];
        $host = $_POST['host'];
        $db = $_POST['schema'];

        $result = array();
        if ( $user != null and $db != null ) {
            $result['user'] = $user;
            $result['db'] = $db;
            if ( $host != null ) {
                $result['host'] = $host;
            } else {
                $result['host'] = '127.0.0.1';
            }
            
            if ( $password != null ) {
                $result['password'] = $password;
            }
        }
        $_SESSION['db_config'] = $result;
        return $result;
    }

	function dbRequirementsMet()
	{
		if ( $_SESSION['db_version']['check'] === TRUE && $_SESSION['db_version']['db_check'] === TRUE ) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function dbHandle()
	{	
	    if ( ! isset($_SESSION['db_config']) ) {
	        return FALSE;
	    }
	    $user = $_SESSION['db_config']['user'];
	    $password = $_SESSION['db_config']['password'];
	    $host = $_SESSION['db_config']['host'];
        error_reporting(E_ERROR);
        $db = mysqli_init();
        $check = mysqli_real_connect($db, $host, $user, $password);
        error_reporting(E_WARNING);
        if ( $check === FALSE ) {
            userMessage("error", "DB Error - " . mysqli_connect_error($db));
            return FALSE;
        }
		return $db;
	}
	
	function dbVersionCheck()
	{
		$db = dbHandle();
        $result = array();
		$result['required'] = MYSQL_VERSION;
        if ( ! $db ) {
            $result['connect'] = FALSE;
        } else {
            $result['connect'] = TRUE;
            $result['current'] = mysqli_get_server_info($db);
            #i wonder if version_compare is fine to use on mysql version numbers?
            $mysql_check = version_compare(MYSQL_VERSION, $result['current']);
            if ( $mysql_check <= 0 ) {
                $result['check'] = TRUE;
            } else {
                $result['check'] = FALSE;
            }
			$result['db_check'] = dbSelect($db);
			mysqli_close($db);
        }
		$_SESSION['db_version'] = $result;
		return $result;
	}
	
	function dbSelect($db)
	{
        if ( ! isset($_SESSION['db_config']) ) {
	        return FALSE;
	    }
	    if ( $db === FALSE ) { 
	        return FALSE;
	    }
	    $schema = $_SESSION['db_config']['db'];
	    $check = mysqli_select_db($db, $schema);
	    if ( $check === FALSE ) {
            userMessage("error", "Problem selecting database " . $schema . " - " . mysqli_error($db));
            return FALSE;
        } else {
            return dbEmpty($db);
        }
	}
	
	function dbEmpty($db)
	{
	    $result = mysqli_query($db, "SHOW TABLES");
	    if ( ! $result ) {
	        userMessage("error", "Problem scanning database " . $schema . " - " . mysqli_error($db));
	    }
	    $tables = mysqli_fetch_assoc($result);
	    if ( count($tables) == 0 ) {
	        return TRUE;
	    } else {
	        userMessage("error", "Database not empty");
	        return FALSE;
	    }
	}

    function dbFlush($db) {
        $done = FALSE;
        while ( ! $done ) {
            $result = mysqli_store_result($db);
            if ( mysqli_more_results($db) ) {
                if ( mysqli_errno($db) != 0 ) {
                    userMessage("warn", "DB Problem - " . mysqli_error($db) );
                }
                mysqli_next_result($db);
                if ( mysqli_errno($db) != 0 ) {
                    userMessage("warn", "DB Problem - " . mysqli_error($db) );
                }
            } else {
                $done = TRUE;
            }
        }
    }

    function dbQueriesFromFile($db, $file)
    {
        $contents = file($file);
        $current_query = '';
        foreach ( $contents as $line_num => $line ) {
            if (substr($line, 0, 2) != '--' && $line != '') {
                $current_query .= $line;
                if (substr(trim($line), -1, 1) == ';') {
                    $result = mysqli_query($db, $current_query);
                    if ( $result === FALSE || mysqli_errno($db) != 0 ) {
                        userMessage("error", "Problem loading schema $schema - " . mysqli_error($db) );
                        return FALSE;
                    }
                    dbFlush($db);
                    $current_query = '';
                }
            }
        }
        
    }
    function dbWrite()
    {
        global $dbSchemas, $dbFixtures;
		$db = dbHandle();
		if ( ! dbSelect($db) ) {
		    return FALSE;
		}
        foreach ( $dbSchemas as $schema ) {
            dbQueriesFromFile($db, $schema);
        }
        
        foreach ( $dbFixtures as $fixture ) {
            $result = mysqli_multi_query($db, file_get_contents($fixture) );
            if ( $result === FALSE || mysqli_errno($db) != 0 ) {
                userMessage("error", "Problem loading fixture $fixture - " . mysqli_error($db) );
                return FALSE;
            }
            dbFlush($db);
        }
        mysqli_close($db);
        return TRUE;
    }

?>