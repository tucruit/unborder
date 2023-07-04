<?php
/**
 * /baser/controllers/updaters_controller.php
 */
/**
 * instant_page_templates テーブルのcreate
 */
if ($this->loadSchema('1.0.2', 'InstantPage', 'instant_page_users', 'alter')){
	$this->setUpdateLog('instant_page_users テーブルの構造変更に成功しました。');
} else {
	$this->setUpdateLog('instant_page_users テーブルの構造変更に失敗しました。', true);
}
