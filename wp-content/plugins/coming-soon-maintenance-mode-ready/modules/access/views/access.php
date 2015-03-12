<?php
class accessViewCsp extends viewCsp {
	
   public function getAdminOptions() {
	   		
			$arrIp = array();
			$arrIpTmp = frameCsp::_()->getTable('access')->orderBy('id DESC')->get('*', array('type_access' => 1)); 
			
			foreach($arrIpTmp as $key=>$value) {
				$arrIp[$value['id']] = $value['access'];
			}
			
			$arrUser = array();
			$arrUserTmp = frameCsp::_()->getTable('access')->orderBy('id DESC')->get('*', array('type_access' => 2));
			
			foreach($arrUserTmp as $key=>$value) {
				$user_meta = get_user_by('id', $value['access']);
				$arrUser[$value['id']] = $user_meta->user_login;
			}
			
			
			$selectUser = array(''=>'');
			$arrSelUserTmp = get_users();
			
			foreach($arrSelUserTmp as $data) {
				$selectUser[$data->ID] = $data->user_login;
			}
			
			$selectRole['10'] = 'Administrator';
			$selectRole['7'] = 'Editor';
			$selectRole['2'] = 'Author';
			$selectRole['1'] = 'Contributor';
			$selectRole['0'] = 'Subscriber';
			
			$this->assign('arrIp', $arrIp);
			$this->assign('arrUser', $arrUser);
			$this->assign('selectUser', $selectUser);
			$this->assign('selectRole', $selectRole);
			
			$blockList[0] = parent::getContent('ipBlock');
			$blockList[1] = parent::getContent('userBlock');
			$blockList[2] = parent::getContent('roleBlock');
		
			$this->assign('blockList', $blockList);
			
		return parent::getContent('accessPage');
	}
	
	public function getRoles() {
		global $wp_roles;

	  	$allRoles = $wp_roles->roles;
	  	$editableRoles = apply_filters('editable_roles', $allRoles);

	  return $editableRoles;
 }
	
	
}