<?php

/**
 * - メールプラグインの署名チェック → デフォルトから変更されているかどうか
 * 
 */
class EmailSignatureTest implements CuReleaseCheckTestInterface {
	protected $result	 = false;
	protected $message	 = "メールプラグインの署名チェック";

	public function title() {
		return 'メールプラグインの署名チェック';
	}

	// テスト実行処理
	public function test() {
		$errorMessage	 = '';
		$checkResult	 = array();
		$hasError		 = false;

		$SiteConfigModel = ClassRegistry::init('Mail.MailConfig');
		$data			 = $SiteConfigModel->find('first', array(
			'recursive'	 => -1,
			'callbacks'	 => false,
		));

		if (!$data) {
			$this->result	 = false;
			$errorMessage	 = '署名設定がありません。';
		}

		if ($data) {

			$siteName = Hash::get($data, 'MailConfig.site_name');
			if (strpos($siteName, 'baser') !== false) {
				$errorMessage .= '署名：WEBサイト名を再確認してください。';
				$checkResult[] = false;
			} else {
				$checkResult[] = true;
			}

			$siteUrl = Hash::get($data, 'MailConfig.site_url');
			if (strpos($siteUrl, 'basercms') !== false) {
				$errorMessage .= '署名：WEBサイトURLを再確認してください。';
				$checkResult[] = false;
			} else {
				$checkResult[] = true;
			}

			$siteEmail = Hash::get($data, 'MailConfig.site_email');
			if (strpos($siteEmail, '@basercms.net') !== false) {
				$errorMessage .= '署名：Eメールを再確認してください。';
				$checkResult[] = false;
			} else {
				$checkResult[] = true;
			}

			foreach ($checkResult as $check) {
				if (!$check) {
					$hasError = true;
				}
			}

			if ($hasError) {
				$this->result	 = false;
				$this->message	 = $errorMessage;
			} else {
				$this->result	 = true;
				$this->message	 = '設定内容に問題はありません。';
			}
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
