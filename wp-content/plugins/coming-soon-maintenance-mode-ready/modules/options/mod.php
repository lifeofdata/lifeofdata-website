<?php
class optionsCsp extends moduleCsp {
	protected $_uploadDir = 'csp';
	protected $_bgImgSubDir = 'bg_img';
	protected $_bgLogoImgSubDir = 'logo_img';
	protected $_favicoDir = 'favico';

    /**
     * Returns the available tabs
     * 
     * @return array of tab 
     */
    public function getTabs(){
        $tabs = array();
        $tab = new tabCsp(langCsp::_('General'), $this->getCode());
        $tab->setView('optionTab');
        $tab->setSortOrder(-99);
        $tabs[] = $tab;
        return $tabs;
    }
    /**
     * This method provides fast access to options model method get
     * @see optionsModel::get($d)
     */
    public function get($d = array()) {
        return $this->getController()->getModel()->get($d);
    }
	/**
     * This method provides fast access to options model method get
     * @see optionsModel::get($d)
     */
	public function isEmpty($d = array()) {
		return $this->getController()->getModel()->isEmpty($d);
	}
	
	public function getUploadDir() {
		return $this->_uploadDir;
	}
	public function getBgImgDir() {
		return $this->_uploadDir. DS. $this->_bgImgSubDir;
	}
	public function getBgImgFullDir() {
		return utilsCsp::getUploadsDir(). DS. $this->getBgImgDir(). DS. $this->get('bg_image');
	}
	public function getBgImgFullPath() {
		return utilsCsp::getUploadsPath(). '/'. $this->_uploadDir. '/'. $this->_bgImgSubDir. '/'. $this->get('bg_image');
	}
	
	public function getLogoImgDir() {
		return $this->_uploadDir. DS. $this->_bgLogoImgSubDir;
	}

	public function getLogoImgFullDir() {
		return utilsCsp::getUploadsDir(). DS. $this->getLogoImgDir(). DS. $this->get('logo_image');
	}
	
	public function getLogoImgFullPath() {
		return utilsCsp::getUploadsPath(). '/'. $this->_uploadDir. '/'. $this->_bgLogoImgSubDir. '/'. $this->get('logo_image');
	}
	
	public function getFavicoDir(){
		return $this->_uploadDir. DS. $this->_favicoDir;		
	}
	
	public function getFavicoFullDir() {
		return utilsCsp::getUploadsDir(). DS. $this->getFavicoDir(). DS. $this->get('favico');		
	}
	public function getFavicoFullPath() {
		return utilsCsp::getUploadsPath(). '/'. $this->_uploadDir. '/'. $this->_favicoDir. '/'. $this->get('favico');		
	}
	public function getAllowedPublicOptions() {
		$res = array();
		$alowedForPublic = array('mode', 'template');
		$allOptions = $this->getModel()->getByCode();
		foreach($alowedForPublic as $code) {
			if(isset($allOptions[ $code ]))
				$res[ $code ] = $allOptions[ $code ];
		}
		return $res;
	}	
}

