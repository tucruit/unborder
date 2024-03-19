<?php

class InstantPageUserStatusSchema extends CakeSchema {

	public $file = 'instant_page_user_status.php';

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {

	}

	public $instant_page_user_status = [
		'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary', 'comment' => 'ID'],
		'user_id' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 11, 'comment' => 'ユーザーID'],
		'instant_page_user_id' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 11, 'comment' => 'インスタントページユーザーID'],
		'status_code' => ['type' => 'string', 'null' => true, 'default' => null, 'comment' => 'ユーザーステータスコード'],
		'created' => ['type' => 'datetime', 'null' => true, 'default' => null, 'comment' => '作成日時'],
		'modified' => ['type' => 'datetime', 'null' => true, 'default' => null, 'comment' => '更新日時'],
		'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
		//'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8mb4_general_ci']
	];

}



