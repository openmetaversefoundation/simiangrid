<div style="height:100%; width:100%;">		
	<div id="search_form">
<?php
	echo form_input(array('name'=>'name', 'id'=>'user_name'));
	echo anchor("#", "Search", array('id' => 'search_button'));
?>
	</div>
	<div id="search_results"></div>
	<div id="user_info"></div>
<script>

    function load_via_post(url_suffix, destination, data)
    {
		return real_load_via_post(<?php echo "\"" . base_url() . "\"" ; ?> + url_suffix, destination, data);
	}
   
    function do_search()
    {
        var search_name = $("#user_name").val();
        var data = {
            'name' : search_name
        };
        load_via_post("index.php/user/search", "#search_results", data);
        return false;
    }

    $().ready(function() {
        $("#search_button").click(do_search);
    });
</script>