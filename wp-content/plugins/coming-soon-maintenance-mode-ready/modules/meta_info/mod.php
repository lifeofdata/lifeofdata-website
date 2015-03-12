<?php
class meta_infoCsp extends moduleCsp {
	public function init() {
		dispatcherCsp::addFilter('adminOptionsTabs', array($this, 'addOptionsTab'));
		dispatcherCsp::addAction('tplHeaderBegin',array($this,'showFavico'));
		dispatcherCsp::addAction('tplBodyEnd',array($this,'GoogleAnalitics'));
		dispatcherCsp::addAction('in_admin_footer',array($this,'showPluginFooter'));
	}
	public function addOptionsTab($tabs) {
		frameCsp::_()->addScript('adminMetaOptions', $this->getModPath(). 'js/admin.meta_info.options.js');
		frameCsp::_()->addScript('', $this->getModPath(). 'js/favico.js');
		$tabs['cspMetaIcons'] = array(
		   'title' => 'SEO / Meta Info', 'content' => $this->getController()->getView()->getAdminOptions(), 'sort_order' => 100
		);
		return $tabs;
	}
	public function getList() {
		return dispatcherCsp::applyFilters('metaTagsList', array(
			'meta_title' => array(
				'label'				=> 'Title',
				'optsTplEngine'		=> array($this->getController()->getView(), 'getTitleOpts'),
			),
			'meta_desc' => array(
				'label'				=> 'Description',
				'optsTplEngine'		=> array($this->getController()->getView(), 'getDescOpts'),
			),
			'meta_keywords' => array(
				'label'				=> 'Keywords',
				'optsTplEngine'		=> array($this->getController()->getView(), 'getKeywordsOpts'),
			)
			,
			'google_analitics'=>array(
				'label'			=> 'Google Analitics Code',
				'optsTplEngine'	=>	array($this->getController()->getView(),
				'getGoogleAnaliticsOpts'),
			),
			'favico'=>array(
				'label'			=>	'Favico for site',
				'optsTplEngine'	=>	array($this->getController()->getView(),
				'getFavicoOpts'),				
			)
		));
	}
	public static function showFavico(){
		echo '<link href="'.frameCsp::_()->getModule('options')->getFavicoFullPath().' " rel="shortcut icon">';

		echo '<!--[if IE]><link rel="SHORTCUT ICON" type="image/x-icon" href="'.frameCsp::_()->getModule('options')->getFavicoFullPath() .'"/><![endif]-->';
	}
	public static function GoogleAnalitics(){
		echo frameCsp::_()->getModule('options')->getController()->getModel()->get('google_analitics');
	}

	public static function showPluginFooter(){

	}
}