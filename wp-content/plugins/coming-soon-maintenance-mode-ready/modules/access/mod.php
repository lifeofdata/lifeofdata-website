<?php
class accessCsp extends moduleCsp {
 public function init() {
		dispatcherCsp::addFilter('adminOptionsTabs', array($this, 'addOptionsTab'));
		dispatcherCsp::addFilter('canAccessSite', array($this, 'accessFilter'));
	}
	
	public function addOptionsTab($tabs) {
		frameCsp::_()->addScript('adminAccessOptions', $this->getModPath(). 'js/admin.access.options.js');
		$tabs['cspAccess'] = array(
		   'title' => 'Access', 'content' => $this->getController()->getView()->getAdminOptions(), 'sort_order' => 90
		);
		return $tabs;
	}  
	
	public function getList() {
			$res[] = $this->getController()->getView('ipBlock');
			$res[] = $this->getController()->getView('userBlock');
		return $res;
	}
	
	public function accessFilter() {
		return $this->getController()->getModel()->accessFilter();
	}
}

