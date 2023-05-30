<?php
/**
 * [ADMIN] ダッシュボード
 */
$user = $this->Session->read('Auth');
$isMemberGroup = Configure::read('InstantPage.enableGroup');
if (in_array($user['Admin']['user_group_id'], $isMemberGroup)) {
	header('Location: '. $this->BcBaser->getUri('/mypage/instant_page/instant_page_users/login'));
	exit;
} else {
	include __DIR__ . DS . '../../../admin-third/Dashboard/admin/index.php';
}
