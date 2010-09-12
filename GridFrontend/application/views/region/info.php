<h3>Info</h3>

<?php if ( $this->scene_data ): ?>
<?php
	$x = $this->scene_data['MinPosition'][0] / 256;
	$y = $this->scene_data['MinPosition'][1] / 256;

	$my_url = get_base_url() . "index.php/region/info/" . $this->scene_data['SceneID'];
	if ( $this->simple_page ) {
		echo anchor($my_url, lang('sg_direct_link') );
	} else {
		generate_like_button($my_url);
		generate_tweet_button($my_url);
	}
?>
<br/>
<?php echo lang('sg_region_name') . " : " . $this->scene_data['Name']; ?> <br/>
<?php echo lang('sg_region_position') . " : " .  $x . "," . $y; ?> <br/>
<?php echo lang('sg_region_owner') . " : " ; render_user_link($this->owner_id); ?><br/>
<?php 
	if ( ! $this->simple_page ) {
		$image_url = $this->config->item('tile_host') . "map-1-$x-$y-objects.png";
		echo "<img src=\"$image_url\"></img>";
	}
?>
<?php if ( isset($this->center_map) && $this->center_map ): ?>
<script type="text/javascript">
	<?php
		echo "center_map($x + 0.5, $y + 0.5)";
	?>
</script>
<?php endif; ?>
<?php else: ?>
<?php echo lang('sg_region_unknown') ;?>
<?php endif; ?>
