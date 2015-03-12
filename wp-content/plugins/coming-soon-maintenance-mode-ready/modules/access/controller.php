<?php
class accessControllerCsp extends controllerCsp {
	
   public function saveIp() {
	   $res = new responseCsp();
		  if(($ipAddressData = $this->getModel()->saveIp(reqCsp::get('post'))) !== false) {
			$res->addMessage(langCsp::_('Ip address added'));
			$res->addData($ipAddressData);
		} else
			$res->pushError ($this->getModel('access')->getErrors());
		return $res->ajaxExec();
   }
   
   public function saveUser() {
	   $res = new responseCsp();
  		  if(($userData = $this->getModel()->saveUser(reqCsp::get('post'))) !== false) {
			$res->addMessage(langCsp::_('User added'));
			$res->addData($userData);
		} else
			$res->pushError ($this->getModel('access')->getErrors());
		return $res->ajaxExec();
   }
   
   public function deleteIp() {
	   $res = new responseCsp();
	   if(($delIpData = $this->getModel()->deleteElement(reqCsp::get('post'))) !== false) {
			$res->addMessage(langCsp::_('Ip address removed'));
			$res->addData($delIpData);
		} else
			$res->pushError($this->getModel('access')->getErrors());
		return $res->ajaxExec();
   }
   
   public function deleteUser() {
	   $res = new responseCsp();
	   if(($delUserData = $this->getModel()->deleteElement(reqCsp::get('post'))) !== false) {
			$res->addMessage(langCsp::_('User removed'));
			$res->addData($delUserData);
		} else
			$res->pushError($this->getModel('access')->getErrors());
		return $res->ajaxExec();
   }
   
    public function saveRole() {
	   $res = new responseCsp();
	   if(($roleRetData = $this->getModel()->saveRole(reqCsp::get('post'))) !== false) {
			$res->addMessage(langCsp::_('Role change'));
			//$res->addData($roleRetData);
		} else
			$res->pushError($this->getModel('access')->getErrors());
		return $res->ajaxExec();	
	}
	/**
	  * @see controller::getPermissions();
	  */
	 public function getPermissions() {
		 return array(
			 CSP_USERLEVELS => array(
				 CSP_ADMIN => array('saveIp', 'saveUser', 'deleteIp', 'deleteUser', 'saveRole')
			 ),
		 );
	 }
}

