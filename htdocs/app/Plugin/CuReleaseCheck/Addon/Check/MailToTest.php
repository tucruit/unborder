<?php

/**
 * メール送信先テスト
 * → メールフォーム設定内の通知先設定値に、catchup文字列が入ってないかチェックする
 * 
 */
class MailToTest extends BcPluginAppModel implements CuReleaseCheckTestInterface {

	public $useTable				 = false;
	protected $result				 = false;
	protected $message				 = "テストが実行されていません";
	protected $existCatchupString	 = false;

	public function title() {
		return 'メール送信先テスト';
	}

	// テスト実行処理
	public function test() {
		$MailContent = ClassRegistry::init('Mail.MailContent');
		$contents	 = $MailContent->find('all', array(
			'recursive'	 => -1,
			'callbacks'	 => false,
			'cache'		 => false,
		));
		if (!$contents) {
			$this->result	 = true;
			$this->message	 = 'メールコンテンツが存在しないため、問題ありません。';
			return;
		}

		$this->message = 'catchup 文字列を含んだメールアドレスが設定されています。';
		foreach ($contents as $content) {
			if ($this->validDomain($content['MailContent']['sender_1'])) {
				$this->message .= '<br>' . $content['MailContent']['sender_1'];
				return;
			}

			if ($this->validDomain($content['MailContent']['sender_2'])) {
				$this->message .= '<br>' . $content['MailContent']['sender_2'];
				return;
			}
		}

		$this->result	 = true;
		$this->message	 = '設定内容に問題はありません。';
	}

	/**
	 * 文字列内に catchup が存在するかどうかチェックする
	 * 
	 * @param string $address
	 * @param array $domains
	 */
	private function validDomain($address, $domains = array('catchup')) {
		if ($address) {
			foreach ($domains as $domain) {
				if (strpos($address, $domain) !== false) {
					return true;
				}
			}
		}
		return false;
	}

	// データ取得処理
	public function getResult() {
		return $this->result;
	}

	public function getMessage() {
		return $this->message;
	}

}
