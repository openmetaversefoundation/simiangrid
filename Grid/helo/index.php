<?php
$RootPath = "/var/www/Grid";

// This attempts to find the path to the Grid directory
if (preg_match('/\/.*\/Grid\//',__FILE__,$matches))
    $RootPath = $matches[0];

define('BASEPATH', $RootPath);

require_once(BASEPATH . 'common/Config.php');
require_once(BASEPATH . 'common/Errors.php');
require_once(BASEPATH . 'common/Log.php');

log_message('debug','hypergrid helo request from ' . $_SERVER['REMOTE_ADDR']);
header("X-Handlers-Provided: opensim-simian");

exit();
?>