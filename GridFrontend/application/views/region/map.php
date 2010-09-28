<html style="height: 100%; width: 100%;">
<head>

<?php render_stylesheet(); ?>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="{base_url}static/javascript/map.js"></script>
<script src="{base_url}/static/javascript/jquery.min.js" type="text/javascript" ></script>
<script src="{base_url}/static/javascript/jquery-ui.min.js" type="text/javascript" ></script>
<script src="{base_url}/static/javascript/helpers.js" type="text/javascript" ></script>
<script src="{base_url}/static/javascript/jquery.dataTables.js" type="text/javascript" ></script>

</head>
<body style="height: 90%; width: 100%;">
<div style="padding: 10px; margin: 10px;" class="ui-widget ui-widget-content">
	<a href="#" id="search_button"><?php echo lang('sg_search'); ?></a>
	<a href="{site_url}/region"><?php echo lang('sg_menu_regions'); ?></a>
</div>
<div style="width: 100%;">
<div id="region_info"></div>
<div id="search_popup">
	<table class="display" id="search_results">
		<thead>
			<tr>
				<th><?php echo lang('sg_region_name'); ?></th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>
<div id="map_canvas" style="background-color: #1D475F; height: 100%; width: 100%;"></div>

<script>
	var div_loader = post_div_loader("<?php echo "$site_url/region/" ; ?>");

    $().ready(function() {
		$("#region_info").dialog({ 
			autoOpen: false, 
			position: ['right', 'center'],
			title: "<?php echo lang('sg_region_info'); ?>" 
		});
		$("#search_popup").dialog({
			autoOpen: false, 
			position: ['left', 'top']
		});
		$("#search_button").click(function(event) {
			$("#search_popup").dialog('open');
			return false;
		});
		$("#search_results").dataTable({
			"bProcessing": true,
			"bServerSide": true,
			"sAjaxSource": "{site_url}/region/search",
			"bSort": false,
			"bLengthChange": false,
			"bJQueryUI": true
		});
        <?php
            echo "initialize_map(\"" . $tile_host. "\", " 
                                   . $x . ", " 
                                   . $y . ", " 
                                   . $zoom . ", load_region_by_pos);"
        ?>
    });
</script>
</div>
</body>
</html>