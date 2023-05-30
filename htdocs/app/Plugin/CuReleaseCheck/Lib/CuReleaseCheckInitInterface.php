<?php
interface CuReleaseCheckInitInterface {
	/**
	 * @return string タイトル
	 */
	public function title();

	/**
	 * 処理実行
	 */
	public function exec();

	/**
	 * @return boolean 実行結果可否
	 */
	public function getResult();

	/**
	 * @return mixin(string | array) 処理メッセージ
	 */
	public function getMessage();
}
