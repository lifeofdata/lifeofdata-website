<?php
class subscribeViewCsp extends viewCsp {
	public function getAdminOptions() {
		$subOptions = frameCsp::_()->getModule('options')->getModel()->getByCategories('Subscribe');
		$emailEditTpls = array();
		$emailTpls = frameCsp::_()->getModule('messenger')->getController()->getModel('email_templates')->get(array('module' => 'subscribe'));
		if(!empty($emailTpls)) {
			foreach($emailTpls as $tpl) {
				$emailEditTpls[] = array(
					'label' => $tpl['label'], 
					'content' => frameCsp::_()->getModule('messenger')->getController()->getView()->getOneEmailTplEditor(array('tplData' => $tpl)),
				);
			}
		}
		$this->assign('subOptions', $subOptions['opts']);
		$this->assign('optModel',	frameCsp::_()->getModule('options')->getModel());
		$this->assign('emailEditTpls', $emailEditTpls);
		$this->assign('isProVersion',(bool)frameCsp::_()->getModule('license'));
		//$this->assign('subscriberSynchronizesystem', $subscriberSynchronizesystem);
		return parent::getContent('subscribeAdminOptions');
	}
	public function getUserForm() {
		$this->assign('enterEmailMsg', langCsp::_(frameCsp::_()->getModule('options')->get('sub_enter_email_msg')));
		$this->assign('sub_name_enable', langCsp::_(frameCsp::_()->getModule('options')->get('sub_name_enable')));
		return parent::getContent('subscribeUserForm');
	}
	public function getMetaBox() {
		global $post;
		$this->assign('subCheckedNotify', (int) frameCsp::_()->getModule('options')->get('sub_checked_notify'));
		$this->assign('post', $post);
		parent::display('subMetaBox');
	}
	public function getSubscribeModAdminOptions($code) {
		frameCsp::_()->addScript('adminSubscribeModulesOptionsCsp', $this->getModule()->getModPath(). 'js/admin.subscribeModulesAdmin.options.js');
		$this->assign('optsModel', frameCsp::_()->getModule('options')->getModel());
		$this->assign('code', $code);
		$this->assign('subMod', frameCsp::_()->getModule($code));
		return parent::getContent('subscribeModAdminOptions');
	}
}
