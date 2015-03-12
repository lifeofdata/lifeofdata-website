<!-- Below is solution for site broke because iframe of share button has a very big height -->
<style type="text/css">
	.fb-follow { 
		width: 80px; 
	}
	.fb-follow span, 
	.fb-follow iframe {
		width: 80px! important;
		height: 25px! important;
	}
</style>
<div class="fb-follow" 
	 data-href="<?php echo (strpos($this->optsModel->get('soc_facebook_follow_profile'), 'http') === 0 ? $this->optsModel->get('soc_facebook_follow_profile') : 'https://www.facebook.com/'. $this->optsModel->get('soc_facebook_follow_profile'))?>" 
	 data-show-faces="<?php echo ($this->optsModel->isEmpty('soc_facebook_follow_faces') ? 'false' : 'true')?>" 
	 data-colorscheme="<?php echo $this->optsModel->get('soc_facebook_follow_color_scheme')?>" 
	 data-font="<?php echo $this->optsModel->get('soc_facebook_follow_font')?>" 
	 data-layout="<?php echo $this->optsModel->get('soc_facebook_follow_layout')?>"
	 data-width="<?php echo $this->optsModel->get('soc_facebook_follow_width')?>"></div>