<?php

/**
 * /files 配下のディレクトリ存在チェック
 * → ディレクトリが在るかどうかだけを判定する
 * 
 */
class ExistFilesDirTest extends BcPluginAppModel implements CuReleaseCheckTestInterface {

	public $useTable	 = false;
	protected $result	 = false;
	protected $message	 = "テストが実行されていません";

	public function title() {
		return '/filesテスト（配下のディレクトリの存在チェック）';
	}

	// テスト実行処理
	public function test() {
		$errorMessage		 = array();
		$targetDirList		 = array(
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
		$existenceDirList	 = array(
			WWW_ROOT . 'files' . DS . 'uploads' . DS . 'limited' . DS . '.htaccess',
			WWW_ROOT . 'files' . DS . 'mail' . DS . 'limited' . DS . '.htaccess',
		);
		if (file_exists(APP . "Plugin" . DS . 'OptionalLink')) {
			$existenceDirList[] = WWW_ROOT . 'files' . DS . 'optionallink' . DS . 'limited' . DS . '.htaccess';
		}

		foreach ($targetDirList as $targetDir) {
			if ($message = $this->dirExistsOrMake($targetDir)) {
				$errorMessage[] = $message;
			}
		}
		foreach ($existenceDirList as $targetDir) {
			if ($message = $this->pathExists($targetDir)) {
				$errorMessage[] = $message;
			}
		}

		if ($errorMessage) {
			$this->result	 = true;
			$this->message	 = $errorMessage;
		} else {
			$this->result	 = true;
			$this->message	 = array(
				'filesの基本設定に問題ないことを確認しました',
			);
			$this->message	 = array_merge($this->message, $targetDirList, $existenceDirList);
		}
	}

	/**
	 * ディレクトリが存在するか確認する
	 * 
	 * @param string $path ディレクトリパス
	 * @return string 実行メッセージ
	 */
	protected function dirExistsOrMake($path) {
		if (file_exists($path) && is_dir($path)) {
			return;
		} else {
			return '[NO]ディレクトリがありません:' . $path;
		}
	}

	/**
	 * パスが存在するか確認する
	 * 
	 * @param string $path ディレクトリパス
	 * @return string 実行メッセージ
	 */
	protected function pathExists($path) {
		if (file_exists($path)) {
			return;
		} else {
			return '[ERROR]必要なファイルもしくはディレクトリが見つかりません。:' . $path;
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
