<?php if ( ! defined('BASEPATH') or !defined('SIMIAN_INSTALLER') ) exit('No direct script access allowed'); ?>
<?php require 'header.tpl.php'; ?>
<h2>Writing...</h2>
<ul>
<li>Configuration File(s) - 
<?php
    if ( $result['config'] === TRUE ) {
        echo "<font color=\"green\">OK</font>";
    } else {
        echo "<font color=\"red\">CHECK</font>";
    }
?>
</li>
<li>Database - 
<?php
    if ( $result['db'] === TRUE ) {
        echo "<font color=\"green\">OK</font>";
    } else {
        echo "<font color=\"red\">CHECK</font>";
    }
?>
</li>
</ul>
<?php require 'footer.tpl.php'; ?>
