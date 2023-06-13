<?php
class InstantPageModelEventListener extends BcModelEventListener {

/**
 * Events
 *
 * @var array
 */
	public $events = array(
		'User.beforeFind',
		'User.beforeValidate',
		'User.afterSave',
		'User.afterDelete',
		'InstantPageUser.afterValidate',
		'InstantPageUser.afterSave',
		'InstantPageUser.afterDelete',
		'Mail.MailMessage.afterValidate',
		'Mail.MailMessage.beforeSave'
	);

	/**
	 * userBeforeFind
	 * ユーザー情報取得の際に、InstantPageUser 情報も併せて取得する
	 *
	 * @param CakeEvent $event
	 */
	public function userBeforeFind(CakeEvent $event) {
		$Model		 = $event->subject();
		$association = array(
			'InstantPageUser' => array(
				'className'	 => 'InstantPageUser',
				'foreignKey' => 'user_id'
			)
		);
		$Model->bindModel(array('hasOne' => $association));
	}

	/**
	 * userBeforeValidate
	 *
	 * @param CakeEvent $event
	 * @return boolean
	 */
	public function userBeforeValidate(CakeEvent $event) {
		$Model = $event->subject();

		if (isset($Model->data['User']['user_group_id']) && InstantPageUtil::isMemberGroup($Model->data['User']['user_group_id'])) {
			$Model->validate['email'] = array(
				'notBlank'	 => array(
					'rule'		 => array('notBlank'),
					'message'	 => "インスタントページユーザーの場合、Eメールは必須入力です。",
					'required'	 => true,
					'allowEmpty' => false,
				),
				'email'		 => array(
					'rule'		 => array('email'),
					'message'	 => 'Eメールの形式が不正です。',
				),
			);

			$InstantPageUserModel = ClassRegistry::init('InstantPage.InstantPageUser');
			$InstantPageUserModel->set($Model->data);
			return $InstantPageUserModel->validates();
		}

		return true;
	}

	/**
	 * userAfterSave
	 * ユーザー情報保存時に、InstantPageUser 情報を保存する
	 *
	 * @param CakeEvent $event
	 */
	public function userAfterSave(CakeEvent $event) {
		$Model = $event->subject();

		if (!isset($Model->data['InstantPageUser']) || empty($Model->data['InstantPageUser'])) {
			return false;
		}

		if (InstantPageUtil::isMemberGroup($Model->data['User']['user_group_id'])) {
			$saveData['InstantPageUser'] = $Model->data['InstantPageUser'];
			$saveData['InstantPageUser']['user_id'] = $Model->id;
			// 保存前にnameをunset
			if (isset($saveData['InstantPageUser']['name'])) {
				unset($saveData['InstantPageUser']['name']);
			}
			// 保存前にemailをunset
			if (isset($saveData['InstantPageUser']['email'])) {
				unset($saveData['InstantPageUser']['email']);
			}

			// InstantPageUserModel呼び出し
			$InstantPageUserModel = ClassRegistry::init('InstantPage.InstantPageUser');
			$data = $InstantPageUserModel->find('first', array(
				'conditions' => array('InstantPageUser.user_id' => $Model->data['User']['id']),
				'recursive'	 => -1,
				'callbacks'	 => false,
			));
			if (empty($data) && isset($Model->data['InstantPageUser']['id']) && $Model->data['InstantPageUser']['id']) { //新規追加時
				$InstantPageUserModel->create($saveData);
			}

			// 保存処理
			if (!$InstantPageUserModel->save($saveData, ['calback' => false])) {
				$this->log(sprintf('ID：%s のInstantPageの保存に失敗しました。', $Model->data['InstantPageUser']['id']));
				$this->log($InstantPageUserModel->validationErrors);
			} else {
				clearAllCache();
			}
		}

		return true;
	}
	/**
	 * userAfterDelete
	 * ユーザー情報削除時、そのユーザーが持つ InstantPageUser 情報を削除する
	 *
	 * @param CakeEvent $event
	 */
	public function userAfterDelete(CakeEvent $event) {
		$Model = $event->subject();
		$data = $Model->data;
		if (empty($data)) {
			return;
		}
		if (InstantPageUtil::isMemberGroup($Model->data['User']['user_group_id'])) {
			$InstantPageUserModel = ClassRegistry::init('InstantPage.InstantPageUser');

			$data = $InstantPageUserModel->find('first', array(
				'conditions' => array('InstantPageUser.user_id' => $Model->id),
				'recursive'	 => -1
			));
			if ($data) {
				if (!$InstantPageUserModel->delete($data['InstantPageUser']['id'])) {
					$this->log('ID:' . $data['InstantPageUser']['id'] . 'のInstantPageUserの削除に失敗しました。');
				}
			}
		}
	}

	/**
	 * instantPageInstantPageUserAfterSave
	 * InstantPageUser情報保存時に、 ユーザー情報を保存する
	 *
	 * @param CakeEvent $event
	 */
	public function instantPageUserAfterSave(CakeEvent $event) {
		$Model = $event->subject();
		$params = Router::getParams();
		if (!isset($Model->data['User']) || empty($Model->data['User']) || $params['controller'] == 'users') {
			return true;
		}
		if (!isset($Model->data['User']['user_group_id'])) {
			$Model->data['User']['user_group_id'] = 4;
		}
		if (InstantPageUtil::isMemberGroup($Model->data['User']['user_group_id'])) {
			$saveData['User'] = $Model->data['User'];
			$UserModel = InstantPageUtil::users();
			if (empty($saveData['User']['id'])) {
				$user = $UserModel->create($saveData);
			} else {
				$user = $UserModel->find('first', ['conditions' => ['User.id' => $saveData['User']['id']] ]);
			}

			$saveUser = $UserModel->save($saveData);
			if (!$saveUser) {
				$this->log(sprintf('ID：%s のInstantPageの保存に失敗しました。', $Model->data['InstantPageUser']['id']));
				//$this->log($InstantPageUserModel->validationErrors);
			} else {
				if (!isset($Model->data['InstantPageUser']['user_id'])) {
					$InstantPageUserModel = ClassRegistry::init('InstantPage.InstantPageUser');
					$data = $InstantPageUserModel->find('first', array(
						'conditions' => array('InstantPageUser.id' => $Model->id),
						'recursive'	 => -1
					));
					$data['InstantPageUser']['user_id'] = $saveUser['User']['id'];
					$InstantPageUserModel->save($data, ['calback' => false]);
				}
				clearAllCache();
			}
		}

		return true;
	}
	/**
	 * userAfterDelete
	 * ユーザー情報削除時、そのユーザーが持つ InstantPageUser 情報を削除する
	 *
	 * @param CakeEvent $event
	 */
	public function instantPageUserAfterDelete(CakeEvent $event) {
		$Model = $event->subject();
		$data = $Model->data;
		if (empty($data)) {
			return;
		}
		if (InstantPageUtil::isMemberGroup($data['User']['user_group_id'])) {
			$UserModel = InstantPageUtil::users();
			$userData = $UserModel->find('first', array(
				'conditions' => array('User.id' => $data['User']['id']),
				'recursive'	 => -1
			));
			if ($userData) {
				if (!$UserModel->delete($userData['User']['id'])) {
					$this->log('ID:' . $data['InstantPageUser']['id'] . 'のInstantPageUserの削除に失敗しました。');
				}
			}
		}
	}

	/**
	 * instantPageUserAfterValidate
	 *
	 * @param CakeEvent $event
	 * @return type
	 */
	public function instantPageUserAfterValidate(CakeEvent $event) {
		$Model = $event->subject();
		// パスワードチェック
		if (isset($Model->data['User']['password_1']) && $Model->data['User']['password_1']) {
			$valdateError = InstantPageUtil::password($Model->data['User']['password_1']);
			if ($valdateError) {
				$Model->invalidate('password_1_complete', $valdateError);
			}
			if ($Model->data['User']['password_1'] !== $Model->data['User']['password_2']) {
				$Model->invalidate('password_2_equal', 'パスワードが同じものではありません。');
			}
		}
	}


	/**
	 * mailMessageBeforeValidate
	 *
	 * @param CakeEvent $event
	 * @return type
	 */
	public function mailMailMessageAfterValidate(CakeEvent $event) {
		$Model = $event->subject();
		$query = $Model->data['MailMessage'];

		if (BcUtil::isAdminSystem()) { // 管理画面の場合何もしない
			return;
		}

		if ($Model->name == 'MailMessage') {

			if ($Model->data['MailMessage']['mode'] === 'Confirm' || $Model->data['MailMessage']['mode'] === 'Send') {
				$MailContent = ClassRegistry::init('Mail.MailContent');
				$mailContent = $MailContent->find('first', array(
					'conditions' => array(
						'MailContent.id' => $Model->mailFields[0]['MailField']['mail_content_id']
					),
					'recursive' => -1
				));


				// メールコンテンツチェック
				if (intval($mailContent['MailContent']['id']) !== 1 ) { //baserCMS４系ではmail_contentテーブルにnameが無い
					return;
				}

				// パスワードチェック
				if (isset($Model->data['MailMessage']['password_1']) && $Model->data['MailMessage']['password_1']) {
					$valdateError = InstantPageUtil::password($Model->data['MailMessage']['password_1']);
					if ($valdateError) {
						$Model->invalidate('password_1_complete', $valdateError);
					}
					if ($Model->data['MailMessage']['password_1'] !== $Model->data['MailMessage']['password_2']) {
						$Model->invalidate('password_2_equal', 'パスワードが同じものではありません。');
					}
				}

				// e-mailチェック
				if (isset($Model->data['MailMessage']['email']) && $Model->data['MailMessage']['email']) {
					//$InstantPageUser = ClassRegistry::init('InstantPage.InstantPageUser');
					$User = InstantPageUtil::users();
					$RegisterMessage = ClassRegistry::init('InstantPage.RegisterMessage');
					// メール・アドレスのバリデーションチェックが通っている場合
					if (Validation::email($Model->data['MailMessage']['email'] )) {
						$instantPageUserMail = $User->find('all', array(
							'conditions' => array(
								'User.email' => $Model->data['MailMessage']['email'],
							),
							'recursive' => -1
						));
						$registerMessageName = $RegisterMessage->find('all', array(
							'conditions' => array(
								'RegisterMessage.email' => $Model->data['MailMessage']['email'],
								'RegisterMessage.token_limit >=' => date('Y-m-d H:i:s'),
							),
							'recursive' => -1
						));
						if ($instantPageUserMail || $registerMessageName) {
							$Model->invalidate('email_not_uniq_email', '既に登録されているE-mailです。別のE-mailをご入力ください。');
						}
						if (InstantPageUtil::domain($Model->data['MailMessage']['email'])) {
							$Model->invalidate('email_not_domain', '許可されていないドメインです。');
						}

					}
				}

			}
		}
	// $this->log($Model->validationErrors);
		return $query;
	}

/**
 * mailMessageBeforeSave
 *
 * @param CakeEvent $event
 * @return type
 */
	public function mailMailMessageBeforeSave(CakeEvent $event) {
		$Model = $event->subject();

		if ($Model->name != 'MailMessage') {
			return true;
		}
		// モデル宣言
		$MailContent = ClassRegistry::init('Mail.MailContent');
		$mailContent = $MailContent->find('first', array(
			'conditions' => array(
				'MailContent.id' => $Model->mailFields[0]['MailField']['mail_content_id']
			),
			'recursive' => -1
		));
		//baserCMS４系ではmail_contentテーブルにnameが無い
		if ($mailContent['MailContent']['id'] != 1) {
			return true;
		}

		// パスワードを暗号化
		// if (isset($Model->data['MailMessage']['password_1'])) {
		// 	App::uses('AuthComponent', 'Controller/Component');
		// 	$Model->data['MailMessage']['password_1'] = AuthComponent::password($Model->data['MailMessage']['password_1']);
		// }
		// if (isset($Model->data['MailMessage']['password_2'])) {
		// 	App::uses('AuthComponent', 'Controller/Component');
		// 	$Model->data['MailMessage']['password_2'] = AuthComponent::password($Model->data['MailMessage']['password_2']);
		// }
// $this->log('メール保存前');
// $this->log($Model->data['MailMessage']['password_1']);
		return true;
	}
}
