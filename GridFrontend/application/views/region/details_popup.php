<h3><?php echo lang('sg_region_info'); ?></h3>

<?php if ( !isset($scene_data) || $scene_data == null ): ?>
Unknown
<?php else: ?>
<?php
	$x = $scene_data['MinPosition'][0] / 256;
	$y = $scene_data['MinPosition'][1] / 256;

	$my_url = "$site_url/region/view/" . $scene_data['SceneID'];
	echo anchor($my_url, lang('sg_direct_link') );
?>
<br/>
<?php echo lang('sg_region_name') . " : " . $scene_data['Name']; ?> <br/>
<?php echo lang('sg_region_position') . " : " .  $x . "," . $y; ?> <br/>
<?php echo lang('sg_region_owner') . " : " ; render_user_link($owner_id); ?><br/>

<?php if ( isset($center_map) && $center_map ): ?>
<script type="text/javascript">
	<?php
		echo "center_map($x + 0.5, $y + 0.5)";
	?>
</script>
<?php endif; ?>
<?php endif; ?>
