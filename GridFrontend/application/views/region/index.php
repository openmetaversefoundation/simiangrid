<div style="height:100%; width:100%;">
	<div style="float: left; width:320px;">
		<div id="region_search">

			<div id="search_form">

<?php
	echo form_input(array('name'=>'name', 'id'=>'region_name'));
	echo anchor("#", "Search", array('id' => 'search_button'));
?>

			</div>
			<div id="search_results">

			</div>
		</div>

		<div id="region_info"></div>
	</div>

	<div id="map_canvas" style="width:400px; height:400px; background-color: #1D475F; "></div>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="static/javascript/map.js"></script>

<script>

    function load_via_post(url_suffix, destination, data)
    {
        <?php
            echo "var url = \"" . base_url() . "\" + url_suffix;";
        ?>;
        console.log("loading " + url + " into " + destination);
        var request = {
            url : url,
            dataType : 'html',
            type : 'POST',
            success : handle_load(destination),
            error : handle_load_error(destination)
        };
        if ( data != undefined ) {
            request.data = data;
        }
        $.ajax(request);
    }
    
    function handle_load_error(destination)
    {
        return function(data, textStatus, XMLHttpRequest) {
            $(destination).html("PROBLEMS " + textStatus);
        }
    }
    
    function test_success(data, textStatus, XMLHttpRequest)
    {
        console.log("DDD " + data + " / " +  textStatus);
    }
    
    function handle_load(destination)
    {
        return function(data, textStatus, XMLHttpRequest) {
            console.log("injecting into " + destination + " - " + data );
            $(destination).html(data);
        }
    }
    
    function load_region_by_pos(x, y)
    {
        var data = {
            'x' : x,
            'y' : y
        };
        load_via_post("index.php/region/view_coord", "#region_info", data);
    }

    function load_region_by_id(scene_id)
    {
        load_via_post("index.php/region/info/" + scene_id, "#region_info");
    }
   
    function do_search()
    {
        var search_name = $("#region_name").val();
        var data = {
            'name' : search_name
        };
        load_via_post("index.php/region/search", "#search_results", data);
        return false;
    }
    
    var load_error_dialog;
    $().ready(function() {
        $("#search_button").click(do_search);
        $( "#region_menu" ).tabs({ ajaxOptions: { async: false } });
        <?php
            echo "initialize_map(\"" . $this->tile_host. "\", " 
                                   . $this->x . ", " 
                                   . $this->y . ", " 
                                   . $this->zoom . ", load_region_by_pos);"
        ?>
    });
</script>
