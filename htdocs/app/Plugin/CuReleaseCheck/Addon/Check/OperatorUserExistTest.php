<?php

/**
 * 運営ユーザの存在チェック
 *  → 存在するか
 * 
 */
class OperatorUserExistTest implements CuReleaseCheckTestInterface {

	protected $result	 = false;
	protected $message	 = '運営ユーザの存在チェック';

	public function title() {
		return '運営ユーザの存在チェック';
	}

	public function test() {
		$UserGroupModel	 = ClassRegistry::init('UserGroup');
		$data			 = $UserGroupModel->find('first', array(
			'conditions' => array(
				'OR' => array(
					array('UserGroup.name' => 'operators'),
					array('UserGroup.title LIKE' => '%' . 'サイト運営' . '%'),
				),
			),
			'callbacks'	 => false,
			'cache'		 => false,
		));

		if (!$data) {
			$this->message = '運営グループが設定されていません。';
			return;
		}

		if (count($data['User'])) {
			$this->result	 = true;
			$this->message	 = '運営ユーザーは設定されています。';
		} else {
			$this->message = '運営ユーザーが設定されていません。';
		}
	}

	public function getResult() {
		return $this->result;
	}

	public function getMessage() {
		return $this->message;
	}

}
