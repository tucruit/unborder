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
	 * @param string $id instantPage ID
	 * @param array $fields オプション（初期値 : array()）
	 *	`named` : URLの名前付きパラメーター
	 * @return string カテゴリ一覧へのURL
	 */
	public function getInstantPage($id, $fields = []) {
		$this->InstantPage = ClassRegistry::init('InstantPage.InstantPage');
		$instantPage = $this->InstantPage->findById($id);
		return $instantPage;
	}

	/**
	 * InstantPageUserを取得する
	 *
	 *
	 * @param string $userId
	 * @return array
	 */
	public function getInstantPageUser($userId) {
		$InstantPageUserModel = ClassRegistry::init('InstantPage.InstantPageUser');
		$instantPageUser = $InstantPageUserModel->find('first', [
				'conditions' => ['InstantPageUser.user_id = ' => $userId],
				'recursive'	 => -1
			]);
		return $instantPageUser ? $instantPageUser['InstantPageUser'] : [];
	}


}
