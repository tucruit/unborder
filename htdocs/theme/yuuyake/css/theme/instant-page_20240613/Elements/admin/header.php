<?php
/*
 * インスタントページ管理 header
 */
// ログインユーザーの取得
$user = $this->Session->read('Auth');
if (empty($user['Admin'])) {
	//include __DIR__ . DS . '../../../admin-third/Elements/admin/header.php';
	include __DIR__ . DS . '../header.php';
} else {
	$isMemberGroup = Configure::read('InstantPage.enableGroup');
	if (in_array($user['Admin']['user_group_id'], $isMemberGroup)) {
		include __DIR__ . DS . '../mypage/header.php';
	} else {
		include __DIR__ . DS . '../../../admin-third/Elements/admin/header.php';
	}
}

