<h2><?php echo lang('sg_user_view'); ?>s</h2>

<?php
	$my_url = "$site_url/user/view/" . $uuid;
	generate_like_button($my_url);
	generate_tweet_button($my_url);
?>
<div id="user_menu">
     <ul>
         <li><?php echo anchor("$site_url/user/profile/" . $uuid, lang('sg_user_profile') ); ?></li>
<?php if ( $this->sg_auth->is_logged_in() ): ?>
    <?php if ( $uuid == $my_uuid || $this->sg_auth->is_admin() ): ?>
         <li><?php echo anchor("$site_url/user/identities/" . $uuid, lang('sg_user_identities')); ?></li>
        <li><?php echo anchor("$site_url/user/actions/" . $uuid, lang('sg_actions')); ?></li>
    <?php endif ; ?>
<?php endif ; ?>
     </ul>
</div>

<script>
    function handle_tab(event, ui) {
<?php if ( $this->config->item('enable_tooltips') ): ?>
        scan_tooltips("<?php echo site_url('about/tooltip/'); ?>");
<?php endif; ?>
    }

    var menu = $( "#user_menu" ).tabs({ 
        ajaxOptions: { async: false },
        load: handle_tab
    });
	var tab = "<?php echo $tab; ?>";
	if ( tab == 'identities' ) {
		menu.tabs('select', 1);
	} else if ( tab == 'actions' ) {
		menu.tabs('select', 2);
	}
</script>
