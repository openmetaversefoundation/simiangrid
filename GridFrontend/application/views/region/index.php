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
<a href="#" id="search_button">Search</a>
</div>
<div id="map_canvas" style="width:700px; height:350px; background-color: #1D475F; "></div>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="{base_url}/static/javascript/map.js"></script>

<script>

    function load_region_by_pos(x, y)
    {
        var data = {
            'x' : x,
            'y' : y
        };
        load_via_post("{site_url}/region/view_coord", "#region_info", data);
		$("#search_results").dialog('close');
		$("#region_info").dialog('open');
    }

    function load_search_result(scene_id)
    {
		var data = {
			'is_search' : true
		};
        load_via_post("{site_url}/region/details/" + scene_id + "/inline", "#region_info", data);
		$("#region_info").dialog('open');
    }

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
