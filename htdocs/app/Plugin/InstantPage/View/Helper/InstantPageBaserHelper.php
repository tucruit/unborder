<?php
class InstantPageBaserHelper extends AppHelper
{

	/**
	 * ログインさせて良いかどうかを判定する。
	 *
	 * @param $id
	 * @return bool
	 */
	public function isAllowLogin($id)
	{
		$InstantPageUserStatus = ClassRegistry::init('InstantPage.InstantPageUserStatus');
		$myData = $InstantPageUserStatus->findByUserId($id);
		if(!empty($myData['InstantPageUserStatus']['status_code'])){ //空でなけれログイン不可
			return false;
		} else {
			return true;
		}
	}
}
