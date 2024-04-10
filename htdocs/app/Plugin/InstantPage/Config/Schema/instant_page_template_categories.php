<?php
class InstantPageTemplateCategoriesSchema extends CakeSchema {

	public $name = 'InstantPageTemplateCategory';
	public $file = 'instant_page_templateCategories.php';

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
	}

	public $instant_page_template_categories = [
		'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary', 'comment' => 'ID'],
		'name' => ['type' => 'string', 'null' => true, 'default' => null, 'comment' => 'テーマ名'],
		'description' => ['type' => 'text', 'null' => true, 'default' => null, 'comment' => '説明'],
		'contents' => ['type' => 'text', 'null' => true, 'default' => null, 'comment' => '詳細'],
		'draft' => ['type' => 'text', 'null' => true, 'default' => null, 'comment' => '下書き'],
		'image_1' => ['type' => 'string', 'null' => true, 'default' => null, 'comment' => 'サムネイル'],
		'image_2' => ['type' => 'string', 'null' => true, 'default' => null, 'comment' => 'キャプチャ'],
		'status' => ['type' => 'integer', 'null' => false, 'default' => null], //1:公開 0:非公開
		'created' => ['type' => 'datetime', 'null' => true, 'default' => null, 'comment' => '作成日時'],
		'modified' => ['type' => 'datetime', 'null' => true, 'default' => null, 'comment' => '更新日時'],
		'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
	];
}
