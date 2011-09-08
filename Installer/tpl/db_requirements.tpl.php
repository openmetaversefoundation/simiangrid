<?php if ( ! defined('BASEPATH') or !defined('SIMIAN_INSTALLER') ) exit('No direct script access allowed'); ?>
<?php require 'header.tpl.php'; ?>
		<h2>Connection Information</h2>
		<table>
			<tr>
				<td>Host</td><td><?php echo $result['db_config']['host']; ?></td>
			</tr>
			<tr>
				<td>User</td><td><?php echo $result['db_config']['user']; ?></td>
			</tr>
			<tr>
				<td>Password</td><td><?php 
					if ( count($result['db_config']['password']) > 0 ) {
						echo "****";
					} ?></td>
			</tr>
			<tr>
				<td>DB</td><td><?php echo $result['db_config']['db'] ?></td>
			</tr>
		</table>
       <h2>Mysql Version</h2>
        <table>
            <tr>
                <td>Required</td><td><?php echo $result['mysql_check']['required'] ?></td>
            </tr>
            <tr>
                <td>Installed</td><td>
                <?php 
                    if ( $result['mysql_check']['check'] ) {
                        echo "<font color=\"green\">" . $result['mysql_check']['current'] . "</font>";
                    } else {
						if ( $result['mysql_check']['connect'] ) {
							$db_version = $result['mysql_check']['current'];
						} else {
							$db_version = "Check";
						}
                        echo "<font color=\"red\">" . $db_version . "</font>";
                    }
                ?>
                </td>
            </tr>
        </table>
		<h2>DB Check</h2>
		DB check
		<?php
			if ( $result['mysql_check']['db_check'] ) {
				echo "<font color=\"green\">Passed</font>";
			} else {
				echo "<font color=\"red\">Check</font>";
			}
		?>

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

