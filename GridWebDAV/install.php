<?php
    define("SIMIAN_INSTALLER", TRUE);

    define("NEED_PHP_VERSION", "5.3");
    define("INSTALLER_PROJECT", 'Simian Grid WebDAV');
    
    $requiredModules = array(
        'curl',
    );

    $writableDirectories = array('logs');
    
    $configOptions['user_service']['name'] = "User Server";
    $configOptions['user_service']['description'] = "The URL of the User Server";
    $configOptions['user_service']['default'] = "http://localhost/Grid/";
    $configOptions['user_service']['string'] = "@@USER_SERVICE@@";
    $configOptions['user_service']['file'] = "config/config.php";
    
    $configOptions['asset_service']['name'] = "Asset Server";
    $configOptions['asset_service']['description'] = "The URL of the Asset Server";
    $configOptions['asset_service']['default'] = "http://localhost/Grid/?id=";
    $configOptions['asset_service']['string'] = "@@ASSET_SERVICE@@";
    $configOptions['asset_service']['file'] = "config/config.php";
    
    $configOptions['inventory_service']['name'] = "Inventory Server";
    $configOptions['inventory_service']['description'] = "The URL of the Inventory Server";
    $configOptions['inventory_service']['default'] = "http://localhost/Grid/";
    $configOptions['inventory_service']['string'] = "@@INVENTORY_SERVICE@@";
    $configOptions['inventory_service']['file'] = "config/config.php";

    $configOptions['base_url']['name'] = "Base URL Path";
    $configOptions['base_url']['description'] = "The base URL path to use when constructing URLs.  Presently 'index.php' is a required suffix.";
    $configOptions['base_url']['default'] = "/GridWebDAV/index.php";
    $configOptions['base_url']['string'] = "@@BASE_URL_PATH@@";
    $configOptions['base_url']['file'] = "config/config.php";

    
    require '../Installer/install.php';

?>
