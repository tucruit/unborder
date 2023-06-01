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
/**
 * 必要フォルダ初期化
 */
$filesPath = WWW_ROOT.'files';
$savePath = $filesPath.DS.'bgeditor';
if(is_writable($filesPath) && !is_dir($savePath)){
	mkdir($savePath);
	chmod($savePath,0777);
}
if(!is_writable($savePath)){
	chmod($savePath,0777);
}

$saveImgPath = $savePath . DS . 'img';
if(!is_dir($saveImgPath)){
	mkdir($saveImgPath);
	chmod($saveImgPath,0777);
}
if(!is_writable($saveImgPath)){
	chmod($saveImgPath,0777);
}

$saveOtherPath = $savePath . DS . 'other';
if(!is_dir($saveOtherPath)){
	mkdir($saveOtherPath);
	chmod($saveOtherPath,0777);
}
if(!is_writable($saveOtherPath)){
	chmod($saveOtherPath,0777);
}

// サンプル画像コピー
$pluginSampleImagePath = dirname(dirname(__FILE__)).DS.'webroot'.DS.'img'.DS.'bg-sample.png';
copy($pluginSampleImagePath, $savePath.DS.'bg-sample.png');
// noimage画像コピー
$pluginSampleImagePath = dirname(dirname(__FILE__)).DS.'webroot'.DS.'img'.DS.'bg-noimage.gif';
copy($pluginSampleImagePath, $savePath.DS.'bg-noimage.gif');
// サンプルPDFコピー
$pluginSampleFilePath = dirname(dirname(__FILE__)).DS.'webroot'.DS.'img'.DS.'bg-sample.pdf';
copy($pluginSampleFilePath, $savePath.DS.'bg-sample.pdf');

/**
 * datasourceがMySQLの場合schemaの利用によって固定ページ、ブログページの本文がtext型となり
 * 最大文字数2万〜3万文字程度がbgeのプロパティ増加に制限となる可能性があるためmidiamtext型に変更する
 * TODO ちょっと行儀悪い
 */
$dbConf = new DATABASE_CONFIG();

// baserCMSバージョン判別
$coreVersion = getVersion();
$version = 4;
$confNameDefault = "default";
$confNamePlugin  = "default";
// 3系
if (strpos($coreVersion, "3.") === 0) {
	$version = 3;
	$confNameDefault = "baser";
	$confNamePlugin  = "plugin";
}
// 4系
if (strpos($coreVersion, "4.") === 0) {
}

if (!empty($dbConf->{$confNameDefault}['datasource']) && $dbConf->{$confNameDefault}['datasource'] == 'Database/BcMysql') {
	$model = ClassRegistry::init('Page');					// モデル
	$tableName = $dbConf->{$confNameDefault}['prefix'] . $model->table;	// テーブル名
	$targetColumns = array('contents', 'draft');			// カラム名
	$targetType = 'text';									// データ型
	$modifyType = 'longtext';

	$columns = $model->getColumnTypes();
	foreach($targetColumns as $targetColumn) {
		if (isset($columns[$targetColumn]) && $columns[$targetColumn] == $targetType) {
			$model->query("ALTER TABLE {$tableName} MODIFY {$targetColumn} {$modifyType}");
		}
	}
	unset($model);
	clearAllCache();
}
if (!empty($dbConf->{$confNamePlugin}['datasource']) && $dbConf->{$confNamePlugin}['datasource'] == 'Database/BcMysql') {
	$model = ClassRegistry::init('Blog.BlogPost');			// モデル
	$tableName = $dbConf->{$confNamePlugin}['prefix'] . $model->table;	// テーブル名
	$targetColumns = array('content', 'detail', 'content_draft', 'detail_draft');	// カラム名
	$targetType = 'text';									// データ型
	$modifyType = 'longtext';

	$columns = $model->getColumnTypes();
	foreach($targetColumns as $targetColumn) {
		if (isset($columns[$targetColumn]) && $columns[$targetColumn] == $targetType) {
			$model->query("ALTER TABLE {$tableName} MODIFY {$targetColumn} {$modifyType}");
		}
	}
	unset($model);
	clearAllCache();
}
unset($dbConf);

// エディタをBurgerEditorに設定
$siteConfig = ClassRegistry::init('SiteConfig');
$siteConfig->saveKeyValue(array('SiteConfig' => array('editor' => 'BurgerEditor.BurgerEditor')));
unset($siteConfig);
