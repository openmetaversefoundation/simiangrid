<h3>Info</h3>

<div>

<table>
    <tr>
        <th>Name</th><td><?php echo $this->user_info['name']; ?></td>
    </tr>
<?php if ( $this->user_id == $this->my_uuid || $this->dx_auth->is_admin() ): ?>
    <tr>
        <th>Email</th><td><?php echo $this->user_info['email']; ?></td>
    </tr>
<?php endif; ?>
<?php if ( $this->dx_auth->is_admin() ): ?>
    <tr>
        <th>Last Location</th><td>
        <?php 
            if ( isset($this->last_scene) ) {
                echo $this->last_scene['Name']; 
            }
        ?>
        </td>
    </tr>
<?php endif; ?>
    <tr>
        <th>About</th><td>
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
        echo "<img src=\"" . base_url() . "index.php/user/profile_pic/" . $this->avatar_image . "\"/>";
    }
?>
        </td>
    </tr>
</table>

<div>