<?php if ( ! defined('BASEPATH') or !defined('SIMIAN_INSTALLER') ) exit('No direct script access allowed'); ?>
<?php require 'header.tpl.php'; ?>
        <form method="POST">
            <table>
                <tr>
                    <td>DB User</td><td><input type="text" name="user" value="<?php echo $result['db_config']['user'] ?>"/></td>
                </tr><tr>
                    <td>DB Password</td><td><input type="text" name="password" value="<?php 
						if ( count($result['db_config']['password']) > 0 ) {
							echo "****";
						} ?>"/></td>
                </tr><tr>
                    <td>DB Schema</td><td><input type="text" name="schema" value="<?php echo $result['db_config']['db'] ?>"/></td>
                </tr><tr>
                    <td>DB Host</td><td><input type="text" name="host" value="<?php echo $result['db_config']['host'] ?>"/></td>
                </tr>
            </table>
            <input type="hidden" name="next" />
            <input type="submit" name="Submit"/>
        </form>
<?php require 'footer.tpl.php'; ?>
