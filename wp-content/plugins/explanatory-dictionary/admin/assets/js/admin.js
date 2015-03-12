(function ( $ ) {
	'use strict';

	$(function () {

		var explandict_globalStyle = '';
		var explandict_tooltips, explandict_positioningTooltip, explandict_positioningTooltipApi;
		
		$('.hide-notice').click(function(e){
			e.preventDefault();
			var $parent = $(this).closest('.admin-message');
			$parent.hide();
		});

		if ( $('.color-picker').length > 0 ){ 
			jQuery( document ).ready(function($) {   
    		    $('.color-picker').wpColorPicker();
    		});             
		}
		
		if( !$('#qtip-custom-theme').is(':checked') ) {
			$('.show-if-custom').hide();
		}
		
		if( $('#_hide_title_from_tooltip').is(':checked') ) {
			$('.hide-if-hidden-title').hide();
		}
		
		if( $('#_title_use_theme_settings').is(':checked') ) {
			$('.hide-if-theme-title-styling').hide();
		}
		
		if( $('#_content_use_theme_settings').is(':checked') ) {
			$('.hide-if-theme-content-styling').hide();
		}
		
		if( ! $('#_use_custom_alphabet').is(':checked') ) {
			$('.hide-if-custom-alphabet').hide();
		}
		
		$('#_use_custom_alphabet').on('change', function(e) {
			e.stopPropagation();
			if( $('#_use_custom_alphabet').is(':checked')) {
				$('.hide-if-custom-alphabet').slideDown();
			} else {
				$('.hide-if-custom-alphabet').slideUp();
			}
		});
		
		if( $('#_custom_word_styling').is(':checked') ) {
			$('.hide-if-custom-word-styling').hide();
		}
		
		$('#_custom_word_styling').on('change', function(e) {
			e.stopPropagation();
			if( $('#_custom_word_styling').is(':checked')) {
				$('.hide-if-custom-word-styling').slideUp();
			} else {
				$('.hide-if-custom-word-styling').slideDown();
			}
		});
		
		function explandict_toggleCustomTheme() {
			if( $('#qtip-custom-theme').is(':checked')) {
				$('.show-if-custom').slideDown( 400, function() {
					explandict_positioningTooltipApi.reposition(null, false);
				});

			} else {
				$('.show-if-custom').slideUp( 400, function() {
					explandict_positioningTooltipApi.reposition(null, false);
				});
			}
		}
		
		function explandict_toggleHideTooltipTitle() {
			if( $('#_hide_title_from_tooltip').is(':checked')) {
				$('.hide-if-hidden-title').slideUp();
			} else {

				$('.hide-if-hidden-title').slideDown();
				if( $('#_title_use_theme_settings').is(':checked') ) {
					$('.hide-if-theme-title-styling').hide();
				}
			}
		}
		
		$('#_hide_title_from_tooltip').on('change', function(e) {
			e.stopPropagation();
			explandict_toggleHideTooltipTitle();
		});
		
		function explandict_toggleUseTooltipStylingForTitle() {
			if( $('#_title_use_theme_settings').is(':checked') ) {
				$('.hide-if-theme-title-styling').slideUp();
			} else {
				$('.hide-if-theme-title-styling').slideDown();
			}
		}
		
		$('#_title_use_theme_settings').on('change', function(e) {
			e.stopPropagation();
			explandict_toggleUseTooltipStylingForTitle();
		});
		
		function explandict_toggleUseTooltipStylingForContent() {
			if( $('#_content_use_theme_settings').is(':checked') ) {
				$('.hide-if-theme-content-styling').slideUp();
			} else {
				$('.hide-if-theme-content-styling').slideDown();
			}
		}
		
		$('#_content_use_theme_settings').on('change', function(e) {
			e.stopPropagation();
			explandict_toggleUseTooltipStylingForContent();
		});
		
		explandict_tooltips = $('#qtip-themes .qtip').each(function(){
			var elem = $(this);
			
			$(this).qtip({
				hide: {
					delay: 100,
					fixed: true
				},
		        content: {
		        	text: function() {
						return elem.attr('oldtitle');
					},
		        	title: function() {
						return elem.attr('data-title');
					}
		        },
		        position: {
					my: 'bottom center',
					at: 'top center'
				},
				style: {
					classes: this.className
				},
				show: {
			        solo: $('#qtip-themes .qtip')
			    }
		    });
			
			var api = $(this).qtip('api');
			if(!api.origStyle) {
				api.origStyle = elem.attr('data-style') + ' qtip-default ';
			}
		});
		
		function explandict_updateTooltips() {
			$(explandict_tooltips).each(function() {
				var api = $(this).qtip('api');
				
				var newStyle = api.origStyle + explandict_globalStyle;
				
				if(api.options.style.classes !== newStyle) {
					api.set('style.classes', newStyle);
				}
			});
		}
		
		
		if( $('#qtip-styling-rounded').is(':checked') ) {
			explandict_addQtipClass( $('#qtip-styling-rounded').attr('data-class') );
		}
		if( $('#qtip-styling-shadow').is(':checked') ) {
			explandict_addQtipClass( $('#qtip-styling-shadow').attr('data-class') );
		}
		if( !$('#_title_use_theme_settings').is(':checked') ) {
			explandict_addQtipClass( $('#_title_use_theme_settings').attr('data-class') );
		}
		if( !$('#_content_use_theme_settings').is(':checked') ) {
			explandict_addQtipClass( $('#_content_use_theme_settings').attr('data-class') );
		}
		
		$('#qtip-styling-rounded, #qtip-styling-shadow').on('change', function(e) {
			e.stopPropagation();
			
			if(! $(this).is(':checked')) {
				explandict_removeQtipClass($(this).attr('data-class'));
			} else {
				explandict_addQtipClass($(this).attr('data-class'));
			}
		});
		
		$('#_title_use_theme_settings, #_content_use_theme_settings').on('change', function(e) {
			e.stopPropagation();
			
			if(! $(this).is(':checked')) {
				explandict_addQtipClass($(this).attr('data-class'));
			} else {
				explandict_removeQtipClass($(this).attr('data-class'));
			}
		});
		
		function explandict_addQtipClass(className) {
			$('#qtip-themes .qtip-themes-basic .qtip').addClass(className);
			explandict_globalStyle += ' ' + className;
			
			explandict_updateTooltips();
		}
		
		function explandict_removeQtipClass(className) {
			$('#qtip-themes .qtip-themes-basic .qtip').removeClass(className);
			
			explandict_globalStyle = explandict_globalStyle.replace(' ' + className, '');
			
			explandict_updateTooltips();
		}
		
		$('.qtip-container').on('click', function(e) {
			e.stopPropagation();
			
			$('.qtip-container.active').removeClass('active');
			$(this).addClass('active');
			$(this).children('input[type="radio"]').attr('checked', 'checked');
			explandict_toggleCustomTheme();
		});
		
		$('.qtip-container input[type="radio"]:checked').parent('.qtip-container').addClass('active');

		// This is for the positioning of the tooltip
		explandict_positioningTooltip = $('.qtip-example').qtip({
			content: {
				text: 'Move me around using the dropdowns'
			},
		    show: {
		        ready: true
		    },
		    hide: false,
	        position: {
				my: get('my'),
				at: get('at'),
				adjust : {
					method: $('#corner-adjust').val()
				},
		    	viewport: $('.qtip-example')
			}
		});
		explandict_positioningTooltipApi = $(explandict_positioningTooltip).qtip('api');
		
		// we trigger the swap so we don't have to do this in PHP
		$('#corner-my-swap').trigger('change');
		
		$('#corner-my-swap').on('change', function(){
			$('#corner-my-y')[ this.checked ? 'insertAfter': 'insertBefore' ]( $('#corner-my-x')[0]	).trigger('change');
		});
		
		$('#corner-my-y, #corner-my-x, #corner-at-x, #corner-at-y, #corner-adjust').on('change', function(){
			explandict_positioningTooltipApi.set({
				'position.my': get('my'),
				'position.at': get('at'),
				'position.adjust.method': $('#corner-adjust').val()
			});
			
			$('#corner-my-y-input').val($('#corner-my-y').val());
			$('#corner-my-x-input').val($('#corner-my-x').val());
			$('#corner-at-y-input').val($('#corner-at-y').val());
			$('#corner-at-x-input').val($('#corner-at-x').val());
			$('#corner-adjust-input').val($('#corner-adjust').val());
		});
		
		function get(c) {
			var arr = [ $('#corner-'+c+'-y').val(), $('#corner-'+c+'-x').val() ];
			return ($('#corner-'+c+'-swap').is(':checked') ? arr.reverse() : arr).join(' ');
		}
	});

}(jQuery));