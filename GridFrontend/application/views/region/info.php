<h3>Info</h3>

<?php if ( $this->scene_data ): ?>
Name : <?php echo $this->scene_data['Name']; ?> <br/>
Mapping : <?php echo $this->scene_data['MinPosition'][0] . "," . $this->scene_data['MinPosition'][1] . " - " . $this->scene_data['MaxPosition'][0] . "," . $this->scene_data['MaxPosition'][1]; ?> <br/>
Position : <?php echo $this->scene_data['MinPosition'][0] / 256 . "," . $this->scene_data['MinPosition'][1] / 256; ?> <br/>
Owner : <?php echo $this->owner_name ?><br/>
<?php else: ?>
Unknown Region
<?php endif; ?>