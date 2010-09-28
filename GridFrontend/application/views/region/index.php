<div id="region_info"></div>
<div id="search_popup">
	<table class="display" id="search_results">
		<thead>
			<tr>
				<th>Region Name</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>
<div style="margin-bottom: 10px; padding: 5px;" class="ui-widget ui-widget-content" >
<a href="#" id="search_button"><?php echo lang('sg_search'); ?></a>
<a href="{site_url}/map"><?php echo lang('sg_region_map'); ?></a>
</div>
<div id="map_canvas" style="width:700px; height:350px; background-color: #1D475F; "></div>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="{base_url}/static/javascript/map.js"></script>

<script>

	var div_loader = post_div_loader("<?php echo "$site_url/region/" ; ?>");

    $().ready(function() {
		$("#search_popup").dialog({
			autoOpen: false, 
			position: ['left', 'top']
		});
		$("#region_info").dialog({ 
			autoOpen: false, 
			position: ['right', 'center'],
			title: "<?php echo lang('sg_region_info'); ?>" 
		});
		$("#search_button").click(function(event) {
			$("#search_popup").dialog('open');
			return false;
		});
        <?php
            echo "initialize_map(\"" . $tile_host. "\", " 
                                   . $x . ", " 
                                   . $y . ", " 
                                   . $zoom . ", load_region_by_pos);"
        ?>
		$("#search_results").dataTable({
			"bProcessing": true,
			"bServerSide": true,
			"sAjaxSource": "{site_url}/region/search",
			"bSort": false,
			"bLengthChange": false,
			"bJQueryUI": true
		});
    });
</script>
