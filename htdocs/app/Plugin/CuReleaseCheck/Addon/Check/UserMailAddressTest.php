<?php
class UserMailAddressTest implements CuReleaseCheckTestInterface {
	protected $result = false;
	protected $message = "テストが実行されていません";

	public function title() {
		return 'メールアドレスチェック';
	}

	// テスト実行処理
	public function test() {
		$errorMessage = array();

		// システム管理のメールアドレスチェック
		$siteConfig = ClassRegistry::init("SiteConfigs");
		$siteConfigList = $siteConfig->find("list", array(
			'fields' => array('name', 'value')
		));
		if (strpos($siteConfigList['email'], 'catchup') !== false) {
			$errorMessage[] = "システム管理に catchupドメインのメールアドレスが利用されています。[" . $siteConfigList['email'] . "]";
		}


		// メールフォームのアドレスチェック
		$mailContent = ClassRegistry::init("Mail.MailContent");
		$mailContentList = $mailContent->find("all");
		foreach($mailContentList as $content) {
			// 送信先メールアドレスをチェック
			if (!empty($content['MailContent']['sender_1']) && strpos($content['MailContent']['sender_1'], 'catchup') !== false) {
				$errorMessage[] = "メールフォームの送信先に catchupドメインのメールアドレスが利用されています。[ID:" . $content['MailContent']['id'] . "/" . $content['Content']['title'] . "]";
			}
			// BCC用送信先メールアドレスをチェック
			if (!empty($content['MailContent']['sender_2']) && strpos($content['MailContent']['sender_2'], 'catchup') !== false) {
				$errorMessage[] = "メールフォームのBCC用送信先に catchupドメインのメールアドレスが利用されています。[ID:" . $content['MailContent']['id'] . "/" . $content['Content']['title'] . "]";
			}
		}


		// 管理者のメールアドレスチェック
		$user = ClassRegistry::init("User");
		$userList = $user->find("all", array(
			'conditions' => array(
				'email LIKE' => "%catchup%"
			),
			'recursive' => -1
		));
		if ($userList) {
			foreach($userList as $userData) {
					$errorMessage[] = "ユーザのメールアドレスにキャッチアップのドメインが利用されています。[ID:" . $userData['User']['id'] . "/" . $userData['User']['email'] . "]";
			}
		}

		if ($errorMessage) {
			$this->result = false;
			$this->message = $errorMessage;
		} else {
			$this->result = true;
			$this->message = array(
				'システム管理、メールフォーム、ユーザアカウントのメールアドレスにキャッチアップのドメインが利用されていないことを確認しました。',
			);
		}
	}

	// データ取得処理
	public function getResult() {
		return $this->result;
	}

	public function getMessage() {
		return $this->message;
	}

}
