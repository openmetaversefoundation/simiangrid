<?php if ( ! defined('BASEPATH') or !defined('SIMIAN_INSTALLER') ) exit('No direct script access allowed'); ?>
<?php require 'header.tpl.php'; ?>
    <ul>
    <?php
        foreach ( $result['permission'] as $file ) {
            echo "<li>[" . $file['type'] . "] " . $file['name'] . " - ";
            if ( $file['check'] ) {
                echo "<font color=\"green\">OK</font>";
            } else {
                echo "<font color=\"red\">CHECK</font>";
            }
            echo "</li>";
        }
    ?>
    </ul>
    <a href="?next">Continue</a>
<?php require 'footer.tpl.php'; ?>
