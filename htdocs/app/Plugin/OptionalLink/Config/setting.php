<?php
/**
 * [Config] 設定ファイル
 *
 * @copyright		Copyright, Catchup, Inc.
 * @link			https://catchup.co.jp
 * @package			OptionalLink
 */
App::uses('OptionalLinkUtil', 'OptionalLink.Lib');
/**
 * システムナビ
 */
$config['BcApp.adminNavi.optional_link'] = [
	'name' => 'オプショナルリンク プラグイン',
	'contents' => [
		['name' => '設定一覧',
			'url' => [
				'admin' => true,
				'plugin' => 'optional_link',
				'controller' => 'optional_link_configs',
				'action' => 'index']
		]
	]
];

$config['BcApp.adminNavigation'] = [
	// 'Contents' => [
	// 	'OptionalLink' => [
	// 		'name' => 'オプショナルリンク プラグイン',
	// 		'title' => 'オプショナルリンク',
	// 		'icon' => 'bca-icon--blog',
	// 		'menus' => [
	// 			[
	// 				'title' => '設定一覧',
	// 				'url' => [
	// 					'admin' => true,
	// 					'plugin' => 'optional_link',
	// 					'controller' => 'optional_link_configs',
	// 					'action' => 'index'
	// 				]
	// 			],
	// 			[
	// 				'title' => '設定済記事一覧',
	// 				'url' => [
	// 					'admin' => true,
	// 					'plugin' => 'optional_link',
	// 					'controller' => 'optional_links',
	// 					'action' => 'index'
	// 				]
	// 			]
	// 		]
	// 	]
	// ],
	'Plugins' => [
		'menus' => [
			'OptionalLink' => [
				'title' => 'オプショナルリンク設定', 
				'url' => [
					'admin' => true,
					'plugin' => 'optional_link',
					'controller' => 'optional_link_configs',
					'action' => 'index'
				]
			],
			'OptionalLinkConfigs' => [
				'title' => 'オプショナルリンク設定済記事一覧', 
				'url' => [
					'admin' => true,
					'plugin' => 'optional_link',
					'controller' => 'optional_links',
					'action' => 'index'
				]
			],
		]
	]
					

];


/**
 * 専用ログ
 */
define('LOG_OPTIONAL_LINK', 'log_optional_link');
CakeLog::config('log_optional_link', [
	'engine' => 'FileLog',
	'types' => ['log_optional_link'],
	'file' => 'log_optional_link',
]);


/**
 * 設定
 */
$config['OptionalLink'] = [
	// ファイルタイプ制限
	'allowFileExts' => [
			'jpg',
			'png',
			'gif',
			'ico',
			'pdf',
			'zip',
			'svg',
			'csv',
			'doc',
			'docx',
			'ppt',
			'pptx',
			'xls',
			'xlsx',
			'txt',
	],
	// ブラウザで開く拡張子
	'inline' => [
			'jpg',
			'png',
			'gif',
			'pdf',
	],
];

// カスタマイズ設定読み込み
if (file_exists(__DIR__ . DS . 'setting_customize.php')) {
	include __DIR__ . DS . 'setting_customize.php';
	$config = Hash::merge($config, $customize_config);
}
