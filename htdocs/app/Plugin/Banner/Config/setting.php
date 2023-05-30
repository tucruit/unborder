<?php
/**
 * [BANNER] SystemNavi
 *
 * @copyright		Copyright 2014 - 2018, D-ZERO Co.,LTD.
 * @link			http://www.d-zero.co.jp/
 * @package			Banner
 * @license			MIT
 */
/**
 * システムナビ
 */
$config['BcApp.adminNavi.Banner'] = [
		'name'		=> 'バナー プラグイン',
		'contents'	=> [
			['name' => 'ブレイクポイント設定',
				'url' => [
					'admin' => true,
					'plugin' => 'banner',
					'controller' => 'banner',
					'action' => 'config']
			],

			['name' => 'バナーエリア一覧',
				'url' => [
					'admin' => true,
					'plugin' => 'banner',
					'controller' => 'banner_areas',
					'action' => 'index']
			],
			['name' => 'バナーエリア新規登録',
				'url' => [
					'admin' => true,
					'plugin' => 'banner',
					'controller' => 'banner_areas',
					'action' => 'add']
			],
	]
];
$config['Banner'] = [
	'breakpointMax' => 15,
];

$config['BcApp.adminNavigation'] = [
	'Contents' => [
		'Banner' => [
			'name'		=> 'バナー プラグイン',
			'title' => 'バナー プラグイン',
			'type' => 'banner',
			'icon' => 'bca-icon--banner',
			'menus'	=> [
				[
					'title' => 'バナーエリア一覧',
					'url' => [
						'admin' => true,
						'plugin' => 'banner',
						'controller' => 'banner_areas',
						'action' => 'index']
				],
				[
					'title' => 'バナーエリア新規登録',
					'url' => [
						'admin' => true,
						'plugin' => 'banner',
						'controller' => 'banner_areas',
						'action' => 'add']
				],
				[
					'title' => 'ブレイクポイント設定',
					'url' => [
						'admin' => true,
						'plugin' => 'banner',
						'controller' => 'banner',
						'action' => 'config']
				],
			],
		]
	],
];

// TODO bcUploadヘルパを使ってると SessionComponent が存在しない、というエラーが出る
// 以下で解消できるが影響範囲が掴めないため保留
// App::import('Component', 'Session'];
/* $BannerArea = ClassRegistry::init('Banner.BannerArea'];
$bannerAreas = $BannerArea->find('all', ['recursive' => -1]];
if($bannerAreas] {
	foreach($bannerAreas as $bannerArea] {
		$bannerArea = $bannerArea['BannerArea'];
		$config['BcApp.adminNavi.banner']['contents'] = array_merge($config['BcApp.adminNavi.banner']['contents'], [
			['name' => '['.$bannerArea['name'].'] バナー一覧',		'url' => ['admin' => true, 'plugin' => 'banner', 'controller' => 'banner_areas', 'action' => 'index', $bannerArea['id']]],
			['name' => '['.$bannerArea['name'].'] バナー登録',		'url' => ['admin' => true, 'plugin' => 'banner', 'controller' => 'banner_files', 'action' => 'add', $bannerArea['id']]],
			['name' => '['.$bannerArea['name'].'] 設定',			'url' => ['admin' => true, 'plugin' => 'banner', 'controller' => 'banner_areas', 'action' => 'edit', $bannerArea['id']]],
		]];
	}
}*/
