<?php
class InstantPageFileUploaderSchema extends CakeSchema {

	public $name = 'InstantPageFileUploader';
	public $file = 'instant_page_file_uploader.php';

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
	}

	public $instant_page_file_uploader = [
		'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary', 'comment' => 'ID'],
		'user_id' => ['type' => 'integer', 'null' => false, 'default' => null],
		'name' => ['type' => 'string', 'null' => true, 'default' => null, 'comment' => 'ファイル名'],
		'description' => ['type' => 'text', 'null' => true, 'default' => null, 'comment' => '説明'],
		'image_1' => ['type' => 'string', 'null' => true, 'default' => null, 'comment' => 'サムネイル'],
		'created' => ['type' => 'datetime', 'null' => true, 'default' => null, 'comment' => '作成日時'],
		'modified' => ['type' => 'datetime', 'null' => true, 'default' => null, 'comment' => '更新日時'],
		'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
	];
}
