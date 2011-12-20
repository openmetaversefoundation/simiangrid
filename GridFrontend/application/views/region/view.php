<?php
    $my_url = "$site_url/region/view/$uuid";
    echo generate_like_button($my_url);
    echo generate_tweet_button($my_url);
?>

<div id="region_menu">
     <ul>
         <li><?php echo anchor("$site_url/region/details/" . $uuid, lang('sg_region_details') ); ?></li>
<?php if ( ! isset($scene_data['ExtraData']['HyperGrid']) ): ?>
<?php if ( $this->sg_auth->get_uuid() == $owner_id || $this->sg_auth->is_admin() ): ?>
        <li><?php echo anchor("$site_url/region/stats/" . $uuid, lang('sg_region_stats') ); ?></li>
<?php endif; ?>
<?php endif; ?>
<?php if ( $this->sg_auth->is_admin() ): ?>
	<li><?php echo anchor("$site_url/region/admin_actions/" . $uuid, lang('sg_region_admin') ); ?></li>
<?php endif; ?>
     </ul>
</div>

<script>
    function handle_tab(event, ui) {
<?php if ( $this->config->item('enable_tooltips') ): ?>
        scan_tooltips("<?php echo site_url('about/tooltip/'); ?>");
<?php endif; ?>
    }

    var menu = $( "#region_menu" ).tabs({ 
        ajaxOptions: { async: false },
        load: handle_tab
    });
    var tab = "{tab}";
    if ( tab == 'stats' ) {
        menu.tabs('select', 1);
    } else if ( tab == 'admin_actions' ) {
		menu.tabs('select', 2);
	}
</script>
