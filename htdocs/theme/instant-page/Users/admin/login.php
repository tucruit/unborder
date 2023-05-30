<?php
// インスタントページをログアウトしたときはインスタントページログインページにリダイレクト
$server = $_SERVER;
if (!empty($server['HTTP_REFERER']) && strpos($server['HTTP_REFERER'], 'admin/instant_page/') !== false) {
	header('Location: '. $this->BcBaser->getUri('/mypage/instant_page/instant_page_users/login'));
	exit;
} else {
	$enableIp = [
		//'::1',
		'183.76.75.203',
		'118.27.108.63',
	];
	if (in_array($server['REMOTE_ADDR'], $enableIp)) {
		include __DIR__ . DS . '../../../admin-third/Users/admin/login.php';
	} else {
		include __DIR__ . DS . '../../../../app/Plugin/InstantPage/View/InstantPageUsers/admin/login.php';
	}
}
