<?php

if ($this->loadSchema('4.1.0', 'Banner', 'banner_files', 'alter')){
	$this->setUpdateLog('banner_files テーブルの構造変更に成功しました。');
} else {
	$this->setUpdateLog('banner_files テーブルの構造変更に失敗しました。', true);
}
if ($this->loadSchema('4.1.0', 'Banner', 'banner_areas', 'alter')){
	$this->setUpdateLog('banner_areas テーブルの構造変更に成功しました。');
} else {
	$this->setUpdateLog('banner_areas テーブルの構造変更に失敗しました。', true);
}
if ($this->loadSchema('4.1.0', 'Banner', 'banner_breakpoints', 'create')){
	$this->setUpdateLog('banner_breakpoints テーブルの作成に成功しました。');
} else {
	$this->setUpdateLog('banner_breakpoints テーブルの作成に失敗しました。', true);
}
