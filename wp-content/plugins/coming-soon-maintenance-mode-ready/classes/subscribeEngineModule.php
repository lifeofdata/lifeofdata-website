<?php
/**
 * Abstract class for subscribe modules engine
 */
abstract class subscribeEngineModuleCsp extends moduleCsp {
	public function init() {
		parent::init();
		dispatcherCsp::addFilter('adminOptionsTabs', array($this, 'addAdminOptionsTab'));
	}
	public function addAdminOptionsTab($tabsData) {
		$tabsData['csp_'. $this->getCode(). '_Setup'] = array(
			'title'		=> $this->getLabel(),
			'content'	=> $this->getController()->getView()->getAdminView(),
			'sort_order' => 50,
		);
		return $tabsData;
	}
	public function install() {
		parent::install();
		frameCsp::_()->getTable('options')->insert(array(
			'code' => $this->getCode(). '_enabled',
			'value' => '',
			'label' => langCsp::_($this->getLabel(). ' Enabled'),
			'cat_id' => 2,
		));
		// unused for now
		/*frameCsp::_()->getTable('options')->insert(array(
			'code' => $this->getCode(). '_auto_subscriber_create',
			'value' => '1',
			'label' => langCsp::_($this->getLabel(). ' Auto subscribers create'),
			'cat_id' => 2,
		));*/
		frameCsp::_()->getTable('options')->insert(array(
			'code' => $this->getCode(). '_is_main',
			'value' => '',
			'label' => langCsp::_($this->getLabel(). ' is Main'),
			'cat_id' => 2,
		));
	}
	public function export() {}
	public function import() {}
	public function subscribe($params) {}
	public function addRecord($params) {}
}