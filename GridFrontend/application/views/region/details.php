<?php if ( !isset($scene_data) || $scene_data == null ): ?>
Unknown
<?php else: ?>
<?php
    $x = $scene_data['MinPosition'][0] / 256;
    $y = $scene_data['MinPosition'][1] / 256;

?>
<br/>
<table>
    <tr>
        <th><?php echo lang('sg_region_name'); ?></th>
        <td><?php echo $scene_data['Name']; ?></td>
    </tr><tr>
        <th><?php echo lang('sg_region_position'); ?></th>
        <td><?php echo $x . "," . $y; ?></td>
    </tr><tr>
        <th><?php echo lang('sg_region_status'); ?></th>
        <td><?php echo $online; ?></td>
    </tr>
<?php if ( ! isset($scene_data['ExtraData']['HyperGrid']) && $scene_data['Enabled'] && ( $this->sg_auth->get_uuid() == $owner_id || $this->sg_auth->is_admin() ) ): ?>
    <tr>
        <th><?php echo lang('sg_region_version'); ?></th>
        <td>{region_version}</td>
    </tr><tr>
         <th><?php echo lang('sg_region_uptime'); ?></th>
        <td>{region_uptime}</td>
    </tr>
<?php else: ?>
    <tr>
        <th colspan="2">HyperGrid Region</th>
    </tr>
<?php endif ; ?>
</table>
<?php 
    $image_url = $this->config->item('tile_host') . "map-1-$x-$y-objects.png";
    echo "<img src=\"$image_url\"></img>";
?>
<?php endif; ?>
