<?php
abstract class subscribeEngineViewCsp extends viewCsp {
	public function getAdminView() {
		return frameCsp::_()->getModule('subscribe')->getView()->getSubscribeModAdminOptions( $this->getCode() );
	}
}
