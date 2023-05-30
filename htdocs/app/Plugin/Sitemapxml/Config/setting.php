<?php
$config = [
	'BcApp.adminNavigation' => [
		'Plugins' => [
			'menus' => [
				'Sitemapxml' => [
					'title' => 'サイトマップXML生成', 
					'url' => [
						'admin' => true, 
						'plugin' => 'sitemapxml', 
						'controller' => 'sitemapxml', 
						'action' => 'index'
					]
				]
			]
		],
		'Contents' => [
			'Sitemapxml' => [
				'title' => 'サイトマップXML',
				'type' => 'sitemapxml',
				'icon' => 'bca-icon--sitemapxml',
				'menus' => [
					'Sitemapxml' => [
						'title' => 'サイトマップXML生成', 
						'url' => [
							'admin' => true, 
							'plugin' => 'sitemapxml', 
							'controller' => 'sitemapxml', 
							'action' => 'index'
						]
					]
				]
			]
		]
	],
	'Sitemapxml' => [
		'filename' => 'sitemap-index.xml'
	]
];
