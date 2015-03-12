var cspAdminFormChanged = [];
window.onbeforeunload = function(){
	// If there are at lease one unsaved form - show message for confirnation for page leave
	if(cspAdminFormChanged.length)
		return 'Some changes were not-saved. Are you sure you want to leave?';
};
jQuery(document).ready(function(){
	jQuery('#cspAdminOptionsTabs').tabs().addClass( "ui-tabs-vertical ui-helper-clearfix" );
    jQuery( "#cspAdminOptionsTabs li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
	
	jQuery('#cspAdminOptionsForm').submit(function(){
		jQuery(this).sendFormCsp({
			msgElID: 'cspAdminMainOptsMsg'
		,	onSuccess: function(res) {
				if(!res.error) {
					changeModeOptionCsp( jQuery('#cspAdminOptionsForm [name="opt_values[mode]"]').val() );
				}
			}
		});
		return false;
	});
	jQuery('#cspAdminOptionsSaveMsg').submit(function(){
		return false;
	});
	jQuery('.cspSetTemplateOptionButton').click(function(){
		toeShowTemplatePopupCsp();
		return false;
	});
	jQuery('.cspGoToTemplateTabOptionButton').click(function(){
		// Go to tempalte options tab
		var index = jQuery('#cspAdminOptionsTabs a[href="#cspTemplateOptions"]').parents('li').index();
		jQuery('#cspAdminOptionsTabs').tabs('option', 'active', index);
		
		toeShowTemplatePopupCsp();
		return false;
	});
	function toeShowTemplatePopupCsp() {
		toeShowDialogCustomized(jQuery('#cspAdminTemplatesSelection'), {
			width: 700
		,	height: jQuery(window).height()
		,	modal: true
		,	closeOnBg: true
		,	title: toeLangCsp('Select Your Template')
		});
	}
	jQuery('#cspAdminOptionsForm [name="opt_values[mode]"]').change(function(){
		changeModeOptionCsp( jQuery(this).val(), true );
	});
	changeModeOptionCsp( toeOptionCsp('mode') );
	selectTemplateImageCsp( toeOptionCsp('template') );
	// Remove class is to remove this class from wrapper object
	jQuery('.cspAdminTemplateOptRow').not('.cspAvoidJqueryUiStyle').buttonset().removeClass('ui-buttonset');
	
	jQuery('#cspAdminTemplateOptionsForm').submit(function(){
		jQuery(this).sendFormCsp({
			msgElID: 'cspAdminTemplateOptionsMsg'
		});
		return false;
	});
	jQuery('#cspAdminTemplateOptionsForm [name="opt_values[bg_type]"]').change(function(){
		changeBgTypeOptionCsp();
	});
	changeBgTypeOptionCsp();
	
	 jQuery('.cspOptTip').live('mouseover',function(event){
        if(!jQuery('#cspOptDescription').attr('toeFixTip')) {
			var pageY = event.pageY - jQuery(window).scrollTop();
			var pageX = event.pageX;
			var tipMsg = jQuery(this).attr('tip');
			var moveToLeft = jQuery(this).hasClass('toeTipToLeft');	// Move message to left of the tip link
			if(typeof(tipMsg) == 'undefined' || tipMsg == '') {
				tipMsg = jQuery(this).attr('title');
			}
			toeOptShowDescriptionCsp( tipMsg, pageX, pageY, moveToLeft );
			jQuery('#cspOptDescription').attr('toeFixTip', 1);
		}
        return false;
    });
    jQuery('.cspOptTip').live('mouseout',function(){
		toeOptTimeoutHideDescriptionCsp();
        return false;
    });
	jQuery('#cspOptDescription').live('mouseover',function(e){
		jQuery(this).attr('toeFixTip', 1);
		return false;
    });
	jQuery('#cspOptDescription').live('mouseout',function(e){
		toeOptTimeoutHideDescriptionCsp();
		return false;
    });
	
	jQuery('#cspColorBgSetDefault').click(function(){
		jQuery.sendFormCsp({
			data: {page: 'options', action: 'setTplDefault', code: 'bg_color', reqType: 'ajax'}
		,	msgElID: 'cspAdminOptColorDefaultMsg'
		,	onSuccess: function(res) {
				if(!res.error) {
					if(res.data.newOptValue) {
						jQuery('#cspAdminTemplateOptionsForm [name="opt_values[bg_color]"]')
							.val( res.data.newOptValue )
							.css('background-color', res.data.newOptValue);
					}
				}
				// Trigger event for case if we use this as external event
				jQuery('#cspColorBgSetDefault').trigger('cpsOptSaved');
			}
		});
		return false;
	});
	jQuery('#cspImgBgSetDefault').click(function(){
		jQuery.sendFormCsp({
			data: {page: 'options', action: 'setTplDefault', code: 'bg_image', reqType: 'ajax'}
		,	msgElID: 'cspAdminOptImgBgDefaultMsg'
		,	onSuccess: function(res) {
				if(!res.error) {
					if(res.data.newOptValue) {
						jQuery('#cspOptBgImgPrev').attr('src', res.data.newOptValue);
					}
				}
				// Trigger event for case if we use this as external event
				jQuery('#cspImgBgSetDefault').trigger('cpsOptSaved');
			}
		});
		return false;
	});
	jQuery('#cspImgBgRemove').click(function(){
		if(confirm(toeLangCsp('Are you sure?'))) {
			jQuery.sendFormCsp({
				data: {page: 'options', action: 'removeBgImg', reqType: 'ajax'}
			,	msgElID: 'cspAdminOptImgBgDefaultMsg'
			,	onSuccess: function(res) {
					if(!res.error) {
						jQuery('#cspOptBgImgPrev').attr('src', '');
					}
				}
			});
		}
		return false;
	});
	jQuery('#cspLogoSetDefault').click(function(){
		jQuery.sendFormCsp({
			data: {page: 'options', action: 'setTplDefault', code: 'logo_image', reqType: 'ajax'}
		,	msgElID: 'cspAdminOptLogoDefaultMsg'
		,	onSuccess: function(res) {
				if(!res.error) {
					if(res.data.newOptValue) {
						jQuery('#cspOptLogoImgPrev').attr('src', res.data.newOptValue);
					}
				}
				// Trigger event for case if we use this as external event
				jQuery('#cspLogoSetDefault').trigger('cpsOptSaved');
			}
		});
		return false;
	});
	jQuery('#cspLogoRemove').click(function(){
		if(confirm(toeLangCsp('Are you sure?'))) {
			jQuery.sendFormCsp({
				data: {page: 'options', action: 'removeLogoImg', reqType: 'ajax'}
			,	msgElID: 'cspAdminOptLogoDefaultMsg'
			,	onSuccess: function(res) {
					if(!res.error) {
						jQuery('#cspOptLogoImgPrev').attr('src', '');
					}
				}
			});
		}
		return false;
	});
	jQuery('#cspMsgTitleSetDefault').click(function(){
		jQuery.sendFormCsp({
			data: {page: 'options', action: 'setTplDefault', code: 'msg_title_params', reqType: 'ajax'}
		,	msgElID: 'cspAdminOptMsgTitleDefaultMsg'
		,	onSuccess: function(res) {
				if(!res.error) {
					if(res.data.newOptValue) {
						if(res.data.newOptValue.msg_title_color)
							jQuery('#cspAdminTemplateOptionsForm [name="opt_values[msg_title_color]"]')
								.val( res.data.newOptValue.msg_title_color )
								.css('background-color', res.data.newOptValue.msg_title_color);
						if(res.data.newOptValue.msg_title_font)
							jQuery('#cspAdminTemplateOptionsForm [name="opt_values[msg_title_font]"]').val(res.data.newOptValue.msg_title_font);
					}
				}
				// Trigger event for case if we use this as external event
				jQuery('#cspMsgTitleSetDefault').trigger('cpsOptSaved');
			}
		});
		return false;
	});
	jQuery('#cspMsgTextSetDefault').click(function(){
		jQuery.sendFormCsp({
			data: {page: 'options', action: 'setTplDefault', code: 'msg_text_params', reqType: 'ajax'}
		,	msgElID: 'cspAdminOptMsgTextDefaultMsg'
		,	onSuccess: function(res) {
				if(!res.error) {
					if(res.data.newOptValue) {
						if(res.data.newOptValue.msg_text_color)
							jQuery('#cspAdminTemplateOptionsForm [name="opt_values[msg_text_color]"]')
								.val( res.data.newOptValue.msg_text_color )
								.css('background-color', res.data.newOptValue.msg_text_color);
						if(res.data.newOptValue.msg_text_font)
							jQuery('#cspAdminTemplateOptionsForm [name="opt_values[msg_text_font]"]').val(res.data.newOptValue.msg_text_font);
					}
				}
				// Trigger event for case if we use this as external event
				jQuery('#cspMsgTextSetDefault').trigger('cpsOptSaved');
			}
		});
		return false;
	});
	// If some changes was made in those forms and they were not saved - show message for confirnation before page reload
	var formsPreventLeave = ['cspAdminOptionsForm', 'cspAdminTemplateOptionsForm', 'cspSubAdminOptsForm', 'cspAdminSocOptionsForm'];
	jQuery('#'+ formsPreventLeave.join(', #')).find('input,select').change(function(){
		var formId = jQuery(this).parents('form:first').attr('id');
		changeAdminFormCsp(formId);
	});
	jQuery('#'+ formsPreventLeave.join(', #')).find('input[type=text],textarea').keyup(function(){
		var formId = jQuery(this).parents('form:first').attr('id');
		changeAdminFormCsp(formId);
	});
	jQuery('#'+ formsPreventLeave.join(', #')).submit(function(){
		if(cspAdminFormChanged.length) {
			var id = jQuery(this).attr('id');
			for(var i in cspAdminFormChanged) {
				if(cspAdminFormChanged[i] == id) {
					cspAdminFormChanged.pop(i);
				}
			}
		}
	});
	
	jQuery('.cspAdminTemplateOptRow').find('.ui-helper-hidden-accessible').css({left: 'auto', top: 'auto'});
	
	jQuery('#toeModActivationPopupFormCsp').submit(function(){
		  jQuery(this).sendFormCsp({
			  msgElID: 'toeModActivationPopupMsgCsp',
			  onSuccess: function(res){
				  if(res && !res.error) {
					  var goto = jQuery('#toeModActivationPopupFormCsp').find('input[name=goto]').val();
					  if(goto && goto != '') {
						toeRedirect(goto);  
					  } else
						toeReload();
				  }
			  }
		  });
		  return false;
	  });
	  
	 jQuery('.toeRemovePlugActivationNoticeCsp').click(function(){
		  jQuery(this).parents('.info_box:first').animateRemove();
		  return false;
	  });
	  if(window.location && window.location.href && window.location.href.indexOf('plugins.php')) {
		  if(CSP_DATA.allCheckRegPlugs && typeof(CSP_DATA.allCheckRegPlugs) == 'object') {
			  for(var plugName in CSP_DATA.allCheckRegPlugs) {
				  var plugRow = jQuery('#'+ plugName.toLowerCase())
				  ,	updateMsgRow = plugRow.next('.plugin-update-tr');
				  if(plugRow.size() && updateMsgRow.find('.update-message').size()) {
					  updateMsgRow.find('.update-message').find('a').each(function(){
						  if(jQuery(this).html() == 'update now') {
							  jQuery(this).click(function(){
								  toeShowModuleActivationPopupCsp( plugName, 'activateUpdate', jQuery(this).attr('href') );
								  return false;
							  });
						  }
					  });
				  }
			  }
		  }
	  }
	  cspCheckSiezTplPrevImages();
	  jQuery(window).resize(function(){
		  cspCheckSiezTplPrevImages();
	  });
});
function cspCheckSiezTplPrevImages() {
	if(jQuery('.cspTemplatesList').size()) {
		var wndWidth = jQuery(window).width()
		,	useLargeImg = wndWidth > 1000;
		if(useLargeImg) {
			jQuery('.cspTemplatesList .cspTemplatePrevShell').addClass('cspTemplatePrevShellLarge');
		} else {
			jQuery('.cspTemplatesList .cspTemplatePrevShell').removeClass('cspTemplatePrevShellLarge');
		}
		jQuery('.cspTemplatesList .cspTemplatePrevShell .cspAdminTemplateImgPrev').each(function(){
			var src = jQuery(this).attr('src')
			,	isLarge = strpos(src, '_large.jpg')
			,	srcCahnged = false;
			if(useLargeImg && !isLarge) {
				src = str_replace(src, '.jpg', '_large.jpg');
				srcCahnged = true;
			}
			if(!useLargeImg && isLarge) {
				src = str_replace(src, '_large.jpg', '.jpg');
				srcCahnged = true;
			}
			if(srcCahnged)
				jQuery(this).attr('src', src);
		});
	}
}
function toeShowModuleActivationPopupCsp(plugName, action, goto) {
	action = action ? action : 'activatePlugin';
	goto = goto ? goto : '';
	jQuery('#toeModActivationPopupFormCsp').find('input[name=plugName]').val(plugName);
	jQuery('#toeModActivationPopupFormCsp').find('input[name=action]').val(action);
	jQuery('#toeModActivationPopupFormCsp').find('input[name=goto]').val(goto);
	
	tb_show(toeLangCsp('Activate plugin'), '#TB_inline?width=710&height=220&inlineId=toeModActivationPopupShellCsp', false);
	var popupWidth = jQuery('#TB_ajaxContent').width()
	,	docWidth = jQuery(document).width();
	// Here I tried to fix usual wordpress popup displace to right side
	jQuery('#TB_window').css({'left': Math.round((docWidth - popupWidth)/2)+ 'px', 'margin-left': '0'});
}
function changeAdminFormCsp(formId) {
	if(jQuery.inArray(formId, cspAdminFormChanged) == -1)
		cspAdminFormChanged.push(formId);
}
function changeModeOptionCsp(option, ignoreChangePanelMode) {
	jQuery('.cspAdminOptionRow-template, .cspAdminOptionRow-redirect, .cspAdminOptionRow-sub_notif_end_maint').hide();
	switch(option) {
		case 'coming_soon':
			jQuery('.cspAdminOptionRow-template').show( CSP_DATA.animationSpeed );
			break;
		case 'redirect':
			jQuery('.cspAdminOptionRow-redirect').show( CSP_DATA.animationSpeed );
			break;
		case 'disable':
			jQuery('.cspAdminOptionRow-sub_notif_end_maint').show( CSP_DATA.animationSpeed );
			break;
	}
	if(!ignoreChangePanelMode) {
		// Determine should we show Comin Soon sign in wordpress admin panel or not
		if(option == 'disable' && !jQuery('#wp-admin-bar-comingsoon').hasClass('cspHidden'))
			jQuery('#wp-admin-bar-comingsoon').addClass('cspHidden');
		else if(option != 'disable' && jQuery('#wp-admin-bar-comingsoon').hasClass('cspHidden'))
			jQuery('#wp-admin-bar-comingsoon').removeClass('cspHidden');
	}
}
function setTemplateOptionCsp(code) {
	jQuery('.cspTemplatesList .cspTemplatePrevShell-'+ code).css('opacity', 0.5);
	jQuery.sendFormCsp({
		data: {page: 'options', action: 'save', opt_values: {template: code}, code: 'template', reqType: 'ajax'}
	,	onSuccess: function(res) {
			jQuery('.cspTemplatesList .cspTemplatePrevShell-'+ code).css('opacity', 1);
			if(!res.error) {
				selectTemplateImageCsp(code);
				if(res.data && res.data.new_name) {
					jQuery('.cspAdminTemplateSelectedName').html(res.data.new_name);
				}
				if(res.data.def_options && !getCookieCsp('csp_hide_set_defs_tpl_popup')) {
					askToSetTplDefaults(res.data.def_options);
				}
				
				// This is for style_editor module, it come with pro version.
				// I know that it's better to create events functionality, but unfortunately - I hove no time for this right now.
				if(typeof(toeGetTemplateStyleContentCsp) == 'function') {
					toeGetTemplateStyleContentCsp();
				}
			}
		}
	})
	return false;
}
function toeShowDialogCustomized(element, options) {
	options = jQuery.extend({
		resizable: false
	,	width: 500
	,	height: 300
	,	closeOnEscape: true
	,	open: function(event, ui) {
			jQuery('.ui-dialog-titlebar').css({
				'background-color': '#222222'
			,	'background-image': 'none'
			,	'border': 'none'
			,	'margin': '0'
			,	'padding': '0'
			,	'border-radius': '0'
			,	'color': '#CFCFCF'
			,	'height': '27px'
			});
			jQuery('.ui-dialog-titlebar-close').css({
				'background': 'url("'+ CSP_DATA.cssPath+ 'img/tb-close.png") no-repeat scroll 0 0 transparent'
			,	'border': '0'
			,	'width': '15px'
			,	'height': '15px'
			,	'padding': '0'
			,	'border-radius': '0'
			,	'margin': '-7px 0 0'
			}).html('');
			jQuery('.ui-dialog').css({
				'border-radius': '3px'
			,	'background-color': '#FFFFFF'
			,	'background-image': 'none'
			,	'padding': '1px'
			,	'z-index': '300000'
			});
			jQuery('.ui-dialog-buttonpane').css({
				'background-color': '#FFFFFF'
			});
			jQuery('.ui-dialog-title').css({
				'color': '#CFCFCF'
			,	'font': '12px sans-serif'
			,	'padding': '6px 10px 0'
			});
			if(options.openCallback && typeof(options.openCallback) == 'function') {
				options.openCallback(event, ui);
			}
			jQuery('.ui-widget-overlay').css({
				'z-index': jQuery( event.target ).parents('.ui-dialog:first').css('z-index') - 1
			,	'background-image': 'none'
			});
			if(options.modal && options.closeOnBg) {
				jQuery('.ui-widget-overlay').unbind('click').bind('click', function() {
					jQuery( element ).dialog('close');
				});
			}
		}
	}, options);
	return jQuery(element).dialog(options);
}
function askToSetTplDefaults(def_options) {
	var startHtml = jQuery('#cspAskDefaultModParams').html();
	toeShowDialogCustomized('#cspAskDefaultModParams', {
		openCallback: function() {
			jQuery('.cspTplDefOptionCheckShell').hide().each(function(){
				if(jQuery(this).find('input[type=checkbox]').size()) {
					var data_values = jQuery(this).find('input[type=checkbox]').val().split(',')
					,	showThisOption = false;
					for(var key in def_options) {
						for(var i in data_values) {
							if(data_values[i] == key) {
								showThisOption = true;
								break;
							}
						}
						if(showThisOption)
							break;
					}
					if(showThisOption) {
						var optName = jQuery(this).find('input[type=checkbox]').attr('name');
						if((optName == 'background_color' && (def_options.bg_type == 'color' || def_options.bg_type == 'color_image'))
							|| (optName == 'background_image' && (def_options.bg_type == 'image' || def_options.bg_type == 'color_image'))
							|| (optName != 'background_color' && optName != 'background_image')
						) {
							jQuery(this).show();
						}
					}
				}
			});
			jQuery('.cspDefTplOptCheckbox').find('input[type=checkbox]').unbind('click').bind('click', function(){
				var parentLoaderElement = jQuery(this).parent('.cspDefTplOptCheckbox:first')
				,	sendElement = null;
				parentLoaderElement.showLoaderCsp();
				var afterSaveAction = function() {
					if(sendElement) {
						parentLoaderElement.html( '<img src="'+ CSP_DATA.ok_icon+ '" />' );
						sendElement.unbind('cpsOptSaved', afterSaveAction);
					}
				};
				var customSuccess = false;
				switch(jQuery(this).attr('name')) {
					case 'background_color':
						sendElement = jQuery('#cspColorBgSetDefault');
						break;
					case 'background_image':
						sendElement = jQuery('#cspImgBgSetDefault');
						break;
					case 'logo':
						sendElement = jQuery('#cspLogoSetDefault');
						break;
					case 'fonts':
						sendElement = jQuery('#cspMsgTitleSetDefault, #cspMsgTextSetDefault');
						break;
					case 'slider_images':
						customSuccess = function(data) {
							toeOptSlidesRedraw(data.slides, data.slidesNames);
						};
					default:
						jQuery.sendFormCsp({
							msgElID: parentLoaderElement
						,	data: {page: 'options', action: 'setTplDefault', reqType: 'ajax', code: jQuery(this).val().split(',')}
						,	onSuccess: function(res) {
								if(!res.error) {
									parentLoaderElement.html( '<img src="'+ CSP_DATA.ok_icon+ '" />' );
								}
								if(customSuccess && typeof(customSuccess) == 'function') {
									customSuccess(res.data);
								}
							}
						});
						break;
				}
				if(sendElement) {
					sendElement
						.unbind('cpsOptSaved', afterSaveAction)
						.bind('cpsOptSaved', afterSaveAction)
						.trigger('click');
				}
			});
		}
	,	buttons: {
			'Don\'t show this message again': function() {
				setCookieCsp('csp_hide_set_defs_tpl_popup', true, 300);
				jQuery(this).dialog('close');
			}
		,	Close: function() {
				jQuery(this).dialog('close');
			}
		}
	,	close: function( event, ui ) {
			jQuery('#cspAskDefaultModParams').html( startHtml );
		}
	});
}
function selectTemplateImageCsp(code) {
	jQuery('.cspTemplatesList .cspTemplatePrevShell-existing .button')
			.val(toeLangCsp('Apply'))
			.removeClass('cspTplSelected');
	if(code) {
		jQuery('.cspTemplatesList .cspTemplatePrevShell-'+ code+ ' .button')
			.val(toeLangCsp('Selected'))
			.addClass('cspTplSelected');
	}
}
function changeBgTypeOptionCsp() {
	jQuery('#cspBgTypeStandart-selection, #cspBgTypeColor-selection, #cspBgTypeImage-selection').hide();
	if(jQuery('#cspAdminTemplateOptionsForm [name="opt_values[bg_type]"]:checked').size())
		jQuery('#'+ jQuery('#cspAdminTemplateOptionsForm [name="opt_values[bg_type]"]:checked').attr('id')+ '-selection').show( CSP_DATA.animationSpeed );
}
/* Background image manipulation functions */
function toeOptImgCompleteSubmitNewFile(file, res) {
    toeProcessAjaxResponseCsp(res, 'cspOptImgkMsg');
    if(!res.error) {
        toeOptImgSetImg(res.data.imgPath);
    }
}
function toeOptImgOnSubmitNewFile() {
    jQuery('#cspOptImgkMsg').showLoaderCsp();
}
function toeOptImgSetImg(src) {
	jQuery('#cspOptBgImgPrev').attr('src', src);
}
/* Logo image manipulation functions */
function toeOptLogoImgCompleteSubmitNewFile(file, res) {
    toeProcessAjaxResponseCsp(res, 'cspOptLogoImgkMsg');
    if(!res.error) {
        toeOptLogoImgSetImg(res.data.imgPath);
    }
}
function toeOptLogoImgOnSubmitNewFile() {
    jQuery('#cspOptLogoImgkMsg').showLoaderCsp();
}
function toeOptLogoImgSetImg(src) {
	jQuery('#cspOptLogoImgPrev').attr('src', src);
}
