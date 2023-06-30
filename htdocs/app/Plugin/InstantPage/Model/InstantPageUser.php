<?php
/**
 * [InstantPage] インスタントページユーザー管理
 */
class InstantPageUser extends AppModel {
	public $useTable = 'instant_page_users';

	/**
	 * belongsTo
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'User' => array(
			'className'	 => 'User',
			'foreignKey' => 'user_id'
		),
	);
	/**
	 * hasmany
	 *
	 * @var array
	 */
	public $hasmany = [
		'InstantPage' => [
			'className'	=> 'InstantPage.InstantPage',
			'foreignKey' => 'instant_page_users_id'
			]
		];

	/**
	 * construct
	 */
	public function __construct() {
		parent::__construct();
		$this->validate = [
			'name' => [
				'notBlank' => ['rule' => ['notBlank'], 'message' => __d('baser', 'アカウント名を入力してください。')],
				'alphaNumericPlus' => ['rule' => 'alphaNumericPlus', 'message' => __d('baser', 'アカウント名は半角英数字とハイフン、アンダースコアのみで入力してください。')],
				'duplicate' => ['rule' => ['duplicate', 'name'], 'message' => __d('baser', '既に登録のあるアカウント名です。')],
				'maxLength' => ['rule' => ['maxLength', 255], 'message' => __d('baser', 'アカウント名は255文字以内で入力してください。')]
			],
			// 会社名
			'company' => [
				'notBlank' => ['rule'=> ['notBlank'], 'message'	=> '必須入力です。'],
			],
			'real_name_1' => [
				'notBlank' => ['rule' => ['notBlank'], 'message' => __d('baser', 'お名前（姓）を入力してください')],
				'maxLength' => ['rule' => ['maxLength', 50], 'message' => __d('baser', 'お名前は50文字以内で入力してください。')]
			],
			'real_name_2' => [
				'notBlank' => ['rule' => ['notBlank'], 'message' => __d('baser', 'お名前（名）を入力してください')],
				'maxLength' => ['rule' => ['maxLength', 50], 'message' => __d('baser', 'お名前は50文字以内で入力してください。')]
			],
			'kana_1' => [
				'notBlank' => ['rule' => ['notBlank'], 'message' => __d('baser', 'フリガナ（セイ）を入力してください。')],
				'zenkakuKatakana' => ['rule' => ['zenkakuKatakana'], 'message' => __d('baser', 'フリガナ（セイ）は全角カタカナのみで入力してください。')],
				'maxLength' => ['rule' => ['maxLength', 50], 'message' => __d('baser', 'フリガナ（セイ）は50文字以内で入力してください。')]
			],
			'kana_2' => [
				'notBlank' => ['rule' => ['notBlank'], 'message' => __d('baser', 'フリガナ（メイ）を入力してください。')],
				'zenkakuKatakana' => ['rule' => ['zenkakuKatakana'], 'message' => __d('baser', 'フリガナ（メイ）は全角カタカナのみで入力してください。')],
				'maxLength' => ['rule' => ['maxLength', 50], 'message' => __d('baser', 'フリガナ（メイ）は50文字以内で入力してください。')]
			],
			'password' => [
				'minLength' => ['rule' => ['minLength', 6], 'allowEmpty' => false, 'message' => __d('baser', 'パスワードは6文字以上で入力してください。')],
				'maxLength' => ['rule' => ['maxLength', 255], 'message' => __d('baser', 'パスワードは255文字以内で入力してください。')],
				'alphaNumericPlus' => ['rule' => ['alphaNumericPlus',[' \.:\/\(\)#,@\[\]\+=&;\{\}!\$\*']], 'message' => __d('baser', 'パスワードは半角英数字(英字は大文字小文字を区別)とスペース、記号(._-:/()#,@[]+=&;{}!$*)のみで入力してください。')],
				'confirm' => ['rule' => ['confirm', ['password_1', 'password_2']], 'message' => __d('baser', 'パスワードが同じものではありません。')]],
			'email' => [
				'notBlank' => ['rule' => ['notBlank'], 'message' => __d('baser', 'Eメールを入力してください。')],
				'email' => ['rule' => ['email'], 'message' => __d('baser', 'Eメールの形式が不正です。'), 'allowEmpty' => false],
				'disapprovalDomain' => ['rule' => ['disapprovalDomain'], 'message' => '許可されていないドメインです。'],
				'duplicate' => ['rule' => ['duplicate', 'name'], 'message' => __d('baser', '既に登録のあるEメールです。')],
				'maxLength' => ['rule' => ['maxLength', 255], 'message' => __d('baser', 'Eメールは255文字以内で入力してください。')]
			],
			'user_group_id' => ['rule' => ['notBlank'], 'message' => __d('baser', 'グループを選択してください。')],
			// 郵便番号
			'zip_code' => [
				'notBlank' => [
					'rule'		=> ['notBlank'],
					'message'	=> '必須入力です。'
				],
				'numeric' => [
					'rule'			=> ['numeric'],
					'message'		=> '数値でご入力ください。',
					'allowEmpty'	=> true
				]
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
				'notBlank' => ['rule' => ['notBlank'], 'message' => __d('baser', '電話番号を入力してください。')],
				'alphaNumericPlus' => ['rule' => 'alphaNumericPlus', 'message' => __d('baser', '電話番号は半角数字で入力してください。')],
				'maxLength' => ['rule' => ['maxLength', 20], 'message' => __d('baser', '電話番号は20文字以内で入力してください。')]
			],

		];
	}

	/**
	 * 初期値を取得する
	 *
	 * @return array
	 */
	public function getDefaultValue() {
		$data = array(
			$this->name => array(
				'status' => 1,
				'password' => '',
			)
		);
		return $data;
	}
	/**
	 * コントロールソースを取得する
	 *
	 * @param string $field フィールド名
	 * @return array コントロールソース
	 */
	public function getControlSource($field) {
		// switch ($field) {
		// 	case 'partner_id':
		// 		$controlSources['partner_id'] = $this->InstantPage->find('list');
		// 		break;
		// }

		if (isset($controlSources[$field])) {
			return $controlSources[$field];
		} else {
			return false;
		}
	}


	/**
	 * ユーザーリストを取得する
	 * 条件を指定する場合は引数を指定する
	 *
	 * @param array $conditions 取得条件
	 * @return array
	 */
	public function getUserList($conditions = [])
	{
		$users = $this->find("all", [
			//'fields' => ['id', 'user_id'],
			'conditions' => $conditions,
			'recursive' => 0
		]);
		$list = Hash::combine($users, '{n}.InstantPageUser.id', '{n}.User.real_name_1');
		return $list;
	}

	/**
	 * ユーザーnameリストを取得する
	 * 条件を指定する場合は引数を指定する
	 *
	 * @param array $conditions 取得条件
	 * @return array
	 */
	public function getUserNameList($conditions = [])
	{
		$users = $this->find("all", [
			//'fields' => ['id', 'user_id'],
			'conditions' => $conditions,
			'recursive' => 0
		]);
		$list = Hash::combine($users, '{n}.InstantPageUser.id', '{n}.User.name');
		return $list;
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
	 * 不許可ドメインチェック
	 *
	 * @param array $check チェック対象文字列
	 * @return boolean
	 */
	public function disapprovalDomain($check) {
		$mailAdress = implode('', $check);
		$mailDomain = explode('@', $mailAdress);
		// それ以外はconfigの過去ドメインで判定
		$disapprovalDomain = Configure::read('disapproval_domain');
		if (in_array($mailDomain[1], $disapprovalDomain, true)) {
			return false;
		}
		return true;

	}

	/**
	 * beforeSave
	 *
	 * @param type $options
	 * @return boolean
	 */
	public function beforeSave($options = array()) {
		// Active Actionからのユーザー登録の場合、二重にパスワードが暗号化されないようにリターンする
		// if (isset($this->data[$this->alias]['from']) && $this->data[$this->alias]['from'] == 'active_action') {
		// 	return true;
		// } elseif (isset($this->data['User']['password'])) {
		// 	App::uses('AuthComponent', 'Controller/Component');
		// 	$this->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
		// }
		// 県名がテキストだった場合、idで保存する
		if (isset($this->data[$this->alias]['prefecture_id'])) {
			$prefIds =Configure::read('InstantPage.pref');
			if (array_key_exists($this->data[$this->alias]['prefecture_id'], $prefIds)) {
				$this->data[$this->alias]['prefecture_id'] = $prefIds[$this->data[$this->alias]['prefecture_id']];
			}
		}

		return true;

	}

}
