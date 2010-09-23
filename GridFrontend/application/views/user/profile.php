<div>

<table>
    <tr>
        <th><Name><?php echo lang('sg_name'); ?></th><td><?php echo $user_info['name']; ?></td>
    </tr>
<?php if ( $user_id == $my_uuid || $this->sg_auth->is_admin() ): ?>
    <tr>
        <th><?php echo lang('sg_email'); ?></th><td><?php echo $user_info['email']; ?></td>
    </tr>
<?php endif; ?>
<?php if ( $this->sg_auth->is_admin() ): ?>
    <tr>
        <th><?php echo lang('sg_user_last_location'); ?></th><td>
        <?php 
            if ( isset($last_scene) ) {
                render_region_link($last_scene['SceneID']); 
            }
        ?>
        </td>
    </tr>
<?php endif; ?>
    <tr>
        <th><?php echo lang('sg_user_about'); ?></th><td>
        <?php
            if ( isset($user_info['about']) ) {
                echo $user_info['about']; 
            }
        ?>
        </td>
    </tr>
    <tr>
        <td colspan="2">
<?php 
    if ( isset($avatar_image) ) {
        echo "<img src=\"" . "$site_url/user/profile_pic/" . $avatar_image . "\"/>";
    }
?>
        </td>
    </tr>
</table>

<div>
