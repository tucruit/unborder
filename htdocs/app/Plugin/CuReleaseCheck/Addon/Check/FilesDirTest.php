<?php
class FilesDirTest extends BcPluginAppModel implements CuReleaseCheckTestInterface {
	public $useTable = false;
	protected $result = false;
	protected $message = "テストが実行されていません";
	
	
	
	public function title() {
		return '/filesテスト（存在しないディレクトリを作成します）';
	}
	
	

	// テスト実行処理
	public function test() {
		$errorMessage = array();
		$targetDirList = array(
			WWW_ROOT . 'files',
			WWW_ROOT . 'files' . DS . 'banners',
			WWW_ROOT . 'files' . DS . 'bgeditor',
			WWW_ROOT . 'files' . DS . 'bgeditor' . DS . 'img',
			WWW_ROOT . 'files' . DS . 'bgeditor' . DS . 'other',
			WWW_ROOT . 'files' . DS . 'blog',
			WWW_ROOT . 'files' . DS . 'editor',
			WWW_ROOT . 'files' . DS . 'mail',
			WWW_ROOT . 'files' . DS . 'theme_configs',
		);
		// 制限ファイルチェック
		$existenceDirList = array(
			WWW_ROOT . 'files' . DS . 'uploads' . DS .'limited' . DS . '.htaccess',
			WWW_ROOT . 'files' . DS . 'mail' . DS . 'limited' . DS . '.htaccess',
		);
		if (file_exists(APP . "Plugin" . DS . 'OptionalLink')) {
			$existenceDirList[] = WWW_ROOT . 'files' . DS . 'optionallink' . DS . 'limited' . DS . '.htaccess';
		}
		
		foreach($targetDirList as $targetDir) {
			if ($message = $this->dirExistsOrMake($targetDir)) {
				$errorMessage[] = $message;
			}
		}
		foreach($existenceDirList as $targetDir) {
			if ($message = $this->pathExists($targetDir)) {
				$errorMessage[] = $message;
			}
		}
		
		if ($errorMessage) {
			$this->result = false;
			$this->message = $errorMessage;
		} else {
			$this->result = true;
			$this->message = array(
				'filesの基本設定に問題ないことを確認しました',
			);
			$this->message = array_merge($this->message, $targetDirList, $existenceDirList);
		}
	}
	
	
	
	/**
	 * ディレクトリが存在するか確認する、なければ作成する
	 * @param string $path ディレクトリパス
	 * @return string 実行メッセージ
	 */
	protected function dirExistsOrMake($path) {
		// 存在チェック
		if (file_exists($path) && is_dir($path)) {
			return;
		}
		
		// 作成
		if (mkdir($path, 0777, true)) {
			return 'ディレクトリを作成しました:' . $path;
		} else {
			return '[ERROR]ディレクトリの作成を試みましたが、作成に失敗しました:' . $path;
		}
	}
	
	/**
	 * パスが存在するか確認す
	 * @param string $path ディレクトリパス
	 * @return string 実行メッセージ
	 */
	protected function pathExists($path) {
		// 存在チェック
		if (file_exists($path)) return;
		return '[ERROR]必要なファイルもしくはディレクトリが見つかりません。:' . $path;
	}
	
	
	
	// データ取得処理
	public function getResult() {
		return $this->result;
	}
	
	

	public function getMessage() {
		return $this->message;
	}

}
