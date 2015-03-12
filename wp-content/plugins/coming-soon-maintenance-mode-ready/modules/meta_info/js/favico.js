function toggleLoader(){
	jQuery('.showUploadedFavico .loader').toggle();
}
function favicoPreLoading(){
	toggleLoader();
}
function favicoUploadComplete(file, resp){
	
	var response = JSON.parse(resp);
	
	if(!response.error){
		toggleLoader();
		jQuery('.showUploadedFavico img').attr('src',response.data.imgPath);
		img_attr = response.data.imgPath.split("/");
		jQuery('#favico_value_input').val(img_attr[img_attr.length-1]);
		jQuery('.showUploadedFavico #cspAdminMetaOptionsMsg').html("Done<br/>")
		console.log(response.data); 
		jQuery("#removeFavicoImage").show();
	}
}
jQuery('#removeFavicoImage').click(function(){
	toggleLoader();
	jQuery('.showUploadedFavico img').attr('src',"");
	jQuery('#favico_value_input').val("");
	toggleLoader();
	jQuery(this).hide();
})