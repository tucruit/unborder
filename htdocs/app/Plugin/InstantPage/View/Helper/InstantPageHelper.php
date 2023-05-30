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

	/**
	 * 公開状態を取得する
	 *
	 * @param array $data ブログ記事
	 * @return boolean 公開状態
	 */
	public function allowPublish($data)
	{
		if (ClassRegistry::isKeySet('InstantPage.InstantPage')) {
			$InstantPage = ClassRegistry::getObject('InstantPage.InstantPage');
		} else {
			$InstantPage = ClassRegistry::init('InstantPage.InstantPage');
		}
		return $InstantPage->allowPublish($data);
	}

}
