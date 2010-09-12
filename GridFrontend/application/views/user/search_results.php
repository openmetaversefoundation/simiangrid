<h2><?php echo count($this->user_list) . " " . lang('sg_results_found'); ?></h2>

<ul>
<?php
    foreach ( $this->user_list as $user ) {
        echo "<li>" . anchor(get_base_url() . "user/view/" . $user['id'], $user['name'], array('class'=>'search_result')) . "</li>";
    }
?>
</ul>