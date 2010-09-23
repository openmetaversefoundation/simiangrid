<h2><?php echo count($scene_list) . " " . lang('sg_results_found'); ?></h2>

<ul>
<?php
    foreach ( $scene_list as $scene ) {
        echo "<li>" . anchor('region/view/' . $scene['id'], $scene['name'], array('class'=>'search_result','onclick' => 'load_search_result(\'' . $scene['id'] . '\', ' . $scene['x'] . ', ' . $scene['y'] . '); return false;')) . "</li>";
    }
?>
</ul>