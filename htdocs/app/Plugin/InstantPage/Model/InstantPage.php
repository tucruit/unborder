<?php
/**
 * [InstantPage] InstantPage管理
 */
class InstantPage extends AppModel {
	public $useTable = 'instant_pages';

	/**
	 * belongsTo
	 *
	 * @var array
	 */
	public $belongsTo = [
		'InstantPageUser' => [
			'className' => 'InstantPage.InstantPageUser',
			'foreignKey' => 'instant_page_users_id',
			// 'order' => 'InstantPageUser.kana_1 ASC',
		],
		'InstantPageTemplate' => [
			'className' => 'InstantPage.InstantPageTemplate',
			'foreignKey' => 'instant_page_template_id',
			// 'order' => 'InstantPageUser.kana_1 ASC',
		],
	];


	/**
	 * construct
	 */
	public function __construct() {
		parent::__construct();
		$this->validate = [
			'name' => [
				'notBlank' => ['rule' => ['notBlank'], 'message' => __d('baser', 'URLを入力してください。')],
				'alphaNumericPlus' => ['rule' => 'alphaNumericPlus', 'message' => __d('baser', 'URLは半角英数字とハイフン、アンダースコアのみで入力してください。')],
				'duplicate' => ['rule' => ['duplicate', 'name'], 'message' => __d('baser', '既に登録のあるアカウント名です。')],
				'maxLength' => ['rule' => ['maxLength', 255], 'message' => __d('baser', 'アカウント名は255文字以内で入力してください。')]
			],
			// タイトル
			'title' => [
				'notBlank' => ['rule'=> ['notBlank'], 'message'	=> '必須入力です。'],
			],
		];
	}

/**
 * 英数チェックプラス
 *
 * ハイフンアンダースコアを許容
 *
 * @param array $check チェック対象文字列
 * @param array $options 他に許容する文字列
 * @return boolean
 */
	public function alphaNumericPlus($check, $options = []) {
		if (!$check[key($check)]) {
			return true;
		}
		if($options && !array_key_exists('rule', $options)) {
			if(!is_array($options)) {
				$options = [$options];
			}
			$options = preg_quote(implode('', $options), '/');
		} else {
			$options = '';
		}

		if (preg_match("/^[a-zA-Z0-9\-_" . $options . "]+$/", $check[key($check)])) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * マルチチェックボックスのチェックなしチェック
	 *
	 * @param array $check チェック対象文字列
	 * @return boolean
	 */
	public function validNotUnchecked($check) {
		foreach ($check as $ch) {
			if (empty($ch)) {
				return false;
			}
		}
		return true;
	}

	/**
	 * 一意の name 値を取得する
	 *
	 * @param string $name name フィールドの値
	 * @return string
	 */
	public function getUniqueName($name, $InstantPageId = null)
	{
		// 先頭が同じ名前のリストを取得し、後方プレフィックス付きのフィールド名を取得する
		$conditions = [
			'InstantPage.name LIKE' => $name . '%',
		];
		if ($InstantPageId) {
			$conditions['InstantPage.id <>'] = $InstantPageId;
		}
		$datas = $this->find('all', ['conditions' => $conditions, 'fields' => ['name'], 'order' => "InstantPage.name", 'recursive' => -1]);
		$datas = Hash::extract($datas, "{n}.InstantPage.name");
		$numbers = [];

		if ($datas) {
			foreach($datas as $data) {
				if ($name === $data) {
					$numbers[1] = 1;
				} elseif (preg_match("/^" . preg_quote($name, '/') . "_([0-9]+)$/s", $data, $matches)) {
					$numbers[$matches[1]] = true;
				}
			}
			if ($numbers) {
				$prefixNo = 1;
				while(true) {
					if (!isset($numbers[$prefixNo])) {
						break;
					}
					$prefixNo++;
				}
				if ($prefixNo == 1) {
					return $name;
				} else {
					return $name . '_' . ($prefixNo);
				}
			} else {
				return $name;
			}
		} else {
			return $name;
		}

	}



	/**
	 * コントロールソースを取得する
	 *
	 * @param string $field フィールド名
	 * @return array コントロールソース
	 */
	public function getControlSource($field) {
		switch ($field) {
			case 'user_id':
			$UsersModel = InstantPageUtill::users;
				$controlSources['user_id'] = $UsersModel->find('list');
				break;
		}

		if (isset($controlSources[$field])) {
			return $controlSources[$field];
		} else {
			return false;
		}
	}

	/**
	 * 公開状態を取得する
	 *
	 * @param array $data モデルデータ
	 * @return boolean 公開状態
	 */
	public function allowPublish($data)
	{
		if (isset($data['InstantPage'])) {
			$data = $data['InstantPage'];
		}

		$allowPublish = (int)$data['status'];

		if ($data['publish_begin'] == '0000-00-00 00:00:00') {
			$data['publish_begin'] = null;
		}
		if ($data['publish_end'] == '0000-00-00 00:00:00') {
			$data['publish_end'] = null;
		}

		// 期限を設定している場合に条件に該当しない場合は強制的に非公開とする
		if (($data['publish_begin'] && $data['publish_begin'] >= date('Y-m-d H:i:s')) ||
			($data['publish_end'] && $data['publish_end'] <= date('Y-m-d H:i:s'))) {
			$allowPublish = false;
		}

		return $allowPublish;
	}

	/**
	 * 公開状態の記事を取得する
	 *
	 * @param array $options
	 * @return array
	 */
	public function getPublishes($options)
	{
		if (!empty($options['conditions'])) {
			$options['conditions'] = array_merge($this->getConditionAllowPublish(), $options['conditions']);
		} else {
			$options['conditions'] = $this->getConditionAllowPublish();
		}
		// 毎秒抽出条件が違うのでキャッシュしない
		$datas = $this->find('all', $options);
		return $datas;
	}

	/**
	 * 初期値を取得する
	 *
	 * @return array
	 */
	public function getDefaultValue() {
		$data[$this->name]['status'] = 0;
		$data[$this->name]['instant_page_template_id'] = 1;
		return $data;
	}


	/**
	 * プレビュー用のデータを生成する
	 *
	 * @param array $data
	 */
	public function createPreviewData($data)
	{
		//$this->log($data);
		$post['InstantPage'] = $data['InstantPage'];
		if (isset($post['Page']['contents_tmp'])) {
			$post['InstantPage']['contents'] = $post['Page']['contents_tmp'];
		}

		// InstantPageキーのデータは作り直しているため、元データは削除して他のモデルキーのデータとマージする
		unset($data['InstantPage']);
		$post = Hash::merge($data, $post);

		return $post;
	}

}
