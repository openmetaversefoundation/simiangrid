<h2><?php echo count($this->user_list); ?> Results</h2>

<ul>
<?php
    foreach ( $this->user_list as $user ) {
        echo "<li>" . anchor(base_url() . "index.php/user/view/" . $user['id'], $user['name'], array('class'=>'search_result')) . "</li>";
    }
?>
</ul>