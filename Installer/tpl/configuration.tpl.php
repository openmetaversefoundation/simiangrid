<?php if ( ! defined('BASEPATH') or !defined('SIMIAN_INSTALLER') ) exit('No direct script access allowed'); ?>
<?php require 'header.tpl.php'; ?>
        <form name="db_configuration" method="POST">
            <table>
                <tr>
                    <th>Config Item</th><th>Value</th>
                </tr>
                <?php
                    foreach ( $result['configuration'] as $config ) {
                        if ( isset($config['value']) ) {
                            $value = $config['value'];
                        } else {
                            $value = "";
                        }
                        $name = $config['name'];
						$label = $config['label'];
                        echo "
                    <tr>
                        <td>$label</td><td><input size=\"100\" type=\"text\" name=\"$name\" value=\"$value\"/></td>
                    </tr>";
                    }
                ?>
            </table>
            <input type="hidden" name="next" />

<ul class="progress_buttons">
    <li><a href="javascript: document.db_configuration.submit();"><span>Continue</span></a></li>
<?php if ( ! ( isset($result['error']) && $result['error'] == TRUE )) {
    if ( $result['step'] != STEP_DONE ) {
        echo "    <li><a href=\"?prev\"><span>Previous Step</a></span></li>\n";
    }
    echo "    <li><a href=\"?restart\"><span>Start from begining</a><span></li>\n";
} ?></ul>

        </form>

        </div>
        </div>
        </div>
        </div>
    </body>
</html>

