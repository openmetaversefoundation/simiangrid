<h2>User View</h2>

<?php
	$my_url = get_base_url() . "user/view/" . $this->uuid;
	generate_like_button($my_url);
	generate_tweet_button($my_url);
?>
<div id="user_menu">
     <ul>
         <li><?php echo anchor(get_base_url() . 'index.php/user/profile/' . $this->uuid, lang('sg_user_profile') ); ?></li>
<?php if ( $this->sg_auth->is_logged_in() ): ?>
    <?php if ( $this->uuid == $this->my_uuid || $this->sg_auth->is_admin() ): ?>
         <li><?php echo anchor(get_base_url() . 'index.php/user/identities/' . $this->uuid, lang('sg_user_identities')); ?></li>
        <li><?php echo anchor(get_base_url() . 'index.php/user/actions/' . $this->uuid, lang('sg_actions')); ?></li>
    <?php endif ; ?>
<?php endif ; ?>
     </ul>
</div>

<script>
    $( "#user_menu" ).tabs({ ajaxOptions: { async: false } });
</script>
