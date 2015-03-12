<?php
	$socYtAccount = $this->optsModel->get('soc_yt_account');
	$href = strpos($socYtAccount, 'http') !== false ? $socYtAccount : 'http://www.youtube.com/user/'. $socYtAccount;
?>
<a href="<?php echo $href?>">
	<img src="<?php echo $this->getSocImgPath('Youtube-link.png')?>" />
</a>