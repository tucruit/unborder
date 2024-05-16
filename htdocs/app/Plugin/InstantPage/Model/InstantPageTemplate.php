<?php
/**
 * [InstantPage] InstantPage管理
 */
class InstantPageTemplate extends AppModel {
	public $useTable = 'instant_page_templates';

	/**
	 * belongsTo
	 *
	 * @var array
	 */
	public $belongsTo = [
		'User' => [
			'className' => 'User',
			'foreignKey' => 'user_id'
		],
	];

	/**
	 * hasmany
	 *
	 * @var array
	 */
	public $hasmany = [
		'InstantPage' => [
			'className'	=> 'InstantPage.InstantPage',
			'foreignKey' => 'instant_page_template_id',
			'order' => 'InstantPage.created DESC',
			'dependent' => true,
			'exclusive' => false,
			'finderQuery' => ''
			]
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
	 * 一意の name 値を取得する
	 *
	 * @param string $name name フィールドの値
	 * @return string
	 */
	public function getUniqueName($name, $InstantPageTemplatId = null)
	{
		// 先頭が同じ名前のリストを取得し、後方プレフィックス付きのフィールド名を取得する
		$conditions = [
			'InstantPageTemplat.name LIKE' => $name . '%',
		];
		if ($InstantPageTemplatId) {
			$conditions['InstantPageTemplat.id <>'] = $InstantPageTemplatId;
		}
		$datas = $this->find('all', ['conditions' => $conditions, 'fields' => ['name'], 'order' => "InstantPageTemplat.name", 'recursive' => -1]);
		$datas = Hash::extract($datas, "{n}.InstantPageTemplat.name");
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


}
