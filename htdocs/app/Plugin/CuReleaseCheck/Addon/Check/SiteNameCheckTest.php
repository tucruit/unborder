<?php
class SiteNameCheckTest extends BcPluginAppModel implements CuReleaseCheckTestInterface {

	public $useTable = false;
	public $useDbConfig = 'baser';
	protected $result = false;
	protected $message = "テストが実行されていません";

	public function title() {
		return 'WEBサイト名のチェック';
	}

	// テスト実行処理
	public function test() {
		$errorMessage = array();

		$SiteConfigModel = ClassRegistry::init('SiteConfig');
		$siteName = $SiteConfigModel->find('first', array(
			'conditions' => array(
				'name' => 'formal_name'
			),
			'recursive'	 => -1,
			'callbacks'	 => false,
		));

		// TODO：比較対象が埋め込みなので、デフォルトテーマの初期値から取得したい…
		if (isset($siteName['SiteConfig']['value']) || 
			!empty($siteName['SiteConfig']['value'])) {
			if (preg_match("/baser/i", $siteName['SiteConfig']['value'])) {
				$errorMessage[] = 'WEBサイト名にbaserという文字が利用されています。';
				$errorMessage[] = $siteName['SiteConfig']['value'];
			}
		} else {
			$errorMessage[] = 'WEBサイト名が設定されていません。';
		}

		if ($errorMessage) {
			$this->result = false;
			$this->message = $errorMessage;
		} else {
			$this->result = true;
			$this->message = array(
				'WEBサイト名に問題ないことを確認しました',
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
