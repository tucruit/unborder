<?php
class InstantPageUtil extends CakeObject {
	/**
	 * Auth情報 テンポラリ
	 */
	public static $tmpLoginDataKey = 'Tmp.Login.Data';

	/**
	 * Auth情報 保持期限延長時間(day)
	 */
	public static $extensionAuthExpires = 60;


	/**
	 * 不許可ドメインチェック
	 *
	 * @param array $check チェック対象文字列
	 * @return boolean
	 */
	public static function domain($check) {
		$mailDomain = explode('@', $check);
			$disapprovalDomain = Configure::read('disapproval_domain');
			return in_array($mailDomain[1], $disapprovalDomain, true);

	}


	/**
	 * パスワードチェック
	 *
	 * @param array $check チェック対象文字列
	 * @return boolean
	 */
	public static function password($password) {
		//独自バリデート
		$valdateError = '';
		switch ($password) {
			case strlen($password) == '':
				$valdateError = 'パスワードを入力してください。';
				break;
			case strlen($password) <= 6:
				$valdateError = 'パスワードは6文字以上で入力してください。';
				break;
			case strlen($password) >= 255:
				$valdateError = 'パスワードは255文字以内で入力してください。';
				break;
			case preg_match('/[a-z0-9@\.:\/\(\)#,@\[\]\+=&;\{\}!\$\*]+/i', $password) == false;
				$valdateError = 'パスワードは半角英数字(英字は大文字小文字を区別)とスペース、記号(._-:/()#,@[]+=&;{}!$*)のみで入力してください。';
				break;
			default:
				break;
		}
		return $valdateError;
	}


	/* ユーザーモデル */
	public static function users() {
		if (ClassRegistry::isKeySet('User')) {
			$Users = ClassRegistry::getObject('User');
		} else {
			$Users = ClassRegistry::init('User');
		}
		return $Users;
	}

	/**
	 * 会員かどうかを判定する
	 *
	 * @param int $userGroupId
	 * @return boolean
	 */
	public static function isMemberGroup($userGroupId) {
		if (in_array($userGroupId, Configure::read('InstantPage.enableGroup'))) {
			return true;
		}
		return false;
	}

	/**
	 * 最新の会員のデータを取得する
	 *
	 * @param array $user
	 * @return array|boolean
	 */
	public static function getRecentMemberData($user) {
		$InstantPageUserModel = ClassRegistry::init('InstantPage.InstantPageUser');

		$data = $InstantPageUserModel->find('first', array(
			'conditions' => array('InstantPageUser.user_id' => $user['id']),
			'recursive'	 => -1,
			'callbacks'	 => false,
		));

		return $data;
	}

	/*
	 * 英数字 +ハイフン・アンダースコアを許可
	 * @param string|array $check Value to check
	 * @return bool Success
	 */
	public static function alphaNumericPlus($check) {
		if (empty($check) && $check != '0') {
			return false;
		}
		return static::_check($check, "/^[a-zA-Z0-9\-_]+$/Du");
		//return static::_check($check, '/^[\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu}\p{Nd}]+$/Du');
	}
	protected static function _check($check, $regex) {
		if (is_string($regex) && is_scalar($check) && preg_match($regex, $check)) {
			return true;
		}
		return false;
	}

	// /**
	//  * 状態を取得する
	//  *
	//  * @param array $data 会員データ
	//  * @return boolean 状態
	//  */
	// public static function allowPublish($data) {
	// 	if (isset($data['InstantPageUser'])) {
	// 		$data = $data['InstantPageUser'];
	// 	}
	// 	$allowPublish = (int) $data['status'];

	// 	return $allowPublish;
	// }



}
