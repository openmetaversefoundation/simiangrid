{
	function load_via_post(url, destination, data)
	{
		var div_loader = post_div_loader();
		div_loader(url, destination, data);
	}

	function post_div_loader(url_prefix)
	{
		return function(url, destination, data) {
			if ( url_prefix == undefined ) {
				var full_url = url;
			} else {
				var full_url = url_prefix + url;
			}
	        var request = {
	            url : full_url,
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

    function enable_tooltip(thing, url_base)
    {
        var rel = thing.attr('title');
        if ( thing.data['qtip'] == undefined ) {
            thing.qtip({
                content : {
                    url: url_base + "/" + rel,
                    text: '...'
                },
                show : {
                    solo : true,
                    effect : {
                        type : 'slide'
                    },
                    when : {
                        event: 'mouseover'
                    },
                    delay : 1000
                },
                hide: {
                    effect : {
                        type : 'slide'
                    },
                    when : {
                        event : 'mouseout'
                    }
                }
            });
            thing.attr('title', '');
        }
    }

    function scan_tooltips(url_base) {
        $("[title!='']").each(function(i) {
            enable_tooltip($(this), url_base);
        });		
    }

	function select_style(url, style) {
        $.ajax({
            url : url,
            dataType : 'text',
            type : 'POST',
			data : {
				style : style
			}
        });
		$("link[media='screen'][id='main']").attr("href", "static/styles/" + style + "/style.css");
		$("link[media='screen'][id='jquery_ui']").attr("href", "static/styles/" + style + "/jquery-ui.css");
		$("link[media='screen'][id='jquery_qtip']").attr("href", "static/styles/" + style + "/jquery.qtip.css");
	}
}