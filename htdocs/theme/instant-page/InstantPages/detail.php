<?php
/*
 * インスタントページ公開テーマ
 */
$this->BcBaser->setTitle(strip_tags($data['InstantPage']['title']));
$this->BcBaser->setDescription(strip_tags($data['InstantPage']['page_description']));
$this->BcBaser->setKeywords(strip_tags($data['InstantPage']['page_key_word']));

// テンプレートリスト取得
$template = configure::read('InstantpageTemplateList');
if ( array_key_exists($data['InstantPage']['template'], $template) ) {
	$this->BcBaser->css('inst/'. $template[$data['InstantPage']['template']], ['inline' => false]);
}

echo $data['InstantPage']['contents'];
