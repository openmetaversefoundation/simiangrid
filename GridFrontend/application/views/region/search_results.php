<h2><?php echo count($this->scene_list); ?> Results</h2>

<ul>
<?php
    foreach ( $this->scene_list as $scene ) {
        echo "<li>" . anchor('region/info/' . $scene['id'], $scene['name'], array('class'=>'search_result','onclick' => 'load_search_result(\'' . $scene['id'] . '\', ' . $scene['x'] . ', ' . $scene['y'] . '); return false;')) . "</li>";
    }
?>
</ul>