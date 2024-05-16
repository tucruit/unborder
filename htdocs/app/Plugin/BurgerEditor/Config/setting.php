<?php
/**
 * BurgerEditor <baserCMS plugin>
 *
 * @copyright		Copyright 2013 -, D-ZERO Co.,LTD.
 * @link			https://www.d-zero.co.jp/
 * @package			burger_editor
 * @since			Baser v 3.0.0
 * @license			https://market.basercms.net/files/baser_market_license.pdf
 */
$config = array(
	'BcApp' => array(
		'editors' => array('BurgerEditor.BurgerEditor' => 'BurgerEditor')
	),
	'Bge' => array(
		// ログインユーザに関わらずアップロードファイルを共有する
		'fileShare'	=> true,
		// 自動的に bge-contentsクラスを付与する
		'autoWrapper' => true,
		// 画像タイプのポップアップ選択設定を初期値onにする
		'defaultImagePopup' => true,
		// リサイズしない拡張子指定
		'noResizeExtension' => array(
			'gif'
		),
		'uploadImageSize' => array(
			'imgSizeWidthMax' => 2400,
			'imgSizeWidthDefault' => 1200,
			'imgSizeWidthSmall' => 600,
		),
		// (1024 * 1024 * 10)アップロード可能な最大サイズ10MB
		'uploadImageDataSize' => 10485760,
		// 画像リサイズ時の圧縮レベル
		'uploadImageQuality' => array(
			IMAGETYPE_JPEG => 90, // JPEG: 0 から 100 を指定
			IMAGETYPE_PNG  => 6, // PNG:  0 から   9 を指定
		),
		// cssに対するサフィックスを付与
		'enableStaticFileSuffix' => false,
		// サフィックスに追加する文字列
		'staticFileSuffix' => '',
		// Addon を提供するプラグインを配列で指定
		// プラグインの直下に「BurgerAddon」というフォルダに Addon を配置する
		'enableAddonPlugin' => array()
	)
);


// カスタマイズ設定読み込み
if (file_exists(__DIR__ . DS . 'setting_customize.php')) {
	include __DIR__ . DS . 'setting_customize.php';
	$config = Hash::merge($config, $customize_config);
}
