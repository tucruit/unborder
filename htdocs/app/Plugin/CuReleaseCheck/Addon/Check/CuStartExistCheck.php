<?php
class CuStartExistCheck extends BcPluginAppModel implements CuReleaseCheckTestInterface {
	public $useTable = false;
	protected $result = false;
	protected $message = "テストが実行されていません";
	
	public function title() {
		return '_cuStart存在確認';
	}

	// テスト実行処理
	public function test() {
		$filename = '_cuStart.php';
		if (file_exists(ROOT.DS.$filename)) {
			$errorMessage = $filename.'が存在します';
		}
                
		if (empty($errorMessage)) {
			$this->result = true;
			$this->message = array(
				'存在しない事を確認しました',
			);
		} else {
			$this->result = false;
			$this->message = $errorMessage;
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
