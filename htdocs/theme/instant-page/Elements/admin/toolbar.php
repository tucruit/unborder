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
		exit;
		include __DIR__ . DS . '../mypage/toolbar.php';
	} else {
		include __DIR__ . DS . '../../../admin-third/Elements/admin/toolbar.php';
	}
}
