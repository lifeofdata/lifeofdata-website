/**
 * Storify Stories Slider is a jQuery plugin that build a storify slider 
 * out off the latest available stories of an user.
 *
 * @name storifyStoriesSlider
 * @version 1.1
 * @requires jQuery v1.7+, jCarousel 0.3+, prettyDate 0.11+, colorBox 1.3+ 
 * @author Renaud Laloux
 * 
 * [storify-stories-slider name="twp20" username="nest_up" width="848" height="400" entries="3" orientation="horizontal"]
 * [storify-stories-slider name="twp21" username="nest_up" width="300" height="500" entries="3" orientation="vertical"]
 */
(function($){
	/**
	 * Options:
	 *  - username: *MANDATORY* Plugin won't allow this field to be null
	 *  - stories:  Number of story to show, determine the width/height of the widget. Must be greater than 0.
	 *  - tileSize: Size of each tiles. Default is 350*220 (330*200 + 2*10 of margin).
	 *  - carousel: Specific configurations for jcarousel. Allow to switch between
	 *  			vertical and horizontal layout, determine the number of scrollable items, etc ...
	 * 				You can also specify additional properties found there: http://sorgalla.com/projects/jcarousel/#Configuration
	 */
	$.widget("ui.storifyStoriesSlider", {
		options: {
			uriStories: 'http://api.storify.com/v1/stories/:username',
	    	uriLikes: 	'http://api.storify.com/v1/stories/:username/likes',
	    	
			username: 	null,			// username of the storify account
			entries:	3,				// number entries displayed by slides
			scroll:	    1,				// number entries slided
			size: {						// size of the widget
				width:  800,
				height: 200
			},
	    	orientation: 'horizontal',	// orientation of the widget, either 'vertical' or 'horizontal'
	    	type: 'stories',			// type of the widget, either 'stories' or 'likes'
	    	paddingArrow: 40,			// padding for the arrows
	    	paddingTiles: 5,
	    	sorting: 'desc',			// stories sorting order, either 'asc' or 'desc'
	    	pages: {
				current: 0,					// the current visible slide
				perPage: 20,				// the number of tiles per scroll
				slides: 0,					// the total number of slides
				pages: 0,					// the total number of pages loaded
				tiles: 0,					// the total number of tiles
				hasMore: false
			}
		},
		_queueAnime: function(start){
		    var rest = [].splice.call(arguments, 1),
		        promise = $.Deferred();
		
		    if (start) {
		        $.when(start()).then(function () {
		            queue.apply(window, rest);
		        });
		    } else {
		        promise.resolve();
		    }
		    return promise;
		},
		_create: function(){
			var self = this;
			
			if (self.options.username == null) {
				throw 'Unable to initialized Storify Stories Slider: Missing username parameter !';
			} else {
				// Filter options values
				
				// Backward compatibility (useless but still)
				if ('carousel' in self.options) {
					if ('vertical' in self.options.carousel) {
						self.options.orientation = self.options.carousel.vertical ? 'vertical' : 'horizontal';
					}
					if ('scroll' in self.options.carousel) {
						self.options.scroll = self.options.carousel.scroll;
					}
				}
				
				// Minimum 1 story
				self.options.entries = self.options.entries <= 0 ? 1 : self.options.entries;
				// Scroll should not exceed the displayed entries
				self.options.scroll = self.options.scroll > self.options.entries ? self.options.entries : self.options.scroll
				// Storify type
				self.options.type = self.options.type == 'stories' || self.options.type == 'likes' ? self.options.type : 'stories';
				// Handle orientation
				self.options.orientation = {
					vertical:   self.options.orientation == 'vertical',
					horizontal: self.options.orientation == 'horizontal'
				};
				// Tweak and store per page value
				self.options.perPage = self.options.perPage > 0 ? self.options.perPage : 20;
				self.options.perPage = self.options.perPage <= 50 ? self.options.perPage : 50;

				// Create inner structure
				self._createStructure();
				
				// Apply styles based on orientation
				self._createOrientedStructure();
				
				// Process first xhr request			    
				self._processStories(false).done(function(){
			    	// Build the carousel
			    	self.carousel.jcarousel({
			    		vertical: self.options.orientation.vertical
			    	}).removeClass('jcarousel-storify-ajax');
			    	self.options.pages.current = 1;
			    	
			    	// Bind event such a next/prev and modal
			    	self.bindEvents();
			    	
			    	// Finally handle the arrows
					self._showArrows({ prev: 'disabled' });
					self.arrows.prev.fadeIn();
					self.arrows.next.fadeIn();
				}).fail(function(){
					self.element.hide();
					throw 'Unable to initialized Storify Stories Slider: Unable to reach Storify API';
				});
			}
		},
		/**
		 * Build the inner structure and the next/prev controls.
		 * Bufferize the selector and assign them to inner properties. 
		 */
		_createStructure: function(){
			var self = this;
			
			// Build the inner structure and setup the size
			self.element.css({
				width:  self.options.size.width+'px',
				height: self.options.size.height+'px',
			}).append(
				'<a href="#" class="jcarousel-storify-arrow jcarousel-storify-arrow-disabled" style="display: none;"></a>'
				+'<a href="#" class="jcarousel-storify-arrow jcarousel-storify-arrow-disabled" style="display: none;"></a>'
    			+'<div class="jcarousel jcarousel-storify-ajax"><ul></ul></div>'
		    ).addClass('jcarousel-skin-default');
		    
		    // Bufferize selector
		    self.carousel = self.element.children('.jcarousel');
			self.stories  = self.carousel.children('ul');
			self.arrows = $('.jcarousel-storify-arrow', self.element);
			self.arrows = {
				prev: self.arrows.filter(':eq(0)'),
				next: self.arrows.filter(':eq(1)')
			};
		},
		/**
		 * Apply style depending on the orientation. 
		 */
		_createOrientedStructure: function(){
			var self = this;
			
			// Apply class/styles based on orientation
			var pA = self.options.paddingArrow;
			var pT = self.options.paddingTiles;
				
		    if (self.options.orientation.vertical) {
		    	self.carousel.addClass('jcarousel-vertical');
		    	self.carousel.css({
	    			width:   self.options.size.width,
		    		height:  self.options.size.height-2*pA
	    		});
				
				self.arrows.prev.addClass('jcarousel-storify-prev-v').css({
					'margin-bottom': pA-self.arrows.prev.height()
				});
				self.arrows.next.addClass('jcarousel-storify-next-v').css({
					'margin-top': pA-self.arrows.next.height()
				});
				
				self.element.css({float: 'left'}).append(self.arrows.next.detach().css({'margin-top': '6px'}));
				
				// Compute tile size of the the tiles sizes
				self.options.tileSize = { 
					width:  self.options.size.width - 2*pT,
					height: Math.ceil((self.options.size.height - 2*pA) / self.options.entries)-2*pT
				};
		    } else {
		    	// Apply size to elements
		    	self.carousel.addClass('jcarousel-horizontal');
		    	self.element.css({margin: 'auto'});
			    self.carousel.css({
	    			width:   self.options.size.width - 2*pA,
		    		height:  self.options.size.height,
		    		margin:  '0 '+pA+'px 0 '+pA+'px'
	    		});
    			
		    	self.arrows.prev.addClass('jcarousel-storify-prev-h');
				self.arrows.next.addClass('jcarousel-storify-next-h');
				
				// Compute tile size of the the tiles sizes
				self.options.tileSize = { 
					width:  Math.ceil((self.options.size.width - 2*pA) / self.options.entries) - 2*pT,
					height: self.options.size.height - 2*pT
				};
		    }
		},
		bindEvents: function() {
			var self = this;
			
		    $('.jcarousel-storify-arrow', self.element).bind('click', $.proxy(self._triggerStoryControl, self));
	    	$('.storify-trigger-external', self.element).bind('click', $.proxy(self._triggerStoryExternal, self));
	    	$('.storify-trigger-story', self.element).bind('click', $.proxy(self._triggerStory, self));
		},
		unbindEvents: function() {
			var self = this;
			
		    $('.jcarousel-storify-arrow', self.element).unbind('click');
	    	$('.storify-trigger-external', self.element).unbind('click');
	    	$('.storify-trigger-story', self.element).unbind('click');
		},
		destroy: function(){
			var self = this;
			
			// Unbind events
			self.unbindEvents();

			// Destory the jcarousel
			self.carousel.jcarousel('destroy');

			// Reset pagination properties			
			self.options.pages.current = 0;
			self.options.pages.perPage = 5;
			self.options.pages.slides = 0;
			self.options.pages.pages = 0;
			self.options.pages.tiles = 0;
			self.options.pages.hasMore = false;
			
			// Reset buffered selectors
			self.carousel = null;
			self.stories = null;
			self.arrows = null;
			
			// Clear structure
			self.element.empty().css({
				width: '',
				height: ''
			}); 
			
			$.Widget.prototype.destroy.call( this );
		},
		_getRequestURL: function() {
			var self = this;
			// Determine the target API url
	    	if (self.options.type == 'stories') {
	    		url = self.options.uriStories;
	    	} else {
	    		url = self.options.uriLikes;
	    	}
	    	
			return url.replace(':username', self.options.username);
		},
		_countTilesLeft: function() {
			var self = this;
			return (self.options.pages.pages * self.options.pages.perPage) - (self.options.entries) - ((self.options.pages.current-1) * self.options.scroll);
		},
		_getEmptyTilesCount: function() {
			var self = this;
			return self.options.scroll - (self.options.pages.pages * self.options.pages.perPage) % self.options.scroll;
		},
		_processStories: function() {
	    	var self = this;

	    	// Process the xhr request
	    	return $.ajax({
					url: self._getRequestURL(),
					dataType: 'jsonp',
					data: {
						direction: self.options.sorting,
						per_page: self.options.pages.perPage,
						page: self.options.pages.pages+1
					}
				}).success(function(json){
				    if (json.code == 200) {
				    	// Render each stories
				    	$.each(json.content.stories, function(idx, story) {
							self._renderStory(story);
				    	});
				    	
		    			// Update page/tile/slide count
				    	var stories = json.content.stories.length;
		    			
		    			self.options.pages.tiles += stories;
		    			self.options.pages.hasMore = stories == self.options.pages.perPage;
		    			self.options.pages.slides = Math.ceil((self.options.pages.tiles - self.options.entries) / self.options.scroll) + 1;
		    			self.options.pages.pages ++;
				    } else {
				    	throw 'Unable to process Storify XHR response';
				    }
			    });
	    },
	    _renderStory: function(story) {
	    	var self = this;
			var li = '<div class="story-tile" style="width: '+self.options.tileSize.width +'px; height: '+self.options.tileSize.height+'px; padding: '+self.options.paddingTiles+'px;" data-slug="' + this._nullCheck(story.slug) + '">'
					+ '	<a href="'+ this._nullCheck(story.permalink) +'" class="story-tile-body storify-trigger-story" style="height:'+(this.options.tileSize.height-50)+'px">'
					+ '		<span class="story-tile-thumbnail" style="height:'+(this.options.tileSize.height-55)+'px;">'
					+ '			<span class="story-tile-thumbnail-image" style="background-image: url('+ this._nullCheck(story.thumbnail) +');" ></span>'
					+ '			<span class="story-tile-overlay"></span>'
					+ '			<span class="pattern"></span>'
					+ '			<span class="story-tile-text">'
					+ '				<span class="story-tile-title">'
					+ '					<span class="story-tile-overlay"></span>'
					+ '					<strong class="story-tile-title-text">'+ this._nullCheck(story.title) +'</strong>'
					+ '				</span>'
					+ '				<span class="clear"></span>'
					+ '				<span class="story-tile-description">'+ this._nullCheck(story.description) +'</span>'
					+ '			</span>'
					+ '		</span>'
					+ '	</a>'
					+ '	<div class="story-tile-below">'
					+ '		<a href="'+ this._nullCheck(story.author.permalink) +'" class="story-tile-avatar left storify-trigger-external">'
					+ '			<img src="'+ this._nullCheck(story.author.avatar) +'">'
					+ '		</a>'
					+ '		<div class="story-tile-info thumb">'
					+ '			<div class="story-tile-data">'
					+ '				<div class="story-tile-author">'
					+ '					<a href="'+ this._nullCheck(story.author.permalink) +'" class="storify-trigger-external">'+ this._nullCheck(story.author.username) +'</a>'
					+ '				</div>'
					+ '				<div data-timestamp="'+ story.date.published +'" class="timestamp story-tile-date">'+ $.timeago(story.date.published) +'</div>'
					+ '				<div class="story-tile-views"><i></i><strong>'+ this._nullCheck(story.stats.views) +'</strong></div>'
					+ '				<div class="story-tile-likes"><i></i><strong>'+ this._nullCheck(story.stats.likes) +'</strong></div>'
					+ '			</div>'
					+ '		</div>'
					+ '	</div>'
					+ '	<div class="clear"></div>'
					+ '</div>';
			
			// If there is empty node we have to replace it's content.
			// We cannot remove empty node then call reload on the jcarousel
			// because it may, on some odd cases, scroll up to the first item.
			$target = self.stories.find('.jcarousel-storify-emptyNode');
			if ($target.length > 0) {
	    		$target.first().empty().removeClass('jcarousel-storify-emptyNode').append(li);
	    	} else {
	    		self.stories.append('<li>'+li+'</li>');
	    	}
	    },
	    _renderEmptyStory: function() {
	    	var self = this;
	    	self.stories.append('<li class="jcarousel-storify-emptyNode">'
	    			+ '<div class="story-tile" style="width: '+self.options.tileSize.width +'px; height: '+self.options.tileSize.height+'px; padding: '+self.options.paddingTiles+'px;">'
					+ '	<div class="story-tile-body storify-trigger-story" style="height: '+(self.options.tileSize.height-50)+'px;">'
					+ '		<span class="story-tile-thumbnail jcarousel-storify-ajax">'
					+ '		</span>'
					+ '	</div>'
					+ '	<div class="clear"></div>'
					+ '</div>'
					+ '</li>');
	    },
	    _triggerStory: function(e) {
			e.preventDefault();
			e.stopImmediatePropagation();
			
	    	var target = $(e.delegateTarget);
	    	target = target == null ? target : target.attr('href');
	    	target = target != '#'  ? target : null;
	    	target = target == null ? target : target+'/embed';

	    	if (target != null) {
		    	$.colorbox({
		    		href:   target, 
		    		iframe: true, 
		    		width:  '45%', 
		    		height: '90%' 
		    	});
		    }
	    },
	    _triggerStoryExternal: function(e) {
			e.preventDefault();
			e.stopImmediatePropagation();

			var target = $(e.target); 
			if (!target.is('a')) {
				target = target.closest('a');
			}
			
			window.open(target.attr('href'), '_blank');
	    },
	    _triggerStoryControl: function(e){
	    	e.preventDefault();
			e.stopImmediatePropagation();

			var self = this;
			var isDisabled = $(e.target).attr('class').indexOf('jcarousel-storify-arrow-disabled') != -1;
			
			// Dismiss trigger on deactivated arrows
			if (! isDisabled){// $(e.target).hasClass('jcarousel-storify-arrow-disabeld')) {
				
				var scroll = $(e.target).attr('class');
				    scroll = scroll.indexOf('next') != -1 ? 'next' : scroll.indexOf('prev') != -1 ? 'prev' : null;
				//var scroll = $(e.target).attr('class').replace('jcarousel-storify-','');
				
				if (scroll == 'next') {
					
					var tilesLeft = self._countTilesLeft();
					var nextSlideComplete = tilesLeft >= self.options.scroll;
					
					if (nextSlideComplete) {
						// Default scrolling behavior, simply scroll
						self.carousel.jcarousel('scroll', '+='+self.options.scroll);
						self.options.pages.current ++;
						
						// Activate/Deactivate arrows					
						self._showArrows();
						
					} else {
						// Scrolling last slide ...
						if (!self.options.pages.hasMore) {
							// No more content is available
							self.carousel.jcarousel('scroll', '+='+self.options.scroll);
							self.options.pages.current ++;
							
							// Activate/Deactivate arrows					
							self._showArrows();
						} else {
							// More content is available, fetch it
							
							// Check if there are missing tile that need to be added to complete the slide
			    			var missing = self.options.scroll - tilesLeft;
			    			for (var i=0; i<missing; i++) {
			    				self._renderEmptyStory();
			    			}
			    			
			    			// Now we disable the 'next' button while processing the XHR
			    			self.options.pages.current ++;
			    			self._showArrows({next: 'disabled'});
			    			
			    			// We reload and scroll to the last slide
			    			self.carousel.jcarousel('reload');
			    			
			    			self.carousel.jcarousel('scroll', '+='+self.options.scroll, true, function(){
								// We load additional content
								self._processStories().done(function(){
							    	// Reload the carousel
					    			self.carousel.jcarousel('reload');
						    	
									// And finally handle the arrows					
									self._showArrows();
								});
			    			});
						}
					}
				} else if (scroll == 'prev') {
					if (self.options.pages.current > 1) {
						self.carousel.jcarousel('scroll', '-='+self.options.scroll);
						self.options.pages.current --;
					}
					
					// Finally handle the arrows					
					self._showArrows();
				}
			} else {
				//console.log(' + call dismissed');
			}
	    },
	    _toggleArrow: function(arrow, active) {
	    	$arrow = this.arrows[arrow];
	    	if ($arrow != undefined) {
	    		if (active) {
	    			$arrow.removeClass('jcarousel-storify-arrow-disabled');
	    		} else {
	    			$arrow.addClass('jcarousel-storify-arrow-disabled');
	    		}
	    	}
	    },
	    _showArrows: function(forcedStates) {
			var self = this;
			if (forcedStates == undefined) {
	    		forcedStates = { 
	    			prev: 'auto', 
	    			next: 'auto' 
	    		};
	    	} else {
	    		if (! ('prev' in forcedStates)) {
	    			forcedStates.prev = 'auto';	
	    		}
	    		if (! ('next' in forcedStates)) {
	    			forcedStates.next = 'auto';	
	    		}
	    	}
	    	
			// Dissallow left arrow if on the left edge
			if ( (forcedStates.prev == 'auto' && self.options.pages.current <= 1) || forcedStates.prev == 'disabled' ) {
				self._toggleArrow('prev', false);
			} else {
				self._toggleArrow('prev', true);
			}

			// Dissallow right arrow if last and no more content			
			if ( (forcedStates.next == 'auto' && self.options.pages.current == self.options.pages.slides && !self.options.pages.hasMore) || forcedStates.next == 'disabled') {
				self._toggleArrow('next', false);
			} else {
				self._toggleArrow('next', true);
			}
		},
	    _nullCheck: function(text) {
	    	return text != null ? text : '';
	    }
	});
})(jQuery);