jQuery(document).ready(function(){
	jQuery('.cspEngineSubscribeOptionsForm').submit(function(){
		var code = jQuery(this).attr('code');
		jQuery(this).sendFormCsp({
			msgElID: jQuery(this).find('.cspEngineSubscribeOptionsFormMsg:first')
		,	onSuccess: function(res) {
				var callbackName = 'submitSaveSettings_'+ code;
				if(typeof window[callbackName] === 'function') {
					callUserFuncArray(window[callbackName], [res]);
				}
			}
		});
		return false;
	});
	jQuery('.cspEngineSubscribeSyncButt').click(function(){
		cspEngineSubscribeSync('sync', this);
		return false;
	});
	jQuery('.cspEngineSubscribeExportButt').click(function(){
		cspEngineSubscribeSync('export', this);
		return false;
	});
	jQuery('.cspEngineSubscribeImportButt').click(function(){
		cspEngineSubscribeSync('import', this);
		return false;
	});
});
function cspEngineSubscribeSync(syncType, button) {
	jQuery.sendFormCsp({
		msgElID: jQuery(button).parents('*:first').find('.cspEngineSubscribeSyncMsg:first')
	,	data: {page: 'subscribe', action: 'syncWithEngine', reqType: 'ajax', syncType: syncType, engine: jQuery(button).attr('code')}
	,	onSuccess: function(res) {
			if(!res.error) {
				getSubersListCsp();
			}
		}
	});
}