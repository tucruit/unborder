<?php
/*
 *  インスタントページ管理 footer
 */
// ログインユーザーの取得
$user = $this->Session->read('Auth');
if (!empty($user['Admin'])) {
 $isMemberGroup = Configure::read('InstantPage.enableGroup');
	if (in_array($user['Admin']['user_group_id'], $isMemberGroup)) {
		include __DIR__ . DS . '../footer.php';
	} else {
		include __DIR__ . DS . '../../../admin-third/Elements/admin/footer.php';
	}
} else {
	include __DIR__ . DS . '../../../admin-third/Elements/admin/footer.php';
}
