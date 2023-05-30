<?php
/* 
 * サンプルのテスト
 */
/*

class Blank implements CuReleaseCheckTestInterface {
	protected $result = false;
	protected $message = "テストが実行されていません";
	
	public function title() {
		return 'テストを作るためのサンプルプログラム';
	}
	
	public function test() {
		$errorMessage = array();
		
		// モデルを利用するサンプル
		$siteConfig = ClassRegistry::init("SiteConfigs");
		$siteConfigList = $siteConfig->find("list", array(
			'fields' => array('name', 'value')
		));
		if (!$siteConfigList) {
			$errorMessage[] = "エラーメッセージを作成します。";
		}
		
		if ($errorMessage) {
			$this->result = false;
			$this->message = $errorMessage;
		} else {
			$this->result = true;
			$this->message = array(
				$this->title() . 'が正しく完了しました。',
			);
		}
	}
	
	public function getResult() {
		return $this->result;
	}

	public function getMessage() {
		return $this->message;
	}
}

 */
