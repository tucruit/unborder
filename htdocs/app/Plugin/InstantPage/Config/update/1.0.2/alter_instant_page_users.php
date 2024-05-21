<?php

class InstantPageUsersSchema extends CakeSchema {

	public $file = 'instant_page_users.php';

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {

	}

	public $instant_page_users = [
		'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary', 'comment' => 'ID'],
		// 'name' => ['type' => 'string', 'null' => true, 'default' => null, 'comment' => 'インスタントページユーザーID'],
		// 'password' => ['type' => 'string', 'null' => true, 'default' => null, 'comment' => 'パスワード'],
		// 'real_name_1' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'comment' => 'お名前'],
		// 'real_name_2' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'comment' => 'お名前名'],
		// 'email' => ['type' => 'string', 'null' => true, 'default' => null, 'comment' => 'E-mail'],
		// 'user_group_id' => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 4],
		// 'nickname' => ['type' => 'string', 'null' => true, 'default' => null],
		'user_id' => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 11, 'comment' => 'ユーザーID'],
		'created' => ['type' => 'datetime', 'null' => true, 'default' => null, 'comment' => '作成日時'],
		'modified' => ['type' => 'datetime', 'null' => true, 'default' => null, 'comment' => '更新日時'],
		'company' => ['type' => 'string', 'null' => true, 'default' => null, 'comment' => '会社名'],
		'kana_1' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'comment' => 'フリガナ'],
		'kana_2' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'comment' => 'フリガナ名'],
		'zip_code' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 15, 'comment' => '郵便番号'],
		'prefecture_id' => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 8, 'comment' => '都道府県'],
		'address' => ['type' => 'string', 'null' => true, 'default' => null, 'comment' => '住所'],
		'building' => ['type' => 'string', 'null' => true, 'default' => null, 'comment' => '建物名'],
		'tel' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 20, 'comment' => '電話番号'],
		'plan_id' => ['type' => 'integer', 'null' => true, 'default' => 1, 'length' => 4, 'comment' => 'プラン'],
		'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
		//'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8mb4_general_ci']
	];

}




