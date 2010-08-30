<h3>Info</h3>

<?php if ( $this->scene_data ): ?>
Name : <?php echo $this->scene_data['Name']; ?> <br/>
Position : <?php echo $this->scene_data['MinPosition'][0] / 256 . "," . $this->scene_data['MinPosition'][1] / 256; ?> <br/>
Owner : <?php echo $this->owner_name ?><br/>
<?php if ( isset($this->center_map) && $this->center_map ): ?>
<script type="text/javascript">
	<?php
		$x = ( $this->scene_data['MinPosition'][0] / 256 ) + 0.5;
		$y = ( $this->scene_data['MinPosition'][1] / 256 ) + 0.5;
		echo "center_map($x, $y)";
	?>
</script>
<?php endif; ?>
<?php else: ?>
Unknown Region
<?php endif; ?>