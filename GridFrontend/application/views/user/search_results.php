<h2><?php echo count($user_list) . " " . lang('sg_results_found'); ?></h2>

<ul>
<?php
    foreach ( $user_list as $user ) {
        echo "<li>" . anchor("$site_url/user/view/" . $user['id'], $user['name'], array('class'=>'search_result')) . "</li>";
    }
?>
</ul>