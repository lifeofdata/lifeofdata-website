jQuery(document).ready(function(){
	jQuery('#cspSubscribeForm').submit(function(){
		jQuery(this).sendFormCsp({
			msgElID: 'cspSubscribeCreateMsg'
		,	onSuccess: function(res) {
				if(!res.error) {
					jQuery('#cspSubscribeForm').clearForm();
				}
				// We can define this custom function in our templates
				if(typeof(cspSubscribeAfterSubmit) == 'function') {
					cspSubscribeAfterSubmit(res);
				}
			}
		});
		return false;
	});
});