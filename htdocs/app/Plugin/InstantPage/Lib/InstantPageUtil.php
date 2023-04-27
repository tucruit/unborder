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
	 * パスワードドメインチェック
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


}
