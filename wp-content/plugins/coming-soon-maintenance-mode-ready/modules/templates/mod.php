<?php
class templatesCsp extends moduleCsp {
    /**
     * Returns the available tabs
     * 
     * @return array of tab 
     */
    protected $_styles = array();
    public function getTabs(){
        $tabs = array();
        $tab = new tabCsp(langCsp::_('Templates'), $this->getCode());
        $tab->setView('templatesTab');
		$tab->setSortOrder(1);
        $tabs[] = $tab;
        return $tabs;
    }
    public function init() {
		$isAdminPlugPage = frameCsp::_()->isAdminPlugPage();
		$isPluginsPage = utilsCsp::isPluginsPage();
        if (is_admin()) {
			if($isAdminPlugPage || $isPluginsPage) {
				frameCsp::_()->addScript('jquery');
				frameCsp::_()->addScript('jquery-ui-tabs', '', array('jquery'));
				frameCsp::_()->addScript('jquery-ui-dialog', '', array('jquery'));
				frameCsp::_()->addScript('jquery-ui-button', '', array('jquery'));

				frameCsp::_()->addScript('farbtastic');

				frameCsp::_()->addScript('commonCsp', CSP_JS_PATH. 'common.js');
				frameCsp::_()->addScript('coreCsp', CSP_JS_PATH. 'core.js');
		
				frameCsp::_()->addScript('adminOptionsCsp', CSP_JS_PATH. 'admin.options.js');
				frameCsp::_()->addScript('ajaxupload', CSP_JS_PATH. 'ajaxupload.js');
				frameCsp::_()->addScript('postbox', get_bloginfo('wpurl'). '/wp-admin/js/postbox.js');
				add_action('wp_enqueue_scripts', array($this, 'addThickbox'));
				
				$ajaxurl = admin_url('admin-ajax.php');
				if(frameCsp::_()->getModule('options')->get('ssl_on_ajax')) {
					$ajaxurl = uriCsp::makeHttps($ajaxurl);
				}
				$jsData = array(
					'siteUrl'					=> CSP_SITE_URL,
					'imgPath'					=> CSP_IMG_PATH,
					'cssPath'					=> CSP_CSS_PATH,
					'loader'					=> CSP_LOADER_IMG, 
					'close'						=> CSP_IMG_PATH. 'cross.gif', 
					'ajaxurl'					=> $ajaxurl,
					'animationSpeed'			=> frameCsp::_()->getModule('options')->get('js_animation_speed'),
					'siteLang'					=> langCsp::getData(),
					'options'					=> frameCsp::_()->getModule('options')->getAllowedPublicOptions(),
					'CSP_CODE'					=> CSP_CODE,
					'ball_loader'				=> CSP_IMG_PATH. 'ajax-loader-ball.gif',
					'ok_icon'					=> CSP_IMG_PATH. 'ok-icon.png',
				);
				$jsData['allCheckRegPlugs']	= modInstallerCsp::getCheckRegPlugs();
				
				$jsData = dispatcherCsp::applyFilters('jsInitVariables', $jsData);
				frameCsp::_()->addJSVar('coreCsp', 'CSP_DATA', $jsData);
				
				$this->_styles = array(
					'styleCsp'				=> array('path' => CSP_CSS_PATH. 'style.css'), 
					'adminStylesCsp'		=> array('path' => CSP_CSS_PATH. 'adminStyles.css'), 

					'jquery-tabs'			=> array('path' => CSP_CSS_PATH. 'jquery-tabs.css'),
					'jquery-buttons'		=> array('path' => CSP_CSS_PATH. 'jquery-buttons.css'),
					'wp-jquery-ui-dialog'	=> array(),
					'farbtastic'			=> array(),
					// Our corrections for ui dialog
					'jquery-dialog'			=> array('path' => CSP_CSS_PATH. 'jquery-dialog.css'),
				);
				$defaultPlugTheme = frameCsp::_()->getModule('options')->get('default_theme');
				foreach($this->_styles as $s => $sInfo) {
					if(isset($sInfo['for'])) {
						if(($sInfo['for'] == 'frontend' && is_admin()) || ($sInfo['for'] == 'admin' && !is_admin()))
							continue;
					}
					$canBeSubstituted = true;
					if(isset($sInfo['substituteFor'])) {
						switch($sInfo['substituteFor']) {
							case 'frontend':
								$canBeSubstituted = !is_admin();
								break;
							case 'admin':
								$canBeSubstituted = is_admin();
								break;
						}
					}
					if($canBeSubstituted && file_exists(CSP_TEMPLATES_DIR. $defaultPlugTheme. DS. $s. '.css')) {
						frameCsp::_()->addStyle($s, CSP_TEMPLATES_PATH. $defaultPlugTheme. '/'. $s. '.css');
					} elseif($canBeSubstituted && file_exists(utilsCsp::getCurrentWPThemeDir(). 'csp'. DS. $s. '.css')) {
						frameCsp::_()->addStyle($s, utilsCsp::getCurrentWPThemePath(). '/toe/'. $s. '.css');
					} elseif(!empty($sInfo['path'])) {
						frameCsp::_()->addStyle($s, $sInfo['path']);
					} else {
						frameCsp::_()->addStyle($s);
					}
				}
			}
		}
        parent::init();
    }
	public function addThickbox() {
		add_thickbox();
	}
}
