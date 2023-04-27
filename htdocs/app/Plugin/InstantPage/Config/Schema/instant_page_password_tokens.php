<?php

class InstantPagePasswordTokensSchema extends CakeSchema {

	public $name = 'InstantPagePasswordTokens';
	public $file = 'instant_page_password_tokens.php';
	public $connection = 'default';

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
		$db = ConnectionManager::getDataSource($this->connection);
		if( get_class($db) !== 'BcMysql'){
			return true ;
		}

		if (isset($event['create'])) {
			switch ($event['create']) {
				case 'instantpagepasswordtokens':
					$tableName = $db->config['prefix'] . 'instant_page_password_tokens';
					$db->query("ALTER TABLE {$tableName} CHANGE data data LONGTEXT");
					break;
			}
		}
	}

	public $instant_page_password_tokens = [
		'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary', 'comment' => 'ID'],
		'token' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 32, 'comment' => 'トークン'],
		'data' => ['type' => 'string', 'null' => true, 'default' => null, 'comment' => 'インスタントページユーザーID'],
		'created' => ['type' => 'datetime', 'null' => true, 'default' => null, 'comment' => '作成日時'],
		'modified' => ['type' => 'datetime', 'null' => true, 'default' => null, 'comment' => '更新日時'],
		'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
		//'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci']
	];

}
