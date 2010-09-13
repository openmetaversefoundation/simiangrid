/*!
* jquery.qtip.ajax. The jQuery tooltip plugin - AJAX plugin
*
* Allows you to use remote content for your tooltips via AJAX functionality
*
* Copyright (c) 2009 Craig Thompson
* http://craigsworks.com
*
* Licensed under MIT
* http://www.opensource.org/licenses/mit-license.php
*
* Launch: August 2009
* Version: UNSTABLE REVISION CODE! Visit http://craigsworks.com/projects/qtip/ for stable code
*
* FOR STABLE VERSIONS VISIT: http://craigsworks.com/projects/qtip/download/
*/

"use strict"; // Enable ECMAScript "strict" operation for this function. See more: http://ejohn.org/blog/ecmascript-5-strict-mode-json-and-more/
/*jslint onevar: true, browser: true, forin: true, undef: true, nomen: true, bitwise: true, regexp: true, newcap: true, maxerr: 300 */
/*global window: false, jQuery: false */
(function($)
{
	// Munge the primitives - Paul Irish
	var TRUE = true,
		FALSE = false,
		NULL = null,
		noop = function(){};

	// Check qTip library is present
	if(!$.fn.qtip) {
		if(window.console){ window.console.error('This plugin requires the qTip library.',''); }
		return FALSE;
	}

	function Ajax(qTip)
	{
		var self = this;

		$.extend(self, {

			init: function()
			{
				// Grab ajax options
				var ajax = qTip.options.content.ajax;

				// Load the remote content
				self.load(ajax, TRUE);

				// Bind show event
				qTip.elements.tooltip.bind('tooltipshow.ajax', function() {
						// Update content if content.ajax.once is FALSE and the tooltip is rendered
						if(ajax.once === FALSE && qTip.rendered === TRUE){ self.load(ajax, TRUE); }
					});
			},

			load: function(ajax)
			{
				// Define success and error handlers
				function successHandler(content, status)
				{
					// Call user-defined success handler if present
					if($.isFunction(ajax.success)) {
						content = ajax.success(content, status);
						if(content === FALSE){ return; }
					}

					// Update content
					qTip.set('content.text', content);
				}
				function errorHandler(xhr, status, error)
				{
					var content = status || error;

					// Call user-defined success handler if present
					if($.isFunction(ajax.error)) {
						content = ajax.error(xhr, status, error);
						if(content === FALSE){ return; }
					}

					// Update tooltip content to indicate error
					qTip.updateContent(content);
				}

				// Setup $.ajax option object and process the request
				$.ajax( $.extend(TRUE, {}, ajax, { success: successHandler, error: errorHandler }) );

				return self;
			},

			destroy: function()
			{
				// Remove bound events
				qTip.elements.tooltip.unbind('tooltiprender.ajax tooltipshow.ajax');
			}
		});

		self.init();
	}

	$.fn.qtip.plugins.ajax = function(qTip)
	{
		var api = qTip.plugins.ajax,
			opts = qTip.options.content;

		// Make sure the qTip uses the $.ajax functionality
		if(opts.ajax && opts.ajax.url) {
			// An API is already present,
			if(api) {
				return api;
			}
			// No API was found, create new instance
			else {
				qTip.plugins.ajax = new Ajax(qTip);
				return qTip.plugins.ajax;
			}
		}
	};

	$.fn.qtip.plugins.ajax.initialize = 'render';

	// Setup plugin checks
	$.fn.qtip.plugins.ajax.checks = {
		'^content.ajax': function(){ this.plugins.ajax.load(this.options.content.ajax); }
	};
	$.fn.qtip.plugins.ajax.sanitize = function(opts)
	{
		// Parse options into correct syntax
		if(opts.content !== undefined) {
			if(opts.content.ajax !== undefined) {
				if(typeof opts.content.ajax !== 'object'){ opts.content.ajax = { url: opts.content.ajax }; }
				if(opts.content.text === FALSE){ opts.content.text = 'Loading...'; }
			} else {
				opts.content.ajax = { url: opts.content.ajax };
			}
			if(opts.content.url){ opts.content.ajax.url = opts.content.url; delete opts.content.url; }
			if(opts.content.data){ opts.content.ajax.data = opts.content.data; delete opts.content.data; }
			if(opts.content.method){ opts.content.ajax.type = opts.content.method; delete opts.content.method; }
		}
	};
}(jQuery));