<?php
class promo_readyViewCsp extends viewCsp {
    public function displayAdminFooter() {
        parent::display('adminFooter');
    }
	public function getPromoTabContent($tabCode, $tabData) {
		$this->assign('tabCode', $tabCode);
		$this->assign('tabData', $tabData);
		return parent::getContent('adminPromoTabContent');
	}
	public function showWelcomePage() {
		$this->assign('askOptions', array(
			1 => array('label' => 'Google'),
			2 => array('label' => 'Wordpress.org'),
			3 => array('label' => 'Refer a friend'),
			4 => array('label' => 'Find on the web'),
			5 => array('label' => 'Other way...'),
		));
		parent::display('welcomePage');
	}
}
