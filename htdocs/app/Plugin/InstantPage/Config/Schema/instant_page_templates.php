<?php
class InstantPageTemplatesSchema extends CakeSchema {

	public $name = 'InstantPageTemplate';
	public $file = 'instant_page_templates.php';

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
	}

	public $instant_page_templates = [
		'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary', 'comment' => 'ID'],
		'name' => ['type' => 'string', 'null' => true, 'default' => null, 'comment' => 'テーマ名'],
		'user_id' => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 11, 'comment' => 'ユーザーID'],
		'created' => ['type' => 'datetime', 'null' => true, 'default' => null, 'comment' => '作成日時'],
		'modified' => ['type' => 'datetime', 'null' => true, 'default' => null, 'comment' => '更新日時'],
		'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
	];
}
