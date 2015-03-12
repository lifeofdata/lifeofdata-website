<?php
class adminmenuViewCsp extends viewCsp {
    protected $_file = '';
    /**
     * Array for standart menu pages
     * @see initMenu method
     */
    protected $_mainSlug = 'ready-coming-soon-page';
    public function init() {
        $this->_file = __FILE__;
		//
        add_action('admin_menu', array($this, 'initMenu'), 9);
        parent::init();
    }
    public function initMenu() {
		$mainSlug = dispatcherCsp::applyFilters('adminMenuMainSlug', $this->_mainSlug);
		$mainMenuPageOptions = array(
			'page_title' => langCsp::_('Ready! Coming Soon'), 
			'menu_title' => langCsp::_('Ready! Coming Soon'), 
			'capability' => 'manage_options',
			'menu_slug' => $mainSlug,
			'function' => array(frameCsp::_()->getModule('options')->getView(), 'getAdminPage'));
		$mainMenuPageOptions = dispatcherCsp::applyFilters('adminMenuMainOption', $mainMenuPageOptions);
        add_menu_page($mainMenuPageOptions['page_title'], $mainMenuPageOptions['menu_title'], $mainMenuPageOptions['capability'], $mainMenuPageOptions['menu_slug'], $mainMenuPageOptions['function']);
    }
    public function getFile() {
        return $this->_file;
    }
	public function getMainSlug() {
		return $this->_mainSlug;
	}
    /*public function getOptions() {
        return $this->_options;
    }*/
}