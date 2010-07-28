<?php if ( ! defined('BASEPATH') or !defined('SIMIAN_INSTALLER') ) exit('No direct script access allowed'); ?>
<?php require 'header.tpl.php'; ?>
<h2><?php echo $result['message'] ?></h2>
<ul>
<?php
    foreach($result['details'] as $detail) {
        echo "<li>$detail</li>";
    }
?>    
</ul>
<?php require 'footer.tpl.php'; ?>
