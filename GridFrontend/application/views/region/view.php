<h3><?php echo lang('sg_region_info'); ?></h3>

<?php
    $my_url = "$site_url/region/view/$uuid";
    generate_like_button($my_url);
    generate_tweet_button($my_url);
?>

<div id="region_menu">
     <ul>
         <li><?php echo anchor("$site_url/region/details/" . $uuid, lang('sg_region_details') ); ?></li>
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
	if ( tab == 'actions' ) {
		menu.tabs('select', 1);
	}
</script>
