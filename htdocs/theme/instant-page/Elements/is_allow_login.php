<?php
//ログインさせてよいかどうかを確認する。
$user = $this->Session->read('Auth');
if(!empty($user['Admin']['id'])){
	if(!$this->BcBaser->isAllowLogin($user['Admin']['id'])){
		header('Location: /instant_page/instant_page_users/not_allow_login');
		exit();
	}
}

