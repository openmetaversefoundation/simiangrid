<?php if ( ! defined('BASEPATH') or !defined('SIMIAN_INSTALLER') ) exit('No direct script access allowed'); ?>
<html>
    <head>
        <title><?php echo INSTALLER_PROJECT . " - " . $result['page'] ?></title>
    </head>
    <body>
        <div id="main">
        <div id="content">
        <div id="header">
            <div id="logo"><?php imageLink("logo.gif") ; ?></div>
            <div id="overview">
                <h1>Installer for <?php echo INSTALLER_PROJECT ?></h1>
            </div>
        <div id="main">
        <div id="user_message">
<?php
                if ( isset($result['user_message']) ) {
                    foreach ( $result['user_message'] as $message ) {
                        echo "<div class=\"message_" . $message['level'] ."\">";
                        imageLink("dialog-" . $message['level'] . ".png");
                        echo $message['text'] . "</div>";
                    }
                }
?>
        </div>