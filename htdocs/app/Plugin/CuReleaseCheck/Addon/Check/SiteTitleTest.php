<?php

class SiteTitleTest extends BcPluginAppModel implements CuReleaseCheckTestInterface {
	public $useTable = false;
	protected $result = false;
	protected $message = "テストが実行されていません";
		
	public function title() {
		return 'WEBサイトタイトルのチェック';
	}
	
	// テスト実行処理
	public function test() {
		$errorMessage = array();
		
		$posts = ClassRegistry::init('site_configs')->find('first', array(
			'conditions' => array(
				'name' => 'name'
			)
		));
		$siteTitle = $posts['site_configs']['value'];

		// WEBサイトタイトルがデフォルトのタイトル名と違うことを判定
		if (isset($siteTitle) || !empty($siteTitle)) {
			if (preg_match("/baser/i", $siteTitle)) {
				$errorMessage[] = 'WEBサイトタイトルにbaserという文字が利用されています。';
				$errorMessage[] = $siteTitle;
			}
		} else {
			$errorMessage[] = 'WEBサイトタイトルが設定されていません。';
		}
		
		if ($errorMessage) {
			$this->result = false;
			$this->message = $errorMessage;
		} else {
			$this->result = true;
			$this->message = array(
				'WEBサイトタイトルに問題がないことを確認しました',
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
