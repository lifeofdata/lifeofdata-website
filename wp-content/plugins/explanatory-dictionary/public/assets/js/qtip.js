/* global qtip_settings */
(function ( $ ) {
	'use strict';
	
    $('.explanatory-dictionary-highlight').qtip({ 
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
    
}(jQuery));