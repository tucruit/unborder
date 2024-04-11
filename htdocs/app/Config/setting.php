<?php

$config['Blog'] = [
	// ブログアイキャッチサイズの初期値
	'eye_catch_size_thumb_width' => 2000,
	'eye_catch_size_thumb_height' => 2000,
	'eye_catch_size_mobile_thumb_width' => 600,
	'eye_catch_size_mobile_thumb_height' => 600,
];
$config['Bge.publishTimer'] = true;

// ショートコード 1カラムテキストなどで　[BcBaser.getElement common_banner_wrapp] と記載
// ショートコード 1カラムテキストなどで　[BcBaser.getSitemap 1] と記載
$config['BcShortCode']['BcBaser'] = [
	'BcBaser.getElement',
	'BcBaser.getSitemap',
];
// ショートコード 1カラムテキストなどで　[InstantPage.getElement lp_form] と記載
$config['BcShortCode']['InstantPage'] = [
	'InstantPage.getElement',
];
// ショートコード 1カラムテキストなどで　[InstantPage.getElement lp_form] と記載
/*
$config['BcShortCode']['InstantPage'] = [
	'InstantPage.getElement',
];
*/
