<?php if ( ! defined('BASEPATH') or !defined('SIMIAN_INSTALLER') ) exit('No direct script access allowed'); ?>
<html>
    <head>
        <title><?php echo INSTALLER_PROJECT . " - " . $result['page']; ?></title>
        <link rel="stylesheet" type="text/css" href="<?php echo staticUrl("css/installer.css"); ?>">
        <meta http-equiv="expires" content="0">
    </head>
    <body>
        <div id="main">
        <div id="content">
        <div id="header">
            <div id="logo">
                <img src="<?php echo staticUrl("image/logo.gif"); ?>" alt="Open Metaverse Foundation logo">
            </div>
            <div id="overview">
                <h1>Installer for <?php echo INSTALLER_PROJECT ?></h1>
            </div>
        <div id="main">
        <div id="user_message">
<?php
                 foreach ( $result['user_message'] as $message ) {
                     echo "<div class=\"message_" . $message['level'] ."\">";
                     imageLink("dialog-" . $message['level'] . ".png");
                     echo $message['text'] . "</div>";
                 }
?>
        </div>
