<?php
/**
 * InstantPage アプリケーション設定
 *
 */
// フロント認証
$config['BcAuthPrefix'] = [
	// フロント（例）
 'front' => [
	  'name'			=> __d('baser', 'フロント'),
	  'loginRedirect'	=> '/instant_pages/',
	  'userModel'		=> 'InstantPageUser',
	  'loginAction'	=> '/mypage/instant_page/instant_page_users/login',
	  'logoutAction'=> '/mypage/instant_page/instant_page_users/logout',
	  'toolbar'		=> true,
	  'sessionKey'	=> 'InstantPageUser'
	],
	// マイページ（例）
 'mypage' => [
	  'name'			=> __d('baser', 'マイページ'),
	  'alias'			=> 'mypage',
	  'loginRedirect'	=> '/instant_pages/',
	  'loginTitle'	=> __d('baser', 'ログイン'),
	  'userModel'		=> 'InstantPageUser',
	  'loginAction'	=> '/mypage/instant_page/instant_page_users/login',
	  'logoutAction'=> '/mypage/instant_page/instant_page_users/logout',
	  'toolbar'		=> true,
	  'sessionKey'	=> 'InstantPageUser'
	]
];


// システムナビ
$config['BcApp.adminNavigation'] = [
	'Contents' => [
		'InstantPage' => [
			'name'		=> 'インスタントページ管理',
			'title' => 'インスタントページ管理',
			'icon' => 'bca-icon--users',
			'menus'	=> [
				[ 'title' => 'インスタントページ一覧',
					'url' => [ 'admin' => true, 'plugin' => 'instant_page', 'controller' => 'instant_pages', 'action' => 'index']
				],
				[ 'title' => 'ユーザー一覧',
					'url' => [ 'admin' => true, 'plugin' => 'instant_page', 'controller' => 'instant_page_users', 'action' => 'index']
				],
			],
		]
	],
];

include __DIR__ . DS . 'disapproval_domain.php';
