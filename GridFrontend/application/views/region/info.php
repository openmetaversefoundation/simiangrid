<h3>Info</h3>

<?php if ( $this->scene_data ): ?>
<?php
	$x = $this->scene_data['MinPosition'][0] / 256;
	$y = $this->scene_data['MinPosition'][1] / 256;

	if ( $this->simple_page ) {
		echo anchor(base_url() . "index.php/region/info/" . $this->scene_data['SceneID'], "Direct Link");
	} else {
		generateLikeButton(base_url() . "index.php/region/info/" . $this->scene_data['SceneID']);
	}
?>
<br/>
Name : <?php echo $this->scene_data['Name']; ?> <br/>
Position : <?php echo $x . "," . $y; ?> <br/>
Owner : <?php echo $this->owner_name ?><br/>
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
Unknown Region
<?php endif; ?>