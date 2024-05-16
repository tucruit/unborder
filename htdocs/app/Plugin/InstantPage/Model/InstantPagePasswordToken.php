<?php
class InstantPagePasswordToken extends AppModel {
	public $useTable = 'instant_page_password_tokens';
	public function generate($data = null) {
		$data = array(
			'token' => substr(md5(uniqid(rand(), 1)), 0, 10),
			'data' => serialize($data),
		);
		if ($this->save($data)) {
			return $data['token'];
		}
		return false;
	}

	public function get($token) {
		$this->garbage();
		$token = $this->find('first', array(
			'recursive' => -1,
			'conditions' => array('InstantPagePasswordToken.token' => $token),
		));
		if ($token) {
			return unserialize($token['InstantPagePasswordToken']['data']);
		}
		return false;
	}

	public function garbage() {
		return $this->deleteAll(array('created < INTERVAL -3 DAY + NOW()'));
	}

}
