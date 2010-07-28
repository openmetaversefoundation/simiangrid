<?php if ( ! defined('BASEPATH') or !defined('SIMIAN_INSTALLER') ) exit('No direct script access allowed'); ?>
<?php require 'header.tpl.php'; ?>
        <form method="POST">
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
            <input type="submit" name="Submit"/>
        </form>
<?php require 'footer.tpl.php'; ?>
