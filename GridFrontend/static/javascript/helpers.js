{
	function real_load_via_post(url, destination, data) 
	{
        var request = {
            url : url,
            dataType : 'html',
            type : 'POST',
            success : handle_load(destination),
            error : handle_load_error(destination)
        };
        if ( data != undefined ) {
            request.data = data;
        } else {
			data = {}
		}
        $.ajax(request);
    }
    
    function handle_load_error(destination)
    {
        return function(data, textStatus, XMLHttpRequest) {
            $(destination).html("PROBLEMS " + textStatus);
			$(destination).dialog('open');
        }
    }
    
    function handle_load(destination)
    {
        return function(data, textStatus, XMLHttpRequest) {
            $(destination).html(data);
        }
    }
}