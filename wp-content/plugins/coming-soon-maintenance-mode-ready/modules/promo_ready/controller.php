<?php
class promo_readyControllerCsp extends controllerCsp {
    public function welcomePageSaveInfo() {
		$res = new responseCsp();
		installerCsp::setUsed();
		if($this->getModel()->welcomePageSaveInfo(reqCsp::get('get'))) {
			$res->addMessage(langCsp::_('Information was saved. Thank you!'));
		} else {
			$res->pushError($this->getModel()->getErrors());
		}
		$originalPage = reqCsp::getVar('original_page');
		$returnArr = explode('|', $originalPage);
		$return = $this->getModule()->decodeSlug(str_replace('return=', '', $returnArr[1]));
		$return = admin_url( strpos($return, '?') ? $return : 'admin.php?page='. $return);
		redirect($return);
		//return $res->ajaxExec();
	}
	/**
	 * @see controller::getPermissions();
	 */
	public function getPermissions() {
		return array(
			CSP_USERLEVELS => array(
				CSP_ADMIN => array('welcomePageSaveInfo')
			),
		);
	}
}