<h3><?php echo lang('sg_user_profile'); ?> </h3>

<div>

<table>
    <tr>
        <th><Name><?php echo lang('sg_name'); ?></th><td><?php echo $this->user_info['name']; ?></td>
    </tr>
<?php if ( $this->user_id == $this->my_uuid || $this->sg_auth->is_admin() ): ?>
    <tr>
        <th><?php echo lang('sg_email'); ?></th><td><?php echo $this->user_info['email']; ?></td>
    </tr>
<?php endif; ?>
<?php if ( $this->sg_auth->is_admin() ): ?>
    <tr>
        <th><?php echo lang('sg_user_last_location'); ?></th><td>
        <?php 
            if ( isset($this->last_scene) ) {
                render_region_link($this->last_scene['SceneID']); 
            }
        ?>
        </td>
    </tr>
<?php endif; ?>
    <tr>
        <th><?php echo lang('sg_user_about'); ?></th><td>
        <?php
            if ( isset($this->user_info['about']) ) {
                echo $this->user_info['about']; 
            }
        ?>
        </td>
    </tr>
    <tr>
        <td colspan="2">
<?php 
    if ( isset($this->avatar_image) ) {
        echo "<img src=\"" . get_base_url() . "index.php/user/profile_pic/" . $this->avatar_image . "\"/>";
    }
?>
        </td>
    </tr>
</table>

<div>
