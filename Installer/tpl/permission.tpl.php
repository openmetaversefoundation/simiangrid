<?php if ( ! defined('BASEPATH') or !defined('SIMIAN_INSTALLER') ) exit('No direct script access allowed'); ?>
<?php require 'header.tpl.php'; ?>
    <ul>
    <?php
        foreach ( $result['permission'] as $file ) {
            if ( $file['check'] ) {
                echo "<li>[" . $file['type'] . "] " . getcwd() . "/" . $file['name'] . " - ";
                echo "<font color=\"green\">OK</font>";
                echo "</li>";
            } 
        }
    ?>
    </ul>
    <a href="?next">Next Step</a>
<?php require 'footer.tpl.php'; ?>
