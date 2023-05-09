<?php
class InstantPageModelEventListener extends BcModelEventListener {

/**
 * Events
 *
 * @var array
 */
	public $events = array(
		'Mail.MailMessage.afterValidate',
		'Mail.MailMessage.beforeSave'
	);

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
				if ($mailContent['MailContent']['id'] !== 1) { //baserCMS４系ではmail_contentテーブルにnameが無い
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
					$InstantPageUser = ClassRegistry::init('InstantPage.InstantPageUser');
					$RegisterMessage = ClassRegistry::init('InstantPage.RegisterMessage');
					// メール・アドレスのバリデーションチェックが通っている場合
					if (Validation::email($Model->data['MailMessage']['email'] )) {
						$instantPageUserMail = $InstantPageUser->find('all', array(
							'conditions' => array(
								'InstantPageUser.email' => $Model->data['MailMessage']['email'],
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
		if (isset($Model->data['MailMessage']['password_1'])) {
			App::uses('AuthComponent', 'Controller/Component');
			$Model->data['MailMessage']['password_1'] = AuthComponent::password($Model->data['MailMessage']['password_1']);
		}
		if (isset($Model->data['MailMessage']['password_2'])) {
			App::uses('AuthComponent', 'Controller/Component');
			$Model->data['MailMessage']['password_2'] = AuthComponent::password($Model->data['MailMessage']['password_2']);
		}
// $this->log('メール保存前');
// $this->log($Model->data['MailMessage']['password_1']);
		return true;
	}
}
