<?php
class DebugModeTest extends BcPluginAppModel implements CuReleaseCheckTestInterface {

	public $useTable = false;
	protected $result = false;
	protected $message = "テストが実行されていません";

	public function title() {
		return '制作開発モードのノーマルチェック';
	}

	// テスト実行処理
	public function test() {
		$errorMessage = array();

		$debugMode = Configure::read('debug');
		if ($debugMode != 0) {
			$errorMessage[] = 
				'制作開発モードがデバッグモード' . $debugMode . 'になっています。';
		}

		if ($errorMessage) {
			$this->result = false;
			$this->message = $errorMessage;
		} else {
			$this->result = true;
			$this->message = array(
				'制作開発モードに問題ないことを確認しました',
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
