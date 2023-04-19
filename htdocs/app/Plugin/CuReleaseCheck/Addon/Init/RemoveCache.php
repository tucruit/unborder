<?php
class RemoveCache implements CuReleaseCheckInitInterface {
	protected $result = false;
	protected $message = "処理が実行されていません";
	
	public function title() {
		return 'キャッシュ削除';
	}
	
	public function exec() {
		clearAllCache();
		$this->result = true;
		$this->message = $this->title() . 'が正しく完了しました。';
	}
	
	public function getResult() {
		return $this->result;
	}

	public function getMessage() {
		return $this->message;
	}
}

