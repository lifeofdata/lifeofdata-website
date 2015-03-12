<?php
class subscribeControllerCsp extends controllerCsp {
	public function create() {
		$res = new responseCsp();
		$data = reqCsp::get('post');
		if(isset($data['withoutConfirm']))
			$data['withoutConfirm'] = false;
		 if($this->getModel()->create($data)) {
			 $res->addMessage(langCsp::_(frameCsp::_()->getModule('options')->get('sub_success_msg')));
		 } else
			 $res->pushError ($this->getModel()->getErrors());
		 return $res->ajaxExec();
	}
	public function confirm() {
		$res = new responseCsp();
		if($this->getModel()->confirm(reqCsp::get('get'))) {
			$res->addMessage(langCsp::_('Your subscription was activated!'));
		} else
			$res->pushError ($this->getModel()->getErrors());
		return $res;
	}
	public function getList() {
		$res = new responseCsp();
		if($count = $this->getModel()->getCount()) {
			$list = $this->getModel()->getList(reqCsp::get('post'));
			$res->addData('list', $list);
			$res->addData('count', $count);
			$res->addMessage(langCsp::_('Done'));
		} else
			$res->pushError ($this->getModel()->getErrors());
		return $res->ajaxExec();
	}
	public function changeStatus() {
		$res = new responseCsp();
		if($this->getModel()->changeStatus(reqCsp::get('post'))) {
			$res->addMessage(langCsp::_('Done'));
		} else
			$res->pushError ($this->getModel()->getErrors());
		return $res->ajaxExec();
	}
	public function remove() {
		$res = new responseCsp();
		if($this->getModel()->remove(reqCsp::get('post'))) {
			$res->addMessage(langCsp::_('Done'));
		} else
			$res->pushError ($this->getModel()->getErrors());
		return $res->ajaxExec();
	}
	public function syncWithEngine() {
		$res = new responseCsp();
		if(($syncMsg = $this->getModel()->syncWithEngine(reqCsp::get('post'))) !== false) {
			$res->addMessage(empty($syncMsg) ? langCsp::_('Done') : $syncMsg);
		} else
			$res->pushError ($this->getModel()->getErrors());
		return $res->ajaxExec();
	}
	public function exportToCsv() {
		$this->_connectCsvLib();
		$fileName = langCsp::_('Subscribers'). ' '. date('m-d-Y');
		$subscribers = $this->getModel()->getList();
		$csvGenerator = toeCreateObjCsp('csvgeneratorCsp', array($fileName));
		$headers = $this->getModel()->getTblHeaders();
		$c = $r = 0;
		foreach($headers as $k => $v) {
			$csvGenerator->addCell($r, $c, $v['title']);
			$c++;
		}
		$c = 0;
		$r = 1;
		if(empty($subscribers)) {
			$csvGenerator->addCell($r, $c, langCsp::_('You have no subscribers for now'));
		} else {
			foreach($subscribers as $sub) {
				$c = 0;
				foreach($headers as $k => $v) {
					$value = $sub[$k];
					if($k == 'active') {
						$value = empty($value) ? langCsp::_('No') : langCsp::_('Yes');
					}
					$csvGenerator->addCell($r, $c, $value);
					$c++;
				}
				$r++;
			}
		}
		$csvGenerator->generate();
		exit();
	}
	private function _connectCsvLib() {
		importClassCsp('filegeneratorCsp');
		importClassCsp('csvgeneratorCsp');
	}
	private function _getTblHeaders() {
		
	}
	public function getPermissions() {
		return array(
			CSP_USERLEVELS => array(
				CSP_ADMIN => array('getList', 'changeStatus', 'remove', 'syncWithEngine', 'exportToCsv')
			),
		);
	}
}

