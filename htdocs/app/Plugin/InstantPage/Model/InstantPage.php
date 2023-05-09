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
			'order' => 'InstantPageUser.name ASC',
		],
	];

	/**
	 * バリデーション
	 *
	 * @var array
	 */
	public $validate = [
		// 契約状態
		'state' => [
			'notBlank' => [
				'rule'		=> ['notBlank'],
				'message'	=> '必須入力です。'
			],
		],
		// パートナー一覧表示
		'is_front_displayed' => [
			'notBlank' => [
				'rule'		=> ['notBlank'],
				'message'	=> '必須入力です。'
			],
		],
		// 契約開始日
		'contact_date' => [
			'notBlank' => [
				'rule'		=> ['notBlank'],
				'message'	=> '必須入力です。'
			],
		],
		// 契約No
		'no' => [
			'notBlank' => [
				'rule'		=> ['notBlank'],
				'message'	=> '必須入力です。'
			],
			'duplicate' => [
				'rule'		=>	['duplicate', 'no'],
				'message'	=> '既に登録のあるエリア名です。'
			]
		],
		// 企業名
		'name' => [
			'notBlank' => [
				'rule'		=> ['notBlank'],
				'message'	=> '必須入力です。'
			],
			'duplicate' => [
				'rule'		=>	['duplicate', 'name'],
				'message'	=> '既に登録のあるエリア名です。'
			]
		],
		// 企業名カナ
		'name_furigana' => [
			'notBlank' => [
				'rule'		=> ['notBlank'],
				'message'	=> '必須入力です。'
			],
		],
		// 郵便番号
		'zip_code' => [
			'notBlank' => [
				'rule'		=> ['notBlank'],
				'message'	=> '必須入力です。'
			],
			// 'numeric' => [
			// 	'rule'			=> ['numeric'],
			// 	'message'		=> '数値でご入力ください。',
			// 	'allowEmpty'	=> true
			// ]
		],
		// 都道府県
		'prefecture_id' => [
			'notBlank' => [
				'rule'		=> ['notBlank'],
				'message'	=> '必須入力です。'
			],
		],
		// 住所
		'address' => [
			'notBlank' => [
				'rule'		=> ['notBlank'],
				'message'	=> '必須入力です。'
			],
		],
		// 電話番号
		'tel' => [
			'notBlank' => [
				'rule'		=> ['notBlank'],
				'message'	=> '必須入力です。'
			],
			// 'numeric' => [
			// 	'rule'			=> ['numeric'],
			// 	'message'		=> '数値でご入力ください。',
			// 	'allowEmpty'	=> true
			// ]
		]
	];

	/**
	 * construct
	 */
	public function __construct() {
		parent::__construct();
		$this->validate = [
			// 契約状態
			'type' => [
				'validNotUnchecked' => ['rule' => ['validNotUnchecked', 'value'], 'message'	=> '必須入力です。'],
			],
			// 契約状態
			'state' => [
				'notBlank' => ['rule' => ['notBlank'], 'message'	=> '必須入力です。'],
			],
			//かんたん覚書
			'kantan' => [
				'notBlank' => ['rule' => ['notBlank'], 'message'	=> '必須入力です。'],
			],
			// パートナー一覧表示
			'is_front_displayed' => [
				'notBlank' => [ 'rule' => ['notBlank'], 'message'	=> '必須入力です。' ],
			],
			// 契約開始日
			'contact_date' => [
				'notBlank' => [ 'rule' => ['notBlank'], 'message'	=> '必須入力です。' ],
			],
			// 契約No
			'no' => [
				'notBlank' => [ 'rule' => ['notBlank'], 'message'	=> '必須入力です。' ],
				'alphaNumericPlus' => ['rule' => ['alphaNumericPlus',[' \.:\/\(\)#,@\[\]\+=&;\{\}!\$\*']], 'message' => '契約Noは半角英数字とハイフンのみで入力してください。'],
				//'duplicate' => [ 'rule' =>	['duplicate', 'no'], 'message'	=> '既に登録のある契約Noです。' ]
			],
			// 企業名
			'name' => [
				'notBlank' => ['rule' => ['notBlank'], 'message' => '企業名を入力してください。'],
				'duplicate' => ['rule' => ['duplicate', 'name'], 'message' => '既に登録のある企業名です。'],
				'maxLength' => ['rule' => ['maxLength', 255], 'message' => '企業名は255文字以内で入力してください。']
			],
			'name_furigana' => [
				'notBlank' => ['rule' => ['notBlank'], 'message' => '企業名（カナ）を入力してください。'],
				'zenkakuKatakana' => ['rule' => ['zenkakuKatakana'], 'message' => '企業名（カナ）は全角カタカナのみで入力してください。'],
				'maxLength' => ['rule' => ['maxLength', 50], 'message' => '企業名（カナ）は50文字以内で入力してください。']],
			'zip_code' => [
				'notBlank' => ['rule' => ['notBlank'], 'message' => '郵便番号を入力してください。'],
				'alphaNumericPlus' => ['rule' => ['alphaNumericPlus',[' \.:\/\(\)#,@\[\]\+=&;\{\}!\$\*']], 'message' => '郵便番号は半角英数字とハイフンのみで入力してください。'],
				'maxLength' => ['rule' => ['maxLength', 20], 'message' => '郵便番号は20文字以内で入力してください。']
			],
			'prefecture_id' => [
				'notBlank' => ['rule' => ['notBlank'], 'message' => '都道府県を選択してください。'],
			],
			'address' => [
				'notBlank' => ['rule' => ['notBlank'], 'message' => '住所を入力してください。'],
				//'duplicate' => ['rule' => ['duplicate', 'name'], 'message' => '既に登録のある住所です。'],
				'maxLength' => ['rule' => ['maxLength', 255], 'message' => '住所は255文字以内で入力してください。']
			],
			'tel' => [
				'notBlank' => ['rule' => ['notBlank'], 'message' => '電話番号を入力してください。'],
				'alphaNumericPlus' => ['rule' => ['alphaNumericPlus',[' \.:\/\(\)#,@\[\]\+=&;\{\}!\$\*']], 'message' => __d('baser', '電話番号は半角英数字とハイフン、アンダースコアのみで入力してください。')],
				'maxLength' => ['rule' => ['maxLength', 20], 'message' => '電話番号は20文字以内で入力してください。']
			],

		];
	}
/**
 * 全角カタカナチェック
 *
 * @param array $check チェック対象文字列
 * @return boolean
 */
	public function zenkakuKatakana($check) {
		if (!$check[key($check)]) {
			return true;
		}
		if(preg_match("/^(|[ァ-ヾ 　]+)$/u", $check[key($check)])) {
			return true;
		} else {
			return false;
		}
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
 * コントロールソースを取得する
 *
 * @param string $field フィールド名
 * @return array コントロールソース
 */
	public function getControlSource($field) {
		switch ($field) {
			case 'partner_id':
				$controlSources['partner_id'] = $this->find('list');
				break;
		}

		if (isset($controlSources[$field])) {
			return $controlSources[$field];
		} else {
			return false;
		}
	}

}
