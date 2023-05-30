<?php
/**
 * [Config] SecurityPatch
 *
 */

/**
 * システムナビ
 */
$config['CuSecurityPatch'] =[
	'source_url' => 'https://basercms.net/cu_security_sv/cu_security_sv/index',
	'auto' => true, // インデックスにアクセスした際に自動で最新の情報を取得するかどうか 
];
$config['BcApp.adminNavigation'] = [
	// 'Contents' => [
	// 	'CuSecurityPatch' => [
	// 		'title' => __d('baser', '脆弱性セキュリティパッチ'),
	// 		'type' => 'cu_security_patch',
	// 		'icon' => 'bca-icon--file',
	// 		'menus' => [
	// 			'Cu_SecurityPatch' => [
	// 				'title' => __d('baser', '脆弱性セキュリティパッチ適用状況一覧'),
	// 				'url' => [
	// 					'admin' => true,
	// 					'plugin' => 'cu_security_patch',
	// 					'controller' => 'cu_security_patches',
	// 					'action' => 'index'
	// 				],
	// 			],
	// 		],
	// 	],
	// ],
	'Plugins' => [
		'menus' => [
			'CuUserRelatedMaster' => [
				'title' => '脆弱性パッチ適用履歴',
				'url' => [
					'admin' => true,
					'plugin' => 'cu_security_patch',
					'controller' => 'cu_security_patches',
					'action' => 'index'
				]
			],
		]
	],
];
