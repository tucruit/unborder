<?php
/**
 * InstantPage アプリケーション設定
 *
 */
App::uses('InstantPageUtil', 'InstantPage.Lib');

// フロント認証
// $config['BcAuthPrefix'] = [
// 	// フロント（例）
//  'front' => [
// 	  'name' => __d('baser', 'インスタントページ'),
// 	  'loginRedirect' => '/cmsadmin/instant_page/instant_pages/',
// 	  'userModel' => 'User',
// 	  'loginAction'	=> '/instant_page/instant_page_users/login',
// 	  'toolbar' => true,
// 	   'sessionKey' => 'Admin'
// 	],
// 	// マイページ（例）
//  'mypage' => [
// 	  'name'			=> __d('baser', 'マイページ'),
// 	  'alias'			=> 'mypage',
// 	  'loginRedirect'	=> '/cmsadmin/instant_page/instant_pages/',
// 	  'loginTitle'	=> __d('baser', 'ログイン'),
// 	  'userModel'		=> 'User',
// 	  'loginAction'	=> '/mypage/instant_page/instant_page_users/login',
// 	  'toolbar'		=> true,
// 	  // 'sessionKey'	=> 'User'
// 	]
// ];

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
$config['pageRoutes'] = '/lp/';
$config['InstantPage'] = [
	'pref' => [
		'北海道' => 	1 ,
		'青森県' => 	2 ,
		'岩手県' => 	3 ,
		'宮城県' => 	4 ,
		'秋田県' => 	5 ,
		'山形県' => 	6 ,
		'福島県' => 	7 ,
		'茨城県' => 	8 ,
		'栃木県' => 	9 ,
		'群馬県' => 10 ,
		'埼玉県' => 11 ,
		'千葉県' => 12 ,
		'東京都' => 13 ,
		'神奈川県' => 14 ,
		'新潟県' => 15 ,
		'富山県' => 16 ,
		'石川県' => 17 ,
		'福井県' => 18 ,
		'山梨県' => 19 ,
		'長野県' => 20 ,
		'岐阜県' => 21 ,
		'静岡県' => 22 ,
		'愛知県' => 23 ,
		'三重県' => 24 ,
		'滋賀県' => 25 ,
		'京都府' => 26 ,
		'大阪府' => 27 ,
		'兵庫県' => 28 ,
		'奈良県' => 29 ,
		'和歌山県' => 30 ,
		'鳥取県' => 31 ,
		'島根県' => 32 ,
		'岡山県' => 33 ,
		'広島県' => 34 ,
		'山口県' => 35 ,
		'徳島県' => 36 ,
		'香川県' => 37 ,
		'愛媛県' => 38 ,
		'高知県' => 39 ,
		'福岡県' => 40 ,
		'佐賀県' => 41 ,
		'長崎県' => 42 ,
		'熊本県' => 43 ,
		'大分県' => 44 ,
		'宮崎県' => 45 ,
		'鹿児島県' => 46 ,
		'沖縄県' => 47
	],
		// ダウンロードユーザー新規登録URLの有効期限 ( 単位: 時間 )
	'activateMaxTime' => 24,
	'enableGroup' => [4],
];

include __DIR__ . DS . 'disapproval_domain.php';
include __DIR__ . DS . 'template.php';
