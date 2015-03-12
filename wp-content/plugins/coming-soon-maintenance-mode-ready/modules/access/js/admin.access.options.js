jQuery(document).ready(function(){
	
 jQuery('#cspAdminAccessFormIp').submit(function(){
  jQuery(this).sendFormCsp({
    msgElID: 'MSG_EL_ID_Ip',
    onSuccess: function(res) {
		if (!res.error) {
			jQuery('#cspAdminAccessFormIp').clearForm();
			var addedElement = '<option value="' + res.data[0] +'">' + res.data[1] + '</option>';
			jQuery("select[name=selectlistCspIp\\[\\]]").prepend(addedElement);
		}
    }
  });
  return false;
 });
 
  jQuery('#cspAdminAccessFormUser').submit(function(){
	jQuery(this).sendFormCsp({
	  msgElID: 'MSG_EL_ID_User',
	  onSuccess: function(res) {
		 if (!res.error) {
			jQuery('#cspAdminAccessFormUser').clearForm();
			var addedElement = '<option value="' + res.data[0] +'">' + res.data[1] + '</option>';
			jQuery("select[name=selectlistCspUser\\[\\]]").prepend(addedElement);
		}
	  }
	});
	return false;
  });
  
  jQuery('#cspAdminAccessFormRole').submit(function(){
	jQuery(this).sendFormCsp({
	  msgElID: 'MSG_EL_ID_Role'
	});
	return false;
  });
  
  jQuery("#delIpCsp").click(function(){
	 delElement('Ip');
  });
  
  jQuery("#delUserCsp").click(function(){
	 delElement('User');
  });
  
  function delElement(ch)
  {
	   var arrId;
		jQuery("select[name=selectlistCsp"+ch+"\\[\\]]").each(function(){
			arrId = jQuery(this).val();
		});
		  
	  if (arrId) {
		jQuery(this).sendFormCsp({
		  msgElID: 'MSG_EL_ID_'+ch,
		  data: {page: 'access', action: 'delete'+ch, reqType: 'ajax', arrElement: arrId },
		  onSuccess: function(res) {
			  if (res.data !== '') {
				res.data.forEach(function(entry) {
					jQuery("select[name=selectlistCsp"+ch+"\\[\\]] option[value="+entry+"]").remove();
				});
			  }
		  }
		});
	  }
  }
  
});

/*alert(res.errors);
alert(res.messages);*/