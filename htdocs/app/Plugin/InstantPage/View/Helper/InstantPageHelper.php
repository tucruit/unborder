<?php
/**
 * InstantPageヘルパー
 *
 */
class InstantPageHelper extends AppHelper {
	/**
	 * ヘルパー
	 *
	 * @var array
	 */
	public $helpers = ['BcBaser', 'BcUpload', 'BcContents', 'BcText'];

	/**
	 * InstantPageを取得する
	 *
	 * [注意] リンク関数でラップする前提の為、ベースURLは考慮されない
	 *
	 * @param string $blogCategoyId ブログカテゴリID
	 * @param array $options オプション（初期値 : array()）
	 *	`named` : URLの名前付きパラメーター
	 * @return string カテゴリ一覧へのURL
	 */
	public function getInstantPage($id, $fields = []) {
		$this->InstantPage = ClassRegistry::init('InstantPage.InstantPage');
		$partner = $this->InstantPage->findById($id);
		return $partner;
	}

}
