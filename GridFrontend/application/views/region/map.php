<html style="height: 100%; width: 100%;">
<head>

<?php render_stylesheet(); ?>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="{base_url}static/javascript/map.js"></script>
<script src="{base_url}/static/javascript/jquery.min.js" type="text/javascript" ></script>
<script src="{base_url}/static/javascript/jquery-ui.min.js" type="text/javascript" ></script>
<script src="{base_url}/static/javascript/helpers.js" type="text/javascript" ></script>

</head>
<body style="height: 100%; width: 100%;">

<div style="height: 100%; width: 100%;">
<div id="region_info"></div>
<div id="map_canvas" style="background-color: #1D475F; height: 100%; width: 100%;"></div>

<script>
	var div_loader = post_div_loader("<?php echo "$site_url/region/" ; ?>");

    $().ready(function() {
		$("#region_info").dialog({ autoOpen: false });
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