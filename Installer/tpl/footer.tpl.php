<?php if ( ! defined('BASEPATH') or !defined('SIMIAN_INSTALLER') ) exit('No direct script access allowed'); ?>
        <?php if ( ! ( isset($result['error']) && $result['error'] == TRUE ) ) {
        echo '
            <a href="?prev">Previous</a>
            <a href="?restart">Restart</a>
        ';
         } ?>
        </div>
        </div>
        </div>
        </div>
    </body>
</html>