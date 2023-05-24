<?php
// インスタントページをログアウトしたときはインスタントページログインページにリダイレクト
$server = $_SERVER;
if (!empty($server['HTTP_REFERER']) && strpos($server['HTTP_REFERER'], 'admin/instant_page/') !== false) {
	header('Location: '. $this->BcBaser->getUri('/mypage/instant_page/instant_page_users/login'));
	exit;
} else {
	include __DIR__ . DS . '../../../admin-third/Users/admin/login.php';
}
