<?php
interface CuReleaseCheckTestInterface {
	/**
	 * @return string タイトル
	 */
	public function title();

	/**
	 * 検証実行
	 */
	public function test();

	/**
	 * @return boolean 実行結果可否
	 */
	public function getResult();

	/**
	 * @return mixin(string | array) 処理メッセージ
	 */
	public function getMessage();
}
