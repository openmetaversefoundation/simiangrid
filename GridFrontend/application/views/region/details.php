<?php if ( !isset($scene_data) || $scene_data == null ): ?>
Unknown
<?php else: ?>
<?php
	$x = $scene_data['MinPosition'][0] / 256;
	$y = $scene_data['MinPosition'][1] / 256;

?>
<br/>
<?php echo lang('sg_region_name') . " : " . $scene_data['Name']; ?> <br/>
<?php echo lang('sg_region_position') . " : " .  $x . "," . $y; ?> <br/>
<?php echo lang('sg_region_owner') . " : " ; render_user_link($owner_id); ?><br/>
<?php 
	$image_url = $this->config->item('tile_host') . "map-1-$x-$y-objects.png";
	echo "<img src=\"$image_url\"></img>";
?>
<?php endif; ?>
