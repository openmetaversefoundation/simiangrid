<?php
    define(SIMIAN_INSTALLER, TRUE);

    define(NEED_PHP_VERSION, "5.3");
    define("MYSQL_VERSION", "5.1");
    define("WITH_DB", TRUE);
    define("INSTALLER_PROJECT", 'Simian Grid Frontend');
    define("DB_CONFIG_FILE", "application/config/database.php");
    
    $requiredMysqlVersion = "5.0";
    $requiredModules = array(
        'curl',
        'mysql',
		'imagick'
    );

	$dbCheckTables = array(
		'sgf_login_attempts',
		'sgf_sessions',
		'sgf_user_autologin',
		'sgf_user_temp',
		'sgf_users'
	);
    
    $defaultDB['user'] = 'root';
    $defaultDB['host'] = '127.0.0.1';
    $defaultDB['db'] = 'SimianFrontend';
    
    $dbSchemas = array('schema.sql');
    
    $dbFixtures = array();
    
    $writableDirectories = array('system/logs');
    
    $configOptions['user_service']['name'] = "User Server";
    $configOptions['user_service']['description'] = "The URL of the User Server";
    $configOptions['user_service']['default'] = "http://localhost/Grid/";
    $configOptions['user_service']['string'] = "@@USER_SERVICE@@";
    $configOptions['user_service']['file'] = "application/config/config.php";
    
    $configOptions['grid_service']['name'] = "Grid Server";
    $configOptions['grid_service']['description'] = "The URL of the Grod Server";
    $configOptions['grid_service']['default'] = "http://localhost/Grid/";
    $configOptions['grid_service']['string'] = "@@GRID_SERVICE@@";
    $configOptions['grid_service']['file'] = "application/config/config.php";
    
    $configOptions['asset_service']['name'] = "Asset Server";
    $configOptions['asset_service']['description'] = "The URL of the Asset Server";
    $configOptions['asset_service']['default'] = "http://localhost/Grid/?id=";
    $configOptions['asset_service']['string'] = "@@ASSET_SERVICE@@";
    $configOptions['asset_service']['file'] = "application/config/config.php";
    
    $configOptions['inventory_service']['name'] = "Inventory Server";
    $configOptions['inventory_service']['description'] = "The URL of the Inventory Server";
    $configOptions['inventory_service']['default'] = "http://localhost/Grid/";
    $configOptions['inventory_service']['string'] = "@@INVENTORY_SERVICE@@";
    $configOptions['inventory_service']['file'] = "application/config/config.php";
    
    $configOptions['login_service']['name'] = "Login Server";
    $configOptions['login_service']['description'] = "The URL of the Login Server";
    $configOptions['login_service']['default'] = "http://localhost/Grid/login/";
    $configOptions['login_service']['string'] = "@@LOGIN_SERVICE@@";
    $configOptions['login_service']['file'] = "application/config/config.php";
    
    $configOptions['grid_name']['name'] = "Grid Name";
    $configOptions['grid_name']['description'] = "The name of your grid.";
    $configOptions['grid_name']['default'] = "SimianGrid";
    $configOptions['grid_name']['string'] = "@@GRID_NAME@@";
    $configOptions['grid_name']['file'] = "application/config/config.php";
    
    $configOptions['grid_name_short']['name'] = "Short Grid Name";
    $configOptions['grid_name_short']['description'] = "The name of your grid. Used as a URL prefix I think?";
    $configOptions['grid_name_short']['default'] = "simian";
    $configOptions['grid_name_short']['string'] = "@@GRID_NAME_SHORT@@";
    $configOptions['grid_name_short']['file'] = "application/config/config.php";
    
    $configOptions['base_url']['name'] = "Base URL";
    $configOptions['base_url']['description'] = "The base url to use when constructing urls.";
    $configOptions['base_url']['default'] = "http://localhost/Simian/GridFrontend/";
    $configOptions['base_url']['string'] = "@@BASE_URL@@";
    $configOptions['base_url']['file'] = "application/config/config.php";

    require '../Installer/install.php';

?>
