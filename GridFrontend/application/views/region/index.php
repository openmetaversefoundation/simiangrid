<div style="height:100%; width:100%;">
	<div id="search_results"></div>
	<div id="region_info"></div>
		
	<div id="search_form">

<?php
	echo form_input(array('name'=>'name', 'id'=>'region_name'));
	echo anchor("#", lang('sg_search'), array('id' => 'search_button'));
?>
	</div>

	<div id="map_canvas" style="width:720px; height:400px; background-color: #1D475F; "></div>

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
        load_via_post("{site_url}/region/view/" + scene_id + "/inline", "#region_info", data);
		$("#region_info").dialog('open');
    }
   
    function do_search()
    {
        var search_name = $("#region_name").val();
        var data = {
            'name' : search_name
        };
        load_via_post("{site_url}/region/search", "#search_results", data, false);
		$("#search_results").dialog('open');
        return false;
    }
    
    var load_error_dialog;
    $().ready(function() {
		$("#search_results").dialog({ autoOpen: false, position: ['left', 'center'] });
		$("#region_info").dialog({ autoOpen: false, position: ['right', 'center'] });
        $("#search_button").click(do_search);
		$("#region_name").change(do_search);
        <?php
            echo "initialize_map(\"" . $tile_host. "\", " 
                                   . $x . ", " 
                                   . $y . ", " 
                                   . $zoom . ", load_region_by_pos);"
        ?>
    });
</script>
