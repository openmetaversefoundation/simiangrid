<?php if ( ! defined('BASEPATH') or !defined('SIMIAN_INSTALLER') ) exit('No direct script access allowed'); ?>
        <?php if ( ! ( isset($result['error']) && $result['error'] == TRUE )  ) {
        if ( $result['step'] != STEP_DONE ) {
            echo '<a href="?prev">Previous</a>';
        }
        echo ' <a href="?restart">Restart</a>';
         } ?>
        </div>
        </div>
        </div>
        </div>
    </body>
</html>