<?php
/**
 * /baser/controllers/updaters_controller.php
 */
/**
 * instant_page_templates テーブルのcreate
 */
if ($this->loadSchema('1.0.1', 'InstantPage', 'instant_page_templates', 'create')){
	$this->setUpdateLog('instant_page_templates テーブルの作成に成功しました。');
} else {
	$this->setUpdateLog('instant_page_templates テーブルの作成に失敗しました。', true);
}
if ($this->loadSchema('1.0.1', 'InstantPage', 'instant_pages', 'alter')){
	$this->setUpdateLog('instant_pages テーブルの構造変更に成功しました。');
} else {
	$this->setUpdateLog('instant_pages テーブルの構造変更に失敗しました。', true);
}
