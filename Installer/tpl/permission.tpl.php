<?php if ( ! defined('BASEPATH') or !defined('SIMIAN_INSTALLER') ) exit('No direct script access allowed'); ?>
<?php require 'header.tpl.php'; ?>
    <ul>
    <?php
        foreach ( $result['permission'] as $file ) {
            if ( $file['check'] ) {
                echo "<li>[" . $file['type'] . "] " . $file['name'] . " - ";
                echo "<font color=\"green\">OK</font>";
                echo "</li>";
            }
        }
    ?>
    </ul>

<ul class="progress_buttons">
    <li><a href="?next"><span>Continue</span></a></li>
<?php if ( ! ( isset($result['error']) && $result['error'] == TRUE )) {
    if ( $result['step'] != STEP_DONE ) {
        echo "    <li><a href=\"?prev\"><span>Previous Step</a></span></li>\n";
    }
    echo "    <li><a href=\"?restart\"><span>Start from begining</a><span></li>\n";
} ?>
</ul>

        </div>
        </div>
        </div>
        </div>
    </body>
</html>

