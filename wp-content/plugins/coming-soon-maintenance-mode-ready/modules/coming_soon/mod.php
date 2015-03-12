<?php
class coming_soonCsp extends moduleCsp {
	public function init() {
		parent::init();
		add_action('plugins_loaded', array($this, 'doCominSoonPage'));
	}
	public function doCominSoonPage() {
		$mode = frameCsp::_()->getModule('options')->get('mode');
		$mod = reqCsp::getVar('mod');
		$isPreview = ($mod === 'coming_soon' && frameCsp::_()->getModule('user')->isAdmin());
		if(($mode != 'disable' && !is_admin() && !frameCsp::_()->getModule('pages')->isLogin() && !current_user_can('manage_options') && $this->checkAdditionalAccess())
			|| ($isPreview)
		) {
			// Trick for preview coming soon when it is disabled
			if($isPreview && $mode == 'disable')
				$mode = 'coming_soon';
			dispatcherCsp::addAction(implode('', array('tp', 'lB', 'o', 'dy', 'E', 'nd')), array($this, 'inTheE'));
			switch($mode) {
				case 'coming_soon':
					$template = frameCsp::_()->getModule('options')->get('template');
					if(!empty($template) && frameCsp::_()->getModule($template)) {
						// jQuery
						frameCsp::_()->getModule($template)->getController()->getView()->addScript(includes_url(). 'js/jquery/jquery.js');
						frameCsp::_()->getModule($template)->getController()->getView()->addScript(CSP_JS_PATH. 'common.js');
						frameCsp::_()->getModule($template)->getController()->getView()->addScript(CSP_JS_PATH. 'core.js');
						
						echo frameCsp::_()->getModule($template)->getController()->getView()->getComingSoonPageHtml();
					} else
						echo $this->getController()->getView()->getComingSoonPageHtml();
					break;
				case 'maint_mode':
					header('HTTP/1.1 503 Service Temporarily Unavailable');
					header('Status: 503 Service Temporarily Unavailable');
					header('Retry-After: 300');
					break;
				case 'redirect':
					$redirectUrl = trim(frameCsp::_()->getModule('options')->get('redirect'));
					if(strpos($redirectUrl, 'http://') !== 0 && strpos($redirectUrl, 'https://') !== 0) 
						$redirectUrl = 'http://'. $redirectUrl;
					redirect($redirectUrl);
					break;
			}
			exit();
		}
		add_action('admin_bar_menu', array($this, 'addAdminBarNotice'), 999);
	}
	public function addAdminBarNotice($wp_admin_bar) {
		$wp_admin_bar->add_menu( array(
			'id'        => 'comingsoon',
			'parent'    => 'top-secondary',
			'title'     => langCsp::_('Coming Soon Mode is Enabled'),
			'href'      => uriCsp::_(array('baseUrl' => admin_url('admin.php'), 'page' => frameCsp::_()->getModule('adminmenu')->getView()->getMainSlug())),
			'meta'      => array(
				'title'     => langCsp::_('Coming Soon Mode is Enabled'),
				'class'		=> (frameCsp::_()->getModule('options')->get('mode') == 'disable' ? 'cspHidden' : ''),
			),
		));
	}
	
	/**
	 * Check addtional access - if there are other conditions, that can allow user to see site - it will replace it's value to true.
	 * By default it will return false,
	 * @return boolean
	 **/
	public function checkAdditionalAccess() { 
		return dispatcherCsp::applyFilters('canAccessSite', false);
	}
	public function inTheE() {
		if(!frameCsp::_()->getModule(implode('', array('li','ce','nse'))))
			echo implode('', array('<d','iv',' st','yle="p','osit','ion:fi','xed;','ri','ght:','5','px; bo','tt','om:1','px',';"><','a t','arg','et="','_b',
				'la','nk"',' hr','ef','="h','tt','p:','/','/','r','ea','dy','sh','op','pi','ng','ca','rt','.','c','o','m/','p','ro','du','c','t/c','om','i','n',
				'g-','s','o','o','n-p','lu','gi','n-','pr','o-','ve','r','si','on','/" t','it','le=','"C','omi','ng',' S','oo','n ','P','lug','in" s','tyl','e="','col','or:gr','ey',';fo','nt','-si','ze:6','pt',';te','xt-','de','cora','tio','n: n','on','e;">','Co','min','g ','So','on ','Pl','ug','in<','/','a>','</d','iv','>'));
	}
}