// The relative or absolute URL of your SimianGrid map tile folder
var TILE_HOST = "/Grid/map/";

var CLICK_HANDLER = sample_handler;

// The maximum width/height of the grid in regions (must be a power of two)
var GRID_WIDTH_IN_REGIONS = 1048576;
// Map from a GRID_WIDTH_IN_REGIONS x GRID_WIDTH_IN_REGIONS square to Lat/Long (0, 0),(-90, 90) 
var SCALE_FACTOR = 90.0 / GRID_WIDTH_IN_REGIONS;

// Override the default Mercator projection with Euclidean projection
// (insert oblig. Flatland reference here)
function EuclideanProjection() {
};

EuclideanProjection.prototype.fromLatLngToPoint = function(latLng, opt_point) {
	var point = opt_point || new google.maps.Point(0, 0);
	
    point.x = latLng.lng() / SCALE_FACTOR;
    point.y = latLng.lat() / SCALE_FACTOR;
    
	return point;
};

EuclideanProjection.prototype.fromPointToLatLng = function(point) {
    var lng = point.x * SCALE_FACTOR;
    var lat = point.y * SCALE_FACTOR;
    
	return new google.maps.LatLng(lat, lng, true);
};

// Configure options for the map
var imageMapOptions = {
	getTileUrl: function(coord, zoom) {
		var gridZoom = 8 - zoom;
	    var regions_per_tile_edge = Math.pow(2, gridZoom - 1);
		
	    // Convert from tile coordinates to world coordinates
		var x = coord.x * regions_per_tile_edge;
		var y = coord.y * regions_per_tile_edge;
		
		// Flip the y axis
	    y = -y;
		
	    // Orient the y axis from the bottom-left corner instead of the top-left
	    y -= regions_per_tile_edge;
	    
	    // Adjust x/y to the lower-left tile for this zoom level
	    x -= x % regions_per_tile_edge;
	    y -= y % regions_per_tile_edge;  
	    
	    return TILE_HOST + "map-" + gridZoom + "-" + x + "-" + y + "-objects.png";
	},
	tileSize: new google.maps.Size(256, 256),
	minZoom: 0,
	maxZoom: 7,
	isPng: true
};

var imageMapType = new google.maps.ImageMapType(imageMapOptions);
imageMapType.projection = new EuclideanProjection();
var map;

var scale = 90.0 / 1048576;
function convert_grid_pos(x, y)
{
    var real_x = ( x * 2 ) * scale;
    var real_y = ( y * 2 ) * scale;
    return new google.maps.LatLng(-real_x, real_y);
}

function handle_click(event)
{
    var x = event.latLng.b;
    var y = event.latLng.c;
    var real_x = 0 - Math.ceil(( x / scale) / 2);
    var real_y = Math.floor(( y / scale) / 2);
    CLICK_HANDLER(real_x, real_y);
}

function sample_handler(x, y)
{
    alert('X : ' + x + " Y : " + y);
}

function initialize_map(tile_host, x, y, zoom, click_handler) {
    var mapOptions = {
        mapTypeControl: false,
        backgroundColor: "#1D475F",
        center: convert_grid_pos(x, y),
        zoom: zoom
    };
    TILE_HOST = tile_host;
    if ( click_handler != undefined ) {
        CLICK_HANDLER = click_handler;
    }
	map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
	// Now attach the grid map type to the map's registry
	map.mapTypes.set('grid', imageMapType);
	// We can now set the map to use the 'grid' map type
	map.setMapTypeId('grid');
	
	google.maps.event.addListener(map, 'click', handle_click);
}

function center_map(x, y, zoom)
{
	if ( zoom == undefined ) {
		zoom = 7;
	}
	
	map.panTo(convert_grid_pos(x, y));
	map.setZoom(zoom);
}