<script src="{base_url}/static/javascript/smoothie.js" type="text/javascript" ></script>

Sim FPS : <span id="sim_fps"><?php echo $sim_details['sim_fps']; ?></span>

<canvas id="sim_fps_chart" width="400" height="100"></canvas>

<script type="text/javascript">

var sim_fps = undefined;
var sim_fps_line = undefined;

function update_chart(data, status, xhr)
{
    var timestamp = new Date().getTime();
    sim_fps_line.append(timestamp, data.sim_fps);
	console.log("what " + timestamp + " / " + data.sim_fps)
	$("#sim_fps").html(data.sim_fps.toString());
}

function do_thing()
{
    var count_settings = {
        dataType : "json",
        success : update_chart,
        url : "{site_url}/region/stats/{scene_id}/feed"
    };
    $.ajax(count_settings);
}

function init_charts()
{
	alert(sim_fps);
	if ( sim_fps == undefined ) {
		sim_fps = new SmoothieChart();

		sim_fps_line = new TimeSeries();
		sim_fps.addTimeSeries(sim_fps_line);

		sim_fps.streamTo($("#sim_fps_chart").get(0), 1000);
		setInterval(do_thing, 1000);
	}
}

$().ready(function() {
	init_charts();
});

</script>
