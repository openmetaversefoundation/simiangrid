<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Database Connectivity Settings
|--------------------------------------------------------------------------
|
| The settings needed to access your database
|
*/
$config['db_hostname'] = "localhost";
$config['db_username'] = "root";
$config['db_password'] = "";
$config['db_database'] = "Simian";
$config['db_driver'] = "mysql";
$config['db_prefix'] = "";
$config['db_persistent'] = TRUE;

/*
|--------------------------------------------------------------------------
| Service URLs
|--------------------------------------------------------------------------
|
| Enter the URL of each SimianGrid service below
|
*/
$config['user_service'] = "http://localhost/Grid/";
$config['grid_service'] = "http://localhost/Grid/";
$config['asset_service'] = "http://localhost/Grid/?id=";
$config['inventory_service'] = "http://localhost/Grid/";

/*
|--------------------------------------------------------------------------
| Asset Driver
|--------------------------------------------------------------------------
|
| Select the inventory backend to use. Possible values:
|   SQLAssets - Database-backed asset backend.
|   MongoAssets - MongoDB-backed asset backend.
|
*/
$config['asset_driver'] = "SQLAssets";
//$config['asset_driver'] = "MongoAssets";

/* MongoDB server hostname and port number for the MongoAssets driver */
$config['mongo_server'] = "localhost:27017";
/* MongoDB database name */
$config['mongo_database'] = "SimianGrid";

/*
|--------------------------------------------------------------------------
| Inventory Driver
|--------------------------------------------------------------------------
|
| Select the inventory backend to use. Possible values:
|   ALT - Adjacency List Table. A simple and fast database-backed inventory
|   MPTT - Modified Preorder Tree Table. An advanced database-backed
|          inventory optimized for read access. WORK IN PROGRESS.
|
*/
$config['inventory_driver'] = "ALT";
//$config['inventory_driver'] = "MPTT";

/*
|--------------------------------------------------------------------------
| Error Logging Threshold
|--------------------------------------------------------------------------
|
| You can enable error logging by setting a threshold over zero. The
| threshold determines what gets logged. Threshold options are:
|
|	0 = Disables logging, Error logging TURNED OFF
|	1 = Error Messages (including PHP errors)
|   2 = Warning Messages
|	3 = Informational Messages
|	4 = Debug Messages
|
| For a live site you'll usually only enable Errors (1) to be logged otherwise
| your log files will fill up very fast.
|
*/
$config['log_threshold'] = 4;

/*
|--------------------------------------------------------------------------
| Error Logging Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| logs/ folder. Use a full server path with trailing slash.
|
*/
$config['log_path'] = "";

/*
|--------------------------------------------------------------------------
| Time Zone
|--------------------------------------------------------------------------
|
| You can change this to a PHP-supported timezone to write log files with
| your local timezone instead of UTC. http://php.net/manual/en/timezones.php
| has a list of supported timezone names.
|
*/
date_default_timezone_set("UTC");

/*
|--------------------------------------------------------------------------
| Date Format for Logs
|--------------------------------------------------------------------------
|
| Each item that is logged has an associated date. You can use PHP date
| codes to set your own date formatting
|
*/
$config['log_date_format'] = 'Y-m-d H:i:s';

/*
|--------------------------------------------------------------------------
| Message of the Day
|--------------------------------------------------------------------------
|
| The message to return to clients on successful login
|
*/
$config['message_of_the_day'] = "Welcome to SimianGrid!";

/*
|--------------------------------------------------------------------------
| Library Owner
|--------------------------------------------------------------------------
|
| UUID of the user account that owns the grid-wide inventory
|
*/
$config['library_owner_id'] = "11111111-1111-0000-0000-000100bba000";

/*
|--------------------------------------------------------------------------
| Blacklisted Packets
|--------------------------------------------------------------------------
|
| A comma-separated list of server->client messages that may not be sent
| over UDP on this grid. Do not modify this list unless you understand the
| security implications
|
*/
$config['udp_blacklist'] = "EnableSimulator,TeleportFinish,CrossedRegion";

/*
|--------------------------------------------------------------------------
| Default Assets
|--------------------------------------------------------------------------
|
| Default asset URLs for this grid. These assets must exist in the asset
| service
|
*/
$config['sun_texture_id'] = "cce0f112-878f-4586-a2e2-a8f104bba271";
$config['moon_texture_id'] = "d07f6eed-b96a-47cd-b51d-400ad4a1c428";
$config['cloud_texture_id'] = "dc4b9f0b-d008-45c6-96a4-01dd947ac621";

