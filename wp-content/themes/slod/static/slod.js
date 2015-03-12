// theme supplemental javascript
$=jQuery;
var map_pan_init = false;

$(document).ready(function() {

	if(jQuery('#homemap').length>=1) {
	  jQuery('#map').hide();
	} 

/* wp-standard */
	// Enable menu toggle for small screens.
	var body    = $( 'body' );
	var _window = $( window );

	var nav = $( '#primary-navigation' ), button, menu;
	if ( ! nav ) {
		return;
	}

	button = nav.find( '.menu-toggle' );
	if ( ! button ) {
		return;
	}

	// Hide button if menu is missing or empty.
	menu = nav.find( '.nav-menu' );
	if ( ! menu || ! menu.children().length ) {
		button.hide();
		return;
	}

	$( '.menu-toggle' ).on( 'click.twentyfourteen', function() {
		nav.toggleClass( 'toggled-on' );
	} );

	/*
	 * Makes "skip to content" link work correctly in IE9 and Chrome for better
	 * accessibility.
	 *
	 * @link http://www.nczonline.net/blog/2013/01/15/fixing-skip-to-content-links/
	 */
	_window.on( 'hashchange.twentyfourteen', function() {
		var hash = location.hash.substring( 1 ), element;
	
		if ( ! hash ) {
			return;
		}
	
		element = document.getElementById( hash );
	
		if ( element ) {
			if ( ! /^(?:a|select|input|button|textarea)$/i.test( element.tagName ) ) {
				element.tabIndex = -1;
			}
	
			element.focus();
	
		}
	} );
	
		// Search toggle.
		$( '.search-toggle' ).on( 'click', function( event ) {
			console.log("line 68!");
			var that    = $( this ),
				wrapper = $( '.search-box-wrapper' );
	
		  that.toggleClass( 'active' );
		  wrapper.toggleClass( 'hide' );
			console.log("line 74! toggled!");
	
		  if ( that.is( '.active' ) || $( '.search-toggle .screen-reader-text' )[0] === event.target ) {
				wrapper.find( '.search-field' ).focus();
			} 
	});

	// Focus styles for menus.
	$( '.primary-navigation, .secondary-navigation' ).find( 'a' ).on( 'focus.twentyfourteen blur.twentyfourteen', function() {
		$( this ).parents().toggleClass( 'focus' );
	} );

} );

//});

/* end of wp-standard */


// make map scrollable
$(document).ready(function() {
	doSLODPageInit();
});

function doSLODPageInit() {

	if(jQuery('.postTabs_divs').length>0) {
		jQuery('.postTabs_divs').hide();
		jQuery('.postTabs_curr_div').show();
		jQuery('.postTabsLinks').each(function() {
		  jQuery(this).click(function() {
		    var info = jQuery(this).attr('id').split('_');
		    postTabs_show(info[1], info[0]);
		  });
		});
		    
		cookie_name = 'postTabs_' + postTabs.post_ID;
		    
		if (postTabs.use_cookie && postTabs_getCookie(cookie_name)) {
		  postTabs_show(postTabs_getCookie(cookie_name), postTabs.post_ID);
		}
	}
	
	if(jQuery('#homemap').length>=1) {
	  jQuery('#map').hide();
	} else {
	  jQuery('#map').fadeIn();

		var this_page = window.location.pathname;
		if (this_page.charAt(this_page.length - 1) == '/') {
			this_page = this_page.substr(0, this_page.length - 1);
		}
		var imagemap_item = jQuery('area').filter(function(){
			var xyz=document.createElement('a');
			xyz.href=jQuery(this).prop('href');
			return jQuery(this).prop('href').indexOf(this_page) != -1;
		});
		if (imagemap_item.length>0) {
		  var fields = jQuery(imagemap_item).attr('coords').split(/,/);
		  var pan_dest = parseInt(fields[1]);
		  var map_height = parseInt(jQuery('div#mapwrapper').height());
		  
	  	jQuery("#mapcontent").panzoom( "pan", 0, 0-pan_dest+(map_height/2), {relative: false, animate: true} );
		}
	}

	if (map_pan_init == false) {
		$("#mapcontent").panzoom( { 	eventNamespace: ".panzoom",
																	/*		parent:jQuery('#mapwrapper'), */
																			contain: 'invert',
																			minscale:0.4,
																			maxscale:2,
																			transition:true,
																			duration:500,
//																			easing: "ease-in-out"
																	} );
		map_pan_init = true;
	}
	// explanatory dictionary
  jQuery('.explanatory-dictionary-highlight').qtip({ 
		hide: {
			delay: 100,
			fixed: true
		},
        content: {
        	title: function() {
	        	var id = $(this).attr('data-definition');
				return $('dt.' + id).html();
	        },
        	text: function() {
	        	var id = $(this).attr('data-definition');
				return $('dd.' + id).html();
	        }
        },
        position: {
			my: qtip_settings.my,
			at: qtip_settings.at,
			adjust : {
				method: qtip_settings.corner_adjust
			},
			viewport: $('html')
		},
        style: {
        	classes: qtip_settings.classes
        },
		show: {
	        solo: $('.explanatory-dictionary-highlight')
	    }
    });
}
