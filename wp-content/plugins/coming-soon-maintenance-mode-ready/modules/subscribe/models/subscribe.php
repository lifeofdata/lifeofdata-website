<?php
class subscribeModelCsp extends modelCsp {
	private $_tblHeaders = array();
	public function create($d = array()) {
		$d = dbCsp::prepareHtml($d);
		$withoutConfirm = isset($d['withoutConfirm']) ? $d['withoutConfirm'] : false;
		$mainEngine = $this->getMainEngine();
		if($mainEngine)	// Confirnation will be processed by subscribe engine, here it will be created in active state
			$withoutConfirm = true;
		if(isset($d['email']) && !empty($d['email'])) {
			if(is_email($d['email'])) {
				$d['email'] = trim($d['email']);
				$params = array(
					'email'		=> $d['email'],
					'created'	=> dbCsp::timeToDate(),
					'ip'		=> utilsCsp::getIP(),
					'active'	=> $withoutConfirm ? 1 : 0,
					'token'		=> md5($d['email']. AUTH_KEY),
				);
				if(isset($d['name']) && !empty($d['name'])){
					$params['name'] = $d['name'];
				}
				if(!frameCsp::_()->getTable('subscribers')->exists($d['email'], 'email')){
					if(frameCsp::_()->getTable('subscribers')->insert($params)) {
						if($mainEngine) {
							$mainEngine->subscribe( $params );
						}
						$this->processEnginesSubscribe($params, $mainEngine);
						if(!$withoutConfirm)
							$this->sendConfirmEmail($d['email']);
						return true;
					} else
						$this->pushError( langCsp::_('Error insert email to database') );
				} else
					$this->pushError (langCsp::_('You are already subscribed'));
			} else {
				$this->pushError (langCsp::_('Invalid email'));
			}
		} else
			$this->pushError (langCsp::_('Please enter email'));
		return false;
	}
	public function sendConfirmEmail($email) {
		return frameCsp::_()->getModule('messenger')->send(
					$email, 
					get_bloginfo('admin_email'), 
					get_bloginfo('name'), 
					'subscribe', 
					'sub_confirm', 
					array(
						'site_name' => get_bloginfo('name'),
						'link' => $this->getConfirmLink($email),
					));
	}
	public function getConfirmLink($email) {
		$token = frameCsp::_()->getTable('subscribers')->get('token', array('email' => $email), '', 'one');
		return uriCsp::_(array(
			'pl'		=> CSP_CODE,
			'mod'		=> 'subscribe',
			'action'	=> 'confirm',
			'email'		=> $email,
			'token'		=> $token,
		));
	}
	public function confirm($d = array()) {
		if(isset($d['email']) 
			&& !empty($d['email']) 
			&& isset($d['token']) 
			&& !empty($d['token'])
		) {
			$subId = frameCsp::_()->getTable('subscribers')->get('id', array('email' => $d['email'], 'token' => $d['token']), '', 'one');
			if(!empty($subId)) {
				frameCsp::_()->getTable('subscribers')->update(array('active' => 1), array('id' => $subId));
				if(!frameCsp::_()->getModule('options')->isEmpty('sub_admin_email')) {
					$this->sendAdminNotification($d['email']);
				}
				dispatcherCsp::doAction('subscribeConfirm', $subId);
				return true;
			} else
				$this->pushError (langCsp::_('No record for such email or token'));
		} else
			$this->pushError (langCsp::_('Invalid confirm data'));
		return false;
	}
	public function sendAdminNotification($email) {
		return frameCsp::_()->getModule('messenger')->send(
					frameCsp::_()->getModule('options')->get('sub_admin_email'), 
					get_bloginfo('admin_email'), 
					get_bloginfo('name'), 
					'subscribe', 
					'sub_admin_notify', 
					array(
						'site_name' => get_bloginfo('name'),
						'email' => $email,
					));
	}
	public function getList($d = array()) {
		frameCsp::_()->getTable('subscribers')->prepareHtml();
		if(isset($d['limitFrom']) && isset($d['limitTo']))
			frameCsp::_()->getTable('subscribers')->limitFrom($d['limitFrom'])->limitTo($d['limitTo']);
		$fromDb = frameCsp::_()->getTable('subscribers')->get('*', $d);
		foreach($fromDb as $i => $val) {
			$fromDb[ $i ] = $this->prepareData($fromDb[ $i ]);
		}
		return $fromDb;
	}
	public function getById($id) {
		$fromDb = frameCsp::_()->getTable('subscribers')->get('*', array('id' => $id), '', 'row');
		if($fromDb && !empty($fromDb)) {
			$fromDb = $this->prepareData($fromDb);
		}
		return $fromDb;
	}
	public function prepareData($data) {
		$data['status'] = (int)$data['active'] ? 'active' : 'disabled';
		return $data;
	}
	public function getCount($d = array()) {
		return frameCsp::_()->getTable('subscribers')->get('COUNT(*)', $d, '', 'one');
	}
	public function changeStatus($d = array()) {
		$d['id'] = isset($d['id']) ? (int)$d['id'] : 0;
		if($d['id']) {
			if(dbCsp::query('UPDATE @__subscribers SET active = IF(active, 0, 1) WHERE id = "'. $d['id']. '"')) {
				return true;
			} else
				$this->pushError (langCsp::_('Database error were occured'));
			return true;
		} else
			$this->pushError (langCsp::_('Invalid ID'));
		return false;
	}
	public function remove($d = array()) {
		$d['id'] = isset($d['id']) ? (int)$d['id'] : 0;
		if($d['id']) {
			if(frameCsp::_()->getTable('subscribers')->delete($d['id'])) {
				return true;
			} else
				$this->pushError (langCsp::_('Database error were occured'));
			return true;
		} else
			$this->pushError (langCsp::_('Invalid ID'));
		return false;
	}
	public function sendSiteOpenNotif() {
		// All active subscribers
		$subscribers = $this->getList(array('active' => 1));
		if(!empty($subscribers)) {
			foreach($subscribers as $s) {
				$this->sendSiteOpenNotifOne($s);
			}
		}
	}
	public function sendSiteOpenNotifOne($d = array()) {
		if(!empty($d['email'])) {
			return frameCsp::_()->getModule('messenger')->send(
					$d['email'], 
					get_bloginfo('admin_email'), 
					get_bloginfo('name'), 
					'subscribe', 
					'sub_site_opened', 
					array(
						'site_name' => get_bloginfo('name'),
						'site_link' => get_bloginfo('url'),
					));
		}
		return false;
	}
	public function sendNewPostNotif($d = array()) {
		// All active subscribers
		$subscribers = $this->getList(array('active' => 1));
		if(!empty($subscribers)) {
			foreach($subscribers as $s) {
				$data = $s;
				$data['post_id'] = $d['post_id'];
				$this->sendNewPostNotifOne($data);
			}
		}
	}
	public function sendNewPostNotifOne($d = array()) {
		if(!empty($d['email'])) {
			return frameCsp::_()->getModule('messenger')->send(
					$d['email'], 
					get_bloginfo('admin_email'), 
					get_bloginfo('name'), 
					'subscribe', 
					'sub_new_post', 
					array(
						'site_name' => get_bloginfo('name'),
						'post_link' => get_permalink($d['post_id']),
						'post_title' => get_the_title($d['post_id']),
					));
		}
		return false;
	}
	public function syncWithEngine($d = array()) {
		if(isset($d['engine']) && frameCsp::_()->getModule($d['engine'])) {
			$engine = frameCsp::_()->getModule($d['engine']);
			$msg = '';
			$num = 0;
			switch($d['syncType']) {
				case 'export':
					if(($num = $engine->export())) {
						$msg = langCsp::_('Emails exported: '). $num;
					}
					break;
				case 'import':
					if(($num = $engine->import())) {
						$msg = langCsp::_('Emails imported: '). $num;
					}
					break;
				case 'sync':
				default:
					$msgErr = array();
					if(($num = $engine->export())) {
						$msgErr[] = langCsp::_('Emails exported: '). $num;
					}
					if(($num = $engine->import())) {
						$msgErr[] = langCsp::_('Emails imported: '). $num;
					}
					if(!empty($msgErr))
						$msg = implode(', ', $msgErr);
					break;
			}
			if(!$engine->haveErrors()) {
				return $msg;
			} else
				$this->pushError ($this->getModel()->getErrors());
			
		} else
			$this->pushError (langCsp::_('Can not find engine for '). $d['engine']);
		return false;
	}
	public function getEngine($engine = '') {
		return frameCsp::_()->getModule($engine);
	}
	public function getMainEngine() {
		$engines = $this->getAllEngines();
		if($engines) {
			foreach($engines as $mod) {
				if(frameCsp::_()->getModule('options')->get($mod->getCode(). '_is_main')) {
					return $mod;
				}
			}
		}
		return false;
	}
	public function getAllEngines() {
		$allSubModules = frameCsp::_()->getModules(array('type' => 'subscribe'));
		if(!empty($allSubModules)) {
			$returnModules = array();
			foreach($allSubModules as $mod) {
				if(frameCsp::_()->getModule('options')->get($mod->getCode(). '_enabled')) {
					$returnModules[] = $mod;
				}
			}
			if(!empty($returnModules))
				return $returnModules;
		}
		return false;
	}
	public function processEnginesSubscribe($params, $mainEngine) {
		$engines = $this->getAllEngines();
		if($engines) {
			foreach($engines as $mod) {
				if($mainEngine && $mainEngine->getCode() === $mod->getCode()) continue;
				$mod->addRecord($params);
			}
		}
	}
	public function getTblHeaders() {
		if(empty($this->_tblHeaders)) {
			$this->_tblHeaders = array(
				'id' => array('title' => langCsp::_('ID')),
				'email' => array('title' => langCsp::_('Email')),
				'name' => array('title' => langCsp::_('Name')),
				'created' => array('title' => langCsp::_('Subscribe date')),
				'active' => array('title' => langCsp::_('Active')),
			);
			if(!frameCsp::_()->getModule('options')->get('sub_name_enable')) {
				unset($this->_tblHeaders['name']);
			}
		}
		return $this->_tblHeaders;
	}
}
