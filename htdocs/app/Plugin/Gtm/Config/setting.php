<?php
$config = array(
	'BcApp.adminNavi.Gtm' => array(
		'name'		 => 'Google Tag Manager',
		'contents'	 => array(
			array('name'	 => '設定',
				'url'	 => array(
					'admin'		 => true,
					'plugin'	 => 'gtm',
					'controller' => 'gtm',
					'action'	 => 'index')
			)
		)
	)

);
$config['BcApp.adminNavigation'] = [
		'Plugins' => [
		'menus' => [
			'Gtm' => [
				'title' => 'Google Tag Manager',
				'url' => [
					'admin' => true,
					'plugin' => 'gtm',
					'controller' => 'gtm',
					'action' => 'index'
				]
			],
		]
	]
];
/*
 * 設定
 */
$config['Gtm'] = [
	// site_configの保存名
	'keyName' => 'Gtm.key',
	// 自動的に GTMタグを付与する（falseにした場合、自動では出力されず、ヘルパーにて出力）
	'auto' => true,
	 //headタグ内用のGTMタグが含まれていないかどうか判定する正規表現
	'headIgnore' => '/gtm.start/i',
	 //bodyタグ直後用のGTMタグが含まれていないかどうか判定する正規表現
	'bodyIgnore' => '/iframe src\=\"https\:\/\/www\.googletagmanager\.com/i',
];
