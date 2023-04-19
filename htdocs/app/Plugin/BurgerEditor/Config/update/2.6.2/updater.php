<?php
/**
 * 2.6.2 バージョン アップデートスクリプト
 */
// DB検証
$dbConf = new DATABASE_CONFIG();
if (!empty($dbConf->baser['datasource']) && $dbConf->baser['datasource'] == 'Database/BcMysql') {
	$model = ClassRegistry::init('Page');					// モデル
	$tableName = $dbConf->baser['prefix'] . $model->table;	// テーブル名
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
if (!empty($dbConf->plugin['datasource']) && $dbConf->plugin['datasource'] == 'Database/BcMysql') {
	$model = ClassRegistry::init('Blog.BlogPost');			// モデル
	$tableName = $dbConf->plugin['prefix'] . $model->table;	// テーブル名
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
