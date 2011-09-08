<?php if ( ! defined('BASEPATH') or !defined('SIMIAN_INSTALLER') ) exit('No direct script access allowed'); ?>

<?php require 'header.tpl.php'; ?>
<h2>PHP Version</h2>
<div id="dependency_list">
    <table>
        <tr>
            <td>Required</td>
            <td><?php echo $result['php_version']['required'] ?></td>
        </tr>
        <tr>
            <td>Installed</td>
            <td>
            <?php 
                if ( $result['php_version']['check'] ) {
                    echo "<font color=\"green\">" . $result['php_version']['current'] . "</font>";
                } else {
                    echo "<font color=\"red\">" . $result['php_version']['current'] . "</font>";
                }
            ?>
            </td>
        </tr>
    </table>
</div>

<h2>Required Modules</h2>           
<div id="dependency_list">
    <table>
		<tr>
        	<th>Package</th>
            <th>Installed?</th>
		</tr>
        <?php
            foreach ( $result['modules']['required'] as $module => $enabled ) {
                if ( $enabled ) {
                    $enabled_string = "<font color=\"green\">Yes</font>";
                } else {
                    $enabled_string = "<font color=\"red\">NO</font>";
                }
                echo "
            <tr>
                <td>$module</td><td>$enabled_string</td>
            </tr>";
            }
        ?>
    </table>
</div>

<h2>Optional Modules (if applicable)</h2>
<div id="dependency_list">
    <table>
        <tr>
            <th>Package</th>
            <th>Installed?</th>
        </tr>
        <?php
            foreach ( $result['modules']['optional'] as $module => $enabled ) {
                if ( $enabled ) {
                    $enabled_string = "<font color=\"green\">Yes</font>";
                } else {
                    $enabled_string = "<font color=\"red\">NO</font>";
                }
                echo "
            <tr>
                <td>$module</td><td>$enabled_string</td>
            </tr>";
            }
        ?>
    </table>
</div>

After installing PHP modules you will likely need to restart Apache.

<ul class="progress_buttons">
    <li><a href="?next"><span>Continue</span></a></li>
<?php if ( ! ( isset($result['error']) && $result['error'] == TRUE )  ) {
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

