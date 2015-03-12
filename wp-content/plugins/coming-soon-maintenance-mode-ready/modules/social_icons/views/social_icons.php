<?php
class social_iconsViewCsp extends viewCsp {
	private $_googleSdkIncluded = false;
	public function getAdminOptions() {
		$iconsList = $this->getModule()->getList();
		foreach($iconsList as $key => $icon) {
			if(isset($icon['optsTplEngine']) && !empty($icon['optsTplEngine']))
				$iconsList[$key]['adminOptsContent'] = call_user_func($icon['optsTplEngine']);
		}
		$this->assign('iconsList', $iconsList);
		$this->assign('optsModel', frameCsp::_()->getModule('options')->getController()->getModel());
		return parent::getContent('socAdminOptions');
	}
	public function getFbOpts() {
		$this->assign('optsModel', frameCsp::_()->getModule('options')->getController()->getModel());
		return parent::getContent('socFbOpts');
	}
	public function getFbButtons() {
		$out = array();
		$optsModel = frameCsp::_()->getModule('options')->getController()->getModel();
		$this->assign('optsModel', $optsModel);
		$this->assign('currentUrl', get_bloginfo('wpurl'). $_SERVER['REQUEST_URI']);
		if(!$optsModel->isEmpty('soc_facebook_enable_link'))
			$out['link'] = parent::getContent('socFbLink');
		if(!$optsModel->isEmpty('soc_facebook_enable_share'))
			$out['share'] = parent::getContent('socFbShare');
		if(!$optsModel->isEmpty('soc_facebook_enable_like'))
			$out['like'] = parent::getContent('socFbLike');
		if(!$optsModel->isEmpty('soc_facebook_enable_follow'))
			$out['follow'] = parent::getContent('socFbFollow');
		if(!empty($out)) {
			// If there are some content - include sdk scripts, let's make it only one time for all fb buttons
			$out['sdk'] = parent::getContent('socFbSdk');
		}
		return $out;
	}
	public function getFrontendContent() {
		$iconsList = $this->getModule()->getList();
		foreach($iconsList as $key => $icon) {
			if(isset($icon['engine']) && !empty($icon['engine'])) {
				$iconsData = call_user_func($icon['engine']);
				if(!empty($iconsData)) {
					$iconsList[$key]['htmlContent'] = $iconsData;
				}
			}
		}
		$this->assign('iconsList', $iconsList);
		return parent::getContent('socFrontendIcons');
	}
	public function getTwOpts() {
		$this->assign('optsModel', frameCsp::_()->getModule('options')->getController()->getModel());
		return parent::getContent('socTwOpts');
	}
	public function getTwButtons() {
		$out = array();
		$optsModel = frameCsp::_()->getModule('options')->getController()->getModel();
		$this->assign('optsModel', $optsModel);
		$this->assign('currentUrl', get_bloginfo('wpurl'). $_SERVER['REQUEST_URI']);
		$this->assign('langIso2Code', substr(CSP_WPLANG, 0, 2));
		if(!$optsModel->isEmpty('soc_tw_enable_link'))
			$out['link'] = parent::getContent('socTwLink');
		if(!$optsModel->isEmpty('soc_tw_enable_tweet'))
			$out['like'] = parent::getContent('socTwTweet');
		if(!$optsModel->isEmpty('soc_tw_enable_follow'))
			$out['follow'] = parent::getContent('socTwFollow');
		if(!empty($out)) {
			// If there are some content - include sdk scripts, let's make it only one time for all fb buttons
			$out['sdk'] = parent::getContent('socTwSdk');
		}
		return $out;
	}
	public function getGpOpts() {
		$this->assign('optsModel', frameCsp::_()->getModule('options')->getController()->getModel());
		return parent::getContent('socGpOpts');
	}
	public function getYtOpts() {
		$this->assign('optsModel', frameCsp::_()->getModule('options')->getController()->getModel());
		return parent::getContent('socYtOpts');
	}
	public function getYtButtons() {
		$out = array();
		$optsModel = frameCsp::_()->getModule('options')->getController()->getModel();
		$this->assign('optsModel', $optsModel);
		if(!$optsModel->isEmpty('soc_yt_enable_link'))
			$out['link'] = parent::getContent('socYtLink');
		if(!$optsModel->isEmpty('soc_yt_enable_subscribe'))
			$out['follow'] = parent::getContent('socYtSubscribe');
		if(!empty($out)) {
			// If there are some content - include sdk scripts, let's make it only one time for all yt buttons
			$out['sdk'] = $this->_getGoogleJsSdk();
		}
		return $out;
	}
	public function getImOpts() {
		$this->assign('optsModel', frameCsp::_()->getModule('options')->getController()->getModel());
		return parent::getContent('socImOpts');
	}
	public function getImButtons() {
		$out = array();
		$optsModel = frameCsp::_()->getModule('options')->getController()->getModel();
		$this->assign('optsModel', $optsModel);
		if(!$optsModel->isEmpty('soc_im_enable_link'))
			$out['link'] = parent::getContent('socImLink');
		return $out;
	}
	public function getGpButtons() {
		$out = array();
		$optsModel = frameCsp::_()->getModule('options')->getController()->getModel();
		$this->assign('optsModel', $optsModel);
		$this->assign('currentUrl', get_bloginfo('wpurl'). $_SERVER['REQUEST_URI']);
		if(!$optsModel->isEmpty('soc_gp_enable_link'))
			$out['link'] = parent::getContent('socGpLink');
		if(!$optsModel->isEmpty('soc_gp_enable_badge'))
			$out['follow'] = parent::getContent('socGpBadge');
		if(!$optsModel->isEmpty('soc_gp_enable_like'))
			$out['like'] = parent::getContent('socGpLike');
		if(!empty($out)) {
			// If there are some content - include sdk scripts, let's make it only one time for all gp buttons
			$out['sdk'] = $this->_getGoogleJsSdk();
		}
		return $out;
	}
	/**
	 * This will return Google JS SDK 'plus one".
	 * We need this in separate method as at least 2 soc. icons - google+ and youtube - use same SDK - to include it only once at page.
	 */
	private function _getGoogleJsSdk() {
		if(!$this->_googleSdkIncluded) {
			$this->_googleSdkIncluded = true;
			$this->assign('langCode', get_bloginfo('language'));
			return parent::getContent('socGpSdk');;
		}
		return '';
	}
	
	public function getSocImgPath($imgName) {
		$template = frameCsp::_()->getModule('options')->get('template');
		if(!empty($template) && frameCsp::_()->getModule($template)) {
			if(utilsCsp::fileExists(frameCsp::_()->getModule($template)->getModDir(). 'img'. DS. $imgName))
				return frameCsp::_()->getModule($template)->getModPath(). 'img/'. $imgName;
		}
		return uriCsp::_(CSP_IMG_PATH. $imgName);
	}
}