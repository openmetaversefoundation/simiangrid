/*!
* jquery.qtip. The jQuery tooltip plugin
*
* Copyright (c) 2009 Craig Thompson
* http://craigsworks.com
*
* Licensed under MIT
* http://www.opensource.org/licenses/mit-license.php
*
* Launch: February 2009
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

	// Option object sanitizer
	function sanitizeOptions(opts)
	{
		if(!opts) { return FALSE; }

		try {
			if('metadata' in opts && 'object' !== typeof opts.metadata) {
				opts.metadata = {
					type: opts.metadata
				};
			}

			if('content' in opts) {
				if('object' !== typeof opts.content || opts.content.jquery) {
					opts.content = {
						text: opts.content
					};
				}

				var noContent = opts.content.text || FALSE;
				if(noContent.length < 1 || (!noContent && !noContent.attr) || (noContent.jquery && noContent.is(':empty'))) {
					opts.content.text = FALSE;
				}

				if('title' in opts.content && 'object' !== typeof opts.content.title) {
					opts.content.title = {
						text: opts.content.title
					};
				}
			}

			if('position' in opts) {
				if('object' !== typeof opts.position) {
					opts.position = {
						my: opts.position,
						at: opts.position
					};
				}

				if('object' !== typeof opts.position.adjust) {
					opts.position.adjust = {};
				}

				if(String(opts.position.adjust.screen).search(/flip|fit/) < 0) {
					opts.position.adjust.screen = FALSE;
				}
			}

			if('show' in opts) {
				if('object' !== typeof opts.show) {
					opts.show = {
						event: opts.show
					};
				}

				if('object' !== typeof opts.show) {
					if(opts.show.jquery) {
						opts.show = { target: opts.show };
					}
					else {
						opts.show = { event: opts.show };
					}
				}
			}

			if('hide' in opts) {
				if('object' !== typeof opts.hide) {
					if(opts.hide.jquery) {
						opts.hide = { target: opts.hide };
					}
					else {
						opts.hide = { event: opts.hide };
					}
				}
			}

			if('style' in opts && 'object' !== typeof opts.style) {
				opts.style = {
					classes: opts.style
				};
			}
		}
		catch (e) {}

		// Sanitize plugin options
		$.each($.fn.qtip.plugins, function() {
			if(this.sanitize) { this.sanitize(opts); }
		});
	}

	/*
	* Core plugin implementation
	*/
	function QTip(target, options, id)
	{
		// Declare this reference
		var self = this;

		// Setup class attributes
		self.id = id;
		self.rendered = FALSE;
		self.elements = { target: target };
		self.cache = { title: '', event: {}, disabled: FALSE };
		self.timers = {};
		self.options = options;
		self.plugins = {};

		/*
		* Private core functions
		*/
		function convertNotation(notation)
		{
			var actual, option, i;

			// Split notation into array
			actual = notation.split('.');

			// Locate required option
			option = options[ actual[0] ];
			for(i = 1; i < actual.length; i+=1) {
				if(typeof option[ actual[i] ] === 'object' && !option[ actual[i] ].jquery) {
					option = option[ actual[i] ];
				}
				else{ break; }
			}

			return [option, actual[i] ];
		}

		function calculate(detail)
		{
			var show = (!self.elements.tooltip.is(':visible')) ? TRUE : FALSE,
				returned = FALSE;

			// Make sure tooltip is rendered and if not, return
			if(!self.rendered) { return FALSE; }

			// Show and hide tooltip to make sure properties are returned correctly
			if(show) { self.elements.tooltip.addClass('ui-tooltip-accessible'); }
			switch(detail)
			{
				case 'dimensions':
					// Find initial dimensions
					returned = {
						height: self.elements.tooltip.outerHeight(),
						width: self.elements.tooltip.outerWidth()
					};
				break;

				case 'position':
					returned = self.elements.tooltip.offset();
				break;
			}
			if(show) { self.elements.tooltip.removeClass('ui-tooltip-accessible'); }

			return returned;
		}

		// IE max-width/min-width simulator function
		function updateWidth(newWidth)
		{
			// Make sure tooltip is rendered and the browser is IE. If not, return
			if(!self.rendered || !$.browser.msie) { return FALSE; }

			var tooltip = self.elements.tooltip, max, min;

			// Determine actual width
			tooltip.css({ width: 'auto', maxWidth: 'none' });
			newWidth = calculate('dimensions').width;
			tooltip.css({ maxWidth: '' });

			// Parse and simulate max and min width
			max = parseInt(tooltip.css('max-width'), 10) || 0;
			min = parseInt(tooltip.css('min-width'), 10) || 0;
			newWidth = Math.min( Math.max(newWidth, min), max );
			if(newWidth % 2) { newWidth += 1; }

			// Set the new calculated width and if width has not numerical, grab new pixel width
			tooltip.width(newWidth);
		}

		function createTitle()
		{
			var elems = self.elements,
			button = options.content.title.button;

			// Destroy previous title element, if present
			if(elems.title) { elems.title.remove(); }

			// Append new ARIA attribute to tooltip
			elems.tooltip.attr('aria-labelledby', 'ui-tooltip-title-'+id);

			// Create elements
			elems.titlebar = $('<div />').addClass('ui-tooltip-titlebar ui-widget-header').prependTo(elems.wrapper);
			elems.title = $('<div />')
				.attr('id', 'ui-tooltip-'+id+'-title')
				.addClass('ui-tooltip-title')
				.html(options.content.title.text)
				.appendTo(elems.titlebar);

			// Create title close buttons if enabled
			if(button)
			{
				// Use custom button if one was supplied by user, else use default
				if(button.jquery) {
					elems.button = button;
				}
				else if('string' === typeof button) {
					elems.button = $('<a />').html(button);
				}
				else {
					elems.button = $('<a />')
						.addClass('ui-state-default')
						.append(
							$('<span />').addClass('ui-icon ui-icon-close')
						)
				}

				// Setup event handlers
				elems.button
					.prependTo(elems.titlebar)
					.attr('role', 'button')
					.addClass('ui-tooltip-' + (button === TRUE ? 'close' : 'button'))
					.hover(
						function(event){ $(this).toggleClass('ui-state-hover', event.type === 'mouseenter'); }
					)
					.click(function()
					{
						if(!elems.tooltip.hasClass('ui-state-disabled')) {
							self.hide();
						}

						return FALSE;
					})
					.bind('mousedown keydown mouseup keyup mouseout', function(event) {
						$(this).toggleClass('ui-state-active ui-state-focus', (/down$/i).test(event.type));
					});
			}
			else if(elems.button) {
				elems.button.remove();
			}
		}

		function removeTitle()
		{
			var elems = self.elements;

			if(elems.title) {
				elems.titlebar.remove();
				elems.titlebar = elems.title = elems.button = NULL;
				elems.tooltip.removeAttr('aria-labelledby');
			}
		}

		function updateTitle(content)
		{
			// Make sure tooltip is rendered and if not, return
			if(!self.rendered) { return FALSE; }

			// If title isn't already created, create it now
			if(!self.elements.title && content) {
				createTitle();
				self.reposition();
			}
			else if(!content) {
				removeTitle();
			}
			else {
				// Set the new content
				self.elements.title.html(content);
			}
		}


		function updateContent(content)
		{
			// Make sure tooltip is rendered and content is defined. If not return
			if(!self.rendered || !content) { return FALSE; }

			// Append new content if its a DOM array and show it if hidden
			if(content.jquery && content.length > 0)
			{
				if(options.content.clone) {
					self.elements.content.html( content.clone(TRUE).removeAttr('id').css({ display: 'block' }) );
				}
				else {
					self.elements.content.append(content.css({ display: 'block' }));
				}
			}

			// Content is a regular string, insert the new content
			else {
				self.elements.content.html(content);
			}

			// Update width and position
			updateWidth();
			self.reposition(self.cache.event);

			// Show the tooltip if rendering is taking place
			if(self.rendered < 0)
			{
				// Show tooltip on ready
				if(options.show.ready || self.rendered === -2) {
					self.show(self.cache.event);
				}

				// Set rendered status to TRUE
				self.rendered = TRUE;
			}

			return self;
		}


		function assignEvents(show, hide, tooltip, doc)
		{
			var namespace = '.qtip-'+id,
				targets = {
					show: options.show.target,
					hide: options.hide.target,
					tooltip: self.elements.tooltip
				},
				events = { show: String(options.show.event).split(' '), hide: String(options.hide.event).split(' ') };

			// Define show event method
			function showMethod(event)
			{
				if(targets.tooltip.hasClass('ui-state-disabled')) { return FALSE; }

				// If set, hide tooltip when inactive for delay period
				targets.show.trigger('qtip-'+id+'-inactive');

				// Clear hide timers
				clearTimeout(self.timers.show);
				clearTimeout(self.timers.hide);

				// Start show timer
				self.timers.show = setTimeout(function(){ self.show(event); }, options.show.delay);
			}

			// Define hide method
			function hideMethod(event)
			{
				if(targets.tooltip.hasClass('ui-state-disabled')) { return FALSE; }

				// Clear timers and stop animation queue
				clearTimeout(self.timers.show);
				clearTimeout(self.timers.hide);

				// Prevent hiding if tooltip is fixed and event target is the tooltip. Or if mouse positioning is enabled
				if((options.hide.fixed && (/mouse(out|leave)/).test(event.type) && $(event.relatedTarget).parents('.qtip.ui-tooltip').length) ||
					(options.position.target === 'mouse' && options.position.adjust.mouse === TRUE && $(event.relatedTarget).parents('.qtip.ui-tooltip').length))
				{
					// Prevent default and popagation
					event.stopPropagation();
					event.preventDefault();
					return FALSE;
				}

				// If tooltip has displayed, start hide timer
				targets.tooltip.stop(TRUE, TRUE);
				self.timers.hide = setTimeout(function(){ self.hide(event); }, options.hide.delay);
			}

			// Define inactive method
			function inactiveMethod(event)
			{
				if(targets.tooltip.hasClass('ui-state-disabled')) { return FALSE; }

				// Clear timer
				clearTimeout(self.timers.inactive);
				self.timers.inactive = setTimeout(function(){ self.hide(event); }, options.hide.inactive);
			}

			// Check if the tooltip is 'fixed'
			if(tooltip && options.hide.fixed)
			{
				// Add tooltip as a hide target
				targets.hide = targets.hide.add(targets.tooltip);

				// Clear hide timer on tooltip hover to prevent it from closing
				targets.tooltip.bind('mouseover'+namespace, function() {
					if(!targets.tooltip.hasClass('ui-state-disabled')) {
						clearTimeout(self.timers.hide);
					}
				});

				// Focus the tooltip on mouseover
				targets.tooltip.bind('mouseover'+namespace, function(){ self.focus(); });
			}

			// Assign hide events
			if(hide) {
				// Check if the tooltip hides when inactive
				if('number' === typeof options.hide.inactive)
				{
					// Bind inactive method to target as a custom event
					targets.show.bind('qtip-'+id+'-inactive', inactiveMethod);

					// Define events which reset the 'inactive' event handler
					$.each($.fn.qtip.inactiveEvents, function(index, type){
						targets.hide.add(self.elements.tooltip).remove(targets.tooltip).bind(type+namespace+'-inactive', inactiveMethod);
					});
				}

				// Apply hide events
				$.each(events.hide, function(index, type) {
					var showIndex = $.inArray(type, events.show);

					// Both events and targets are identical, apply events using a toggle
					if((showIndex > -1 && $(targets.hide).add(targets.show).length === $(targets.hide).length) || type === 'unfocus')
					{
						targets.show.bind(type+namespace, function(event)
						{
							if(targets.tooltip.is(':visible')) { hideMethod(event); }
							else{ showMethod(event); }
						});

						// Don't bind the event again
						delete events.show[ showIndex ];
					}

					// Events are not identical, bind normally
					else{ targets.hide.bind(type+namespace, hideMethod); }
				});
			}

			// Apply show events
			if(show) {
				$.each(events.show, function(index, type) {
					targets.show.bind(type+namespace, showMethod);
				});

				// If mouse is the target, update tooltip position on mousemove
				if(options.position.target === 'mouse')
				{
					targets.show.bind('mousemove'+namespace, function(event)
					{
						// Update the tooltip position only if the tooltip is visible and adjustment is enabled
						if(options.position.adjust.mouse && !targets.tooltip.hasClass('ui-state-disabled') && targets.tooltip.is(':visible')) {
							self.reposition(event);
						}
					});
				}
			}

			// Apply document events
			if(doc) {
				// Adjust positions of the tooltip on window resize or scroll, if enabled
				if(options.position.adjust.scroll && ($(options.position.target)[0] === window || $(options.position.target)[0] === document)) {
					$(window).bind('scroll'+namespace, function(event) {
						var size = self.get('dimensions');

						// Update position only if tooltip is visible and smaller than window dimensions
						if(self.elements.tooltip.is(':visible') && !(size.height > $(window).height() || size.width > $(window).width())) {
							self.reposition(event);
						}
					});
				}
				if(options.position.adjust.resize) {
					$(window).bind('resize'+namespace, function(event) {
						// Only update position if tooltip is visible
						if(self.elements.tooltip.is(':visible')) { self.reposition(event); }
					});
				}

				// Hide tooltip on document mousedown if unofcus events are enabled
				if((/unfocus/i).test(options.hide.event)) {
					$(document).bind('mousedown'+namespace, function(event) {
						var tooltip = self.elements.tooltip;

						if($(event.target).parents('.qtip.ui-tooltip').length === 0 && $(event.target).add(target).length > 1 &&
						tooltip.is(':visible') && !tooltip.hasClass('ui-state-disabled')) {
							self.hide();
						}
					});
				}
			}
		}

		function unassignEvents(show, hide, tooltip, doc)
		{
			doc = parseInt(doc, 10) !== 0;
			var namespace = '.qtip-'+id,
				targets = {
					show: show ? options.show.target : $('<div/>'),
					hide: hide ? options.hide.target : $('<div/>'),
					tooltip: tooltip ? self.elements.tooltip : $('<div/>')
				},
				events = { show: String(options.show.event).split(' '), hide: String(options.hide.event).split(' ') };

			// Check if tooltip is rendered
			if(self.rendered)
			{
				// Remove show events
				$.each(events.show, function(index, type){ targets.show.unbind(type+namespace); });
				targets.show.unbind('mousemove'+namespace)
					.unbind('mouseout'+namespace)
					.unbind('qtip-'+id+'-inactive');

				// Remove hide events
				$.each(events.hide, function(index, type) {
					targets.hide.add(targets.tooltip).unbind(type+namespace);
				});
				$.each($.fn.qtip.inactiveEvents, function(index, type){
					targets.hide.add(tooltip ? self.elements.content : NULL).unbind(type+namespace+'-inactive');
				});
				targets.hide.unbind('mouseout'+namespace);

				// Remove tooltip events
				targets.tooltip.unbind('mouseover'+namespace);

				// Remove document events
				if(doc) {
					$(window).unbind('resize'+namespace+' scroll'+namespace);
					$(document).unbind('mousedown'+namespace);
				}
			}

			// Tooltip isn't yet rendered, remove render event
			else if(show) { targets.show.unbind(events.show+namespace+'-create'); }
		}

		/*
		* Public API methods
		*/
		$.extend(self, {

			render: function(show)
			{
				var elements = self.elements;

				// If tooltip has already been rendered, exit
				if(self.rendered) { return FALSE; }

				// Call API method and set rendered status
				self.rendered = show ? -2 : -1; // -1: rendering	 -2: rendering and show when done

				// Create initial tooltip elements
				elements.tooltip = $('<div/>')
					.attr({
						id: 'ui-tooltip-'+id,
						role: 'tooltip',
						'aria-describedby': 'ui-tooltip-'+id+'-content'
					})
					.addClass('qtip ui-tooltip ui-widget ui-helper-reset '+options.style.classes)
					.addClass(self.cache.disabled ? 'ui-state-disabled' : '')
					.css('z-index', $.fn.qtip.zindex + $('div.qtip.ui-tooltip').length)
					.data('qtip', self)
					.appendTo(options.position.container);

				// Append to container element
				elements.wrapper = $('<div />').addClass('ui-tooltip-wrapper').appendTo(elements.tooltip);
				elements.content = $('<div />').addClass('ui-tooltip-content')
					.attr('id', 'ui-tooltip-'+id+'-content')
					.addClass('ui-tooltip-content ui-widget-content')
					.appendTo(elements.wrapper);

				// Create title if enabled
				if(options.content.title.text) {
					createTitle();
				}

				// Initialize plugins and apply border
				$.each($.fn.qtip.plugins, function() {
					if(this.initialize === 'render') { this(self); }
				});

				// Assign events
				assignEvents(1, 1, 1, 1);
				$.each(options.events, function(name, callback) {
					elements.tooltip.bind('tooltip'+name, callback);
				});

				// Set the tooltips content
				updateContent(options.content.text);

				// Call API method and if return value is FALSE, halt
				elements.tooltip.trigger('tooltiprender', [self.hash()]);

				return self;
			},

			get: function(notation)
			{
				var result, option;

				switch(notation.toLowerCase())
				{
					case 'offset':
						result = calculate('position');
					break;

					case 'dimensions':
						result = calculate('dimensions');
					break;

					default:
						option = convertNotation(notation.toLowerCase());
						result = (option[0].precedance) ? option[0].string() : (option[0].jquery) ? option[0] : option[0][ option[1] ];
					break;
				}

				return result;
			},

			set: function(notation, value)
			{
				var option = convertNotation(notation.toLowerCase()),
					previous = { show: options.show.target, hide: options.hide.target },
					category, rule,
					checks = {
						builtin: {
							// Content checks
							'^content.text': function(){ updateContent(value); },
							'^content.title.text': function(){ updateTitle(value); },

							// Position checks
							'^position.container$': function(){ if(self.rendered) { self.elements.tooltip.appendTo(value); } },
							'^position.(corner|adjust|target)': function(){ if(self.rendered) { self.reposition(); } },
							'^position.(my|at)$': function(){
								// Parse new corner value into Corner objecct
								var corner = (notation.search(/my$/i) > -1) ? 'my' : 'at';

								if('string' === typeof value) {
									options.position[corner] = new $.fn.qtip.plugins.Corner(value);
								}
							},

							// Show & hide checks
							'^(show|hide).(event|target|fixed)': function() {
								var prop = (notation.search(/fixed/i) > -1) ? [0, [0,1,1,1]] : (notation.search(/hide/i) < 0) ? ['show', [1,0,0,0]] : ['hide', [0,1,0,0]];

								if(prop[0]) { options[prop[0]].target = previous[prop[0]]; }
								unassignEvents.apply(self, prop[1]);

								if(prop[0]) { options[prop[0]].target = value; }
								assignEvents.apply(self, prop[1]);
							}
						}
					};

				// Merge active plugin checks
				$.each(self.plugins, function(name) {
					if('object' === typeof this.checks) {
						checks[name] = this.checks;
					}
				});

				// Set new option value
				option[0][ option[1] ] = value;

				// Re-sanitize options
				sanitizeOptions(options);

				// Execute any valid callbacks
				for(category in checks) {
					for(rule in checks[category]) {
						if((new RegExp(rule, 'i')).test(notation)) {
							checks[category][rule].call(self, option[0], option[1], value);
						}
					}
				}

				return self;
			},

			toggle: function(state, event)
			{
				if(!self.rendered) { return FALSE; }

				var type = state ? 'show' : 'hide',
					tooltip = self.elements.tooltip,
					opts = options[type],
					visible = tooltip.is(':visible'),
					callback;

				// Detect state if valid one isn't provided
				if((typeof state).search('boolean|number')) { state = !tooltip.is(':visible'); }

				// Define after callback
				function after()
				{
					// Reset opacity to avoid bugs
					$(this).css({ opacity: '', height: '' });

					// Prevent antialias from disappearing in IE7 by removing filter attribute
					if(state && $.browser.msie && $(this).get(0).style) { $(this).get(0).style.removeAttribute('filter'); }
				}

				// Return if element is already in correct state
				if((visible && state) || (!visible && !state) || tooltip.is(':animated')) { return self; }

				// Prevent 'blinking' effect when tooltip and show target overlap
				if(event) {
					// Compare targets and events
					if(self.cache.event && (/over|enter/).test(event.type) && (/out|leave/).test(self.cache.event.type) &&
					$(event.target).add(options.show.target).length < 2 && $(event.relatedTarget).parents('.qtip.ui-tooltip').length > 0){
						return self;
					}

					// Cache event
					self.cache.event = $.extend(TRUE, {}, event);
				}

				// Call API methods
				callback = $.Event('tooltip'+type);
				tooltip.trigger(callback, [self.hash(), 90]);
				if(callback.isDefaultPrevented()){ return self; }

				// Execute state specific properties
				if(state) {
					// Remove title attribute
					self.cache.title = target.attr('title');
					target.removeAttr('title');

					// Check tooltip is full rendered
					if(self.rendered === TRUE) {
						self.focus(); // Focus the tooltip before show to prevent visual stacking
						self.reposition(event); // Update tooltip position
					}

					// Hide other tooltips if tooltip is solo
					if(opts.solo) { $(':not(.qtip.ui-tooltip)').qtip('hide'); }
				}
				else {
					target.attr('title', self.cache.title); // Reset attribute content
					clearTimeout(self.timers.show);  // Clear show timer
					tooltip.css({ opacity: '' }); // Reset opacity
				}

				// Set ARIA hidden status attribute
				tooltip.attr('aria-hidden', Boolean(!state));

				// Clear animation queue
				tooltip.stop(TRUE, FALSE);

				// Use custom function if provided
				if($.isFunction(opts.effect)) {
					opts.effect.call(tooltip);
					tooltip.queue(function(){ after(); $(this).dequeue(); });
				}

				// If no effect type is supplied, use a simple toggle
				else if(opts.effect === FALSE) {
					tooltip[ type ]();
					after();
				}

				// Use basic fade function
				else {

					tooltip['fade'+(state?'In':'Out')](90, after);
				}

				// If inactive hide method is set, active it
				if(state) { opts.target.trigger('qtip-'+id+'-inactive'); }

				return self;
			},

			show: function(event){ this.toggle(TRUE, event); },

			hide: function(event){ this.toggle(FALSE, event); },

			focus: function(event)
			{
				if(!self.rendered) { return FALSE; }

				var tooltip = self.elements.tooltip,
					curIndex = parseInt(tooltip.css('z-index'), 10),
					newIndex = $.fn.qtip.zindex + $('.qtip.ui-tooltip').length,
					callback;

				// Only update the z-index if it has changed and tooltip is not already focused
				if(!tooltip.hasClass('ui-state-focus') && curIndex !== newIndex)
				{
					$(':not(.qtip.ui-tooltip)').each(function()
					{
						var api = $(this).qtip(), tooltip, elemIndex;
						if(!api || !api.rendered) { return TRUE; }
						tooltip = api.elements.tooltip;

						// Reduce all other tooltip z-index by 1
						elemIndex = parseInt(tooltip.css('z-index'), 10);
						if(!isNaN(elemIndex)) { tooltip.css({ zIndex: elemIndex - 1 }); }

						// Set focused status to FALSE
						tooltip.removeClass('ui-state-focus');
					});

					// Call API method
					callback = $.Event('tooltipfocus');
					tooltip.trigger(callback, [self.hash(), newIndex]);

					// Set the new z-index and set focus status to TRUE if callback wasn't FALSE
					if(!callback.isDefaultPrevented()) {
						tooltip.css({ zIndex: newIndex }).addClass('ui-state-focus');
					}
				}

				return self;
			},

			reposition: function(event)
			{
				if(!self.rendered) { return FALSE; }

				var target = $(options.position.target),
					tooltip = self.elements.tooltip,
					posOptions = options.position,
					targetWidth,
					targetHeight,
					elemWidth = self.elements.tooltip.width(),
					elemHeight = self.elements.tooltip.height(),
					position, my, at,
					callback,
					adapt = {
						fit: {
							left: function() {
								var beforePos = parseInt(position.left, 10),
									over = position.left + elemWidth - $(window).width() - $(window).scrollLeft();

								position.left = over > 0 ? position.left - over : Math.max(0, position.left);
								return Math.ceil(beforePos - position.left);
							},
							top: function() {
								var beforePos = parseInt(position.top, 10),
									over = position.top + elemHeight - $(window).height() - $(window).scrollTop();

								position.top = over > 0 ? position.top - over : Math.max(0, position.top);
								return Math.ceil(beforePos - position.top);
							}
						},

						flip: {
							left: function() {
								var over = position.left + elemWidth - $(window).width() - $(window).scrollLeft(),
									myOffset = my.x === 'left' ? -elemWidth : my.x === 'right' ? elemWidth : 0,
									offset = -2 * posOptions.adjust.x;

								position.left += position.left < 0 ? myOffset + targetWidth + offset : over > 0 ? myOffset - targetWidth + offset : 0;
								return Math.round(over);
							},
							top: function() {
								var over = position.top + elemHeight - $(window).height() - $(window).scrollTop(),
									myOffset = my.y === 'top' ? -elemHeight : my.y === 'bottom' ? elemHeight : 0,
									atOffset = at.y === 'top' ? targetHeight : at.y === 'bottom' ? -targetHeight : 0,
									offset = -2 * posOptions.adjust.y;

								position.top += position.top < 0 ? myOffset + targetHeight + offset : over > 0 ? myOffset + atOffset + offset : 0;
								return Math.round(over);
							}
						}
					};

				// X and Y coordinates were given
				if(posOptions.at.left && posOptions.at.top) {
					position = $.extend({}, options.position.corner);
					my = at = { x: 'left', y: 'top' };
				}

				// Use smart corner positioning
				else{
					my = posOptions.my;
					at = posOptions.at;

					if(posOptions.target === 'mouse') {
						// Force left top to allow flipping
						at = { x: 'left', y: 'top' };
						targetWidth = targetHeight = 0;

						// Use cached event if one isn't available for positioning
						if(!event) { event = self.cache.event; }
						position = { top: event.pageY, left: event.pageX };
					}
					else {
						if(target[0] === document) {
							targetWidth = target.width();
							targetHeight = target.height();
							position = { top: 0, left: 0 };
						}
						else if(target[0] === window) {
							targetWidth = target.width();
							targetHeight = target.height();
							position = { top: target.scrollTop(), left: target.scrollLeft() };
						}
						else if(target.is('area') && $.fn.qtip.plugins.areaDetails) {
							position = $.fn.qtip.plugins.areaDetails(target, options.at);
							targetWidth = position.width;
							targetHeight = position.height;
							position = position.offset;
						}
						else {
							targetWidth = target.outerWidth();
							targetHeight = target.outerHeight();
							position = target.offset();
						}

						// Adjust position relative to target
						position.left += at.x === 'right' ? targetWidth : at.x === 'center' ? targetWidth / 2 : 0;
						position.top += at.y === 'bottom' ? targetHeight : at.y === 'center' ? targetHeight / 2 : 0;
					}

					// Adjust position relative to tooltip
					position.left += posOptions.adjust.x + (my.x === 'right' ? -elemWidth : my.x === 'center' ? -elemWidth / 2 : 0);
					position.top += posOptions.adjust.y + (my.y === 'bottom' ? -elemHeight : my.y === 'center' ? -elemHeight / 2 : 0);
				}

				// Calculate collision offset values
				if(posOptions.adjust.screen) {
					position.adjust = {
						left: adapt[posOptions.adjust.screen].left(),
						top: adapt[posOptions.adjust.screen].top()
					};
				}
				else { position.adjust = { left: 0, top: 0 }; }

				// Call API method
				callback = $.Event('tooltipmove');
				tooltip.trigger(callback, [self.hash(), position]);
				if(callback.isDefaultPrevented()){ return self; }
				delete position.adjust;

				// Use custom function if provided
				if(tooltip.is(':visible') && $.isFunction(posOptions.adjust.effect)) {
					posOptions.adjust.effect.call(tooltip, position);
					tooltip.queue(function() {
						// Reset attributes to avoid cross-browser rendering bugs
						$(this).css({ opacity: '', height: '' });
						if($.browser.msie && $(this).get(0).style) { $(this).get(0).style.removeAttribute('filter'); }
						$(this).dequeue();
					});
				}
				else {
					tooltip.css(position);
				}

				return self;
			},

			disable: function(state)
			{
				var tooltip = self.elements.tooltip;

				if(!self.rendered) {
					self.cache.disabled = TRUE;
				}
				else {
					tooltip.toggleClass('ui-state-disabled', state);
				}

				return self;
			},

			destroy: function()
			{
				// Destroy any associated plugins when rendered
				if(self.rendered) {
					$.each(self.plugins, function() {
						if(this.initialize === 'render') { this.destroy(); }
					});
				}

				// Remove bound events
				unassignEvents(1, 1, 1, 1);

				// Remove api object and tooltip
				target.removeData('qtip');
				if(self.rendered) { self.elements.tooltip.remove(); }

				return target.attr('title', self.cache.title);
			},

			hash: function()
			{
				var apiHash = $.extend({}, self);
				delete apiHash.cache;
				delete apiHash.timers;
				delete apiHash.options;
				delete apiHash.plugins;
				delete apiHash.render;
				delete apiHash.hash;

				return apiHash;
			}
		});
	}

	// Initialization method
	function init(id, opts)
	{
		var obj,

		// Grab metadata from element if plugin is present
		metadata = ($(this).metadata) ? $(this).metadata(opts.metadata) : {},

		// Create unique configuration object using metadata
		config = $.extend(TRUE, {}, opts, metadata),

		// Use document body instead of document element if needed
		newTarget = $(this)[0] === document ? $(document.body) : $(this);

		// Setup missing content if none is detected
		if('boolean' === typeof config.content.text) {

			// Grab from supplied attribute if available
			if(config.content.attr !== FALSE && $(this).attr(config.content.attr)) {
				config.content.text = $(this).attr(config.content.attr);
			}

			// No valid content was found, abort render
			else {
				return FALSE;
			}
		}

		// Setup target options
		if(config.position.container === FALSE) { config.position.container = $(document.body); }
		if(config.position.target === FALSE) { config.position.target = newTarget; }
		if(config.show.target === FALSE) { config.show.target = newTarget; }
		if(config.hide.target === FALSE) { config.hide.target = newTarget; }

		// Convert position corner values into x and y strings
		config.position.at = new $.fn.qtip.plugins.Corner(config.position.at);
		config.position.my = new $.fn.qtip.plugins.Corner(config.position.my);

		// Destroy previous tooltip if overwrite is enabled, or skip element if not
		if($(this).data('qtip')) {
			if(config.overwrite) {
				$(this).qtip('destroy');
			}
			else if(config.overwrite === FALSE) {
				return FALSE;
			}
		}

		// Initialize the tooltip and add API reference
		obj = new QTip($(this), config, id);
		$(this).data('qtip', obj);

		return obj;
	}

	// jQuery $.fn extension method
	$.fn.qtip = function(options, notation, newValue)
	{
		var command =  String(options).toLowerCase(), // Parse command
			returned = FALSE,
			opts;

		// Check for API request
		if(!options || command === 'api') {
			opts = $(this).eq(0).data('qtip');
			return opts ? opts.hash() : undefined;
		}

		// Execute API command if present
		else if('string' === typeof options)
		{
			$(this).each(function()
			{
				var api = $(this).data('qtip');
				if(!api) { return TRUE; }

				// Call APIcommand
				if((/option|set/).test(command) && notation) {
					if(newValue !== undefined) {
						api.set(notation, newValue);
					}
					else {
						returned = api.get(notation);
					}
				}
				else {
					// Render tooltip if not already rendered when tooltip is to be shown
					if(command === 'show' || (command === 'toggle' && !api.rendered && !api.elements.tooltip.is(':visible'))) {
						api.render();
					}

					// Execute API command
					if(api[command]) {
						api[command](command === 'disable' ? TRUE : command === 'enable' ? FALSE : NULL);
					}
				}
			});

			return returned !== FALSE ? returned : $(this);
		}

		// No API commands. validate provided options and setup qTips
		else if('object' === typeof options)
		{
			// Sanitize options
			sanitizeOptions(options);

			// Build new sanitized options object
			opts = $.extend(TRUE, {}, $.fn.qtip.defaults, options);

			// Bind the qTips
			return $.fn.qtip.bind.call(this, opts);
		}
	};

	// $.fn.qtip Bind method
	$.fn.qtip.bind = function(opts)
	{
		return $(this).each(function() {
			var id, self, options, targets, events, namespace;

			// Find next available ID, or use custom ID if provided
			id = (opts.id === FALSE || opts.id.length < 1 || $('#ui-tooltip-'+opts.id).length) ? $.fn.qtip.nextid++ : opts.id;

			// Setup events namespace
			namespace = '.qtip-'+id+'-create';

			// Initialize the qTip
			self = init.call($(this), id, opts);
			if(self === FALSE) { return TRUE; }
			options = self.options;

			// Initialize plugins
			$.each($.fn.qtip.plugins, function() {
				if(this.initialize === 'initialize') { this(self); }
			});

			// Determine hide and show targets
			targets = { show: options.show.target, hide: options.hide.target };
			events = {
				show: String(options.show.event).replace(' ', namespace+' ') + namespace,
				hide: String(options.hide.event).replace(' ', namespace+' ') + namespace
			};

			// Define hoverIntent function
			function hoverIntent(event) {
				function render() {
					// Cache mouse coords,render and render the tooltip
					self.render(TRUE);

					// Unbind show and hide event
					targets.show.unbind(events.show);
					targets.hide.unbind(events.hide);
				}

				// Cache the mouse data
				self.cache.event = $.extend(TRUE, {}, event);

				// Start the event sequence
				if(options.show.delay > 0) {
					self.timers.show = setTimeout(render, options.show.delay);
					if(events.show !== events.hide) {
						targets.hide.bind(events.hide, function(event){ clearTimeout(self.timers.show); });
					}
				}
				else { render(); }
			}

			// Prerendering is enabled, create tooltip now
			if(options.show.ready || options.prerender) {
				hoverIntent();
			}

			// Prerendering is disabled, create tooltip on show event
			else {
				targets.show.bind(events.show, hoverIntent);
			}
		});
	};

	// Set global qTip properties
	$.fn.qtip.nextid = 0;
	$.fn.qtip.inactiveEvents = 'click dblclick mousedown mouseup mousemove mouseleave mouseenter'.split(' ');
	$.fn.qtip.zindex = 15000;

	// Setup base plugins
	$.fn.qtip.plugins = {
		// Corner object parser
		Corner: function(corner) {
			this.x = String(corner).replace(/middle/i, 'center').match(/left|right|center/i)[0].toLowerCase();
			this.y = String(corner).replace(/middle/i, 'center').match(/top|bottom|center/i)[0].toLowerCase();
			this.offset = { left: 0, top: 0 };
			this.precedance = (corner.charAt(0).search(/^(t|b)/) > -1) ? 'y' : 'x';
			this.string = function(){ return (this.precedance === 'y') ? this.y+this.x : this.x+this.y; };
		}
	};

	// Define configuration defaults
	$.fn.qtip.defaults = {
		prerender: FALSE,
		id: FALSE,
		overwrite: TRUE,

		// Metadata
		metadata: {
			type: 'class'
		},
		// Content
		content: {
			text: TRUE,
			attr: 'title',
			clone: TRUE,
			title: {
				text: FALSE,
				button: FALSE
			}
		},
		// Position
		position: {
			my: 'top left',
			at: 'bottom right',
			target: FALSE,
			container: FALSE,
			adjust: {
				x: 0, y: 0,
				mouse: TRUE,
				screen: FALSE,
				scroll: TRUE,
				resize: TRUE,
				effect: TRUE
			}
		},
		// Effects
		show: {
			target: FALSE,
			event: 'mouseenter',
			effect: TRUE,
			delay: 140,
			solo: FALSE,
			ready: FALSE
		},
		hide: {
			target: FALSE,
			event: 'mouseleave',
			effect: TRUE,
			delay: 0,
			fixed: FALSE,
			inactive: FALSE
		},
		style: {
			classes: ''
		},
		// Callbacks
		events: {
			render: noop,
			move: noop,
			show: noop,
			hide: noop,
			focus: noop
		}
	};
}(jQuery));