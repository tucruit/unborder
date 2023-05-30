<?php
class WritableDirTest extends BcPluginAppModel implements CuReleaseCheckTestInterface {
	public $useTable = false;
	protected $result = false;
	protected $message = "テストが時刻されていません";
	public $uses = array("SiteConfig");
	
	
	
	public function title() {
		return '一時フォルダ、固定ページ書き込み権限テスト';
	}
	
	

	// テスト実行処理
	public function test() {
		$errorMessage = array();
		// 書き込み権限チェック
		$writableDir = array(
			TMP,
		);
		
		// 固定ページ書き込みチェック
		if (strpos(getVersion(), '3.') === 0) {
			$dir = opendir(BASER_THEMES);
			while (($file = readdir($dir)) !== false) {
				if (is_dir(BASER_THEMES . DS . $file) && $file != "." && $file != "..") {
					$writableDir[] = BASER_THEMES . $file . DS . "Pages";
				}
			}
			closedir($dir);
		} elseif (strpos(getVersion(), '4.') === 0) {
			$appView = APP . 'View';
			$dir	 = opendir($appView . DS);
			while (($file = readdir($dir)) !== false) {
				if ($file === 'Pages') {
					if (is_dir($appView . DS . $file) && $file != "." && $file != "..") {
						$writableDir[] = $appView . DS . $file;
					}
				}
			}
			closedir($dir);
		}
		
		foreach($writableDir as $targetDir) {
			if (!is_writable($targetDir)) {
				$errorMessage[] = '[ERROR]書き込み権限がありません。:' . $targetDir;
			}
		}
		
		
		if ($errorMessage) {
			$this->result = false;
			$this->message = $errorMessage;
		} else {
			$this->result = true;
			$this->message = array(
				'一時フォルダ、固定ページの書き込み権限に問題無いことを確認しました。',
			);
			$this->message = array_merge($this->message, $writableDir);
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
