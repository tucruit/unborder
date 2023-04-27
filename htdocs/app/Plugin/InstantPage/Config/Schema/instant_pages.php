<?php

class InstantPagesSchema extends CakeSchema {

	public $name = 'InstantPages';
	public $file = 'instant_pages.php';
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
				case 'instantpages':
					$tableName = $db->config['prefix'] . 'instant_pages';
					$db->query("ALTER TABLE {$tableName} CHANGE contents contents LONGTEXT");
					break;
			}
		}

	}

	public $instant_pages = [
		'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary', 'comment' => 'ID'],
		'title' => ['type' => 'string', 'null' => true, 'default' => null, 'comment' => 'ページタイトル'],
		'url' => ['type' => 'string', 'null' => true, 'default' => null, 'comment' => 'url'],
		'author_id' => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 8, 'comment' => 'インスタントページユーザーID'],
		'template' => ['type' => 'string', 'null' => true, 'default' => null, 'comment' => 'テンプレート'],
		'contents' => ['type' => 'string', 'null' => true, 'default' => null, 'comment' => '詳細'],
		'status' => ['type' => 'boolean', 'null' => false, 'default' => '0', 'comment' => '公開'],
		'publish_begin' => ['type' => 'datetime', 'null' => true, 'default' => NULL, 'comment' => '公開開始'],
		'publish_end' => ['type' => 'datetime', 'null' => true, 'default' => NULL, 'comment' => '公開終了'],
		'created' => ['type' => 'datetime', 'null' => true, 'default' => null, 'comment' => '作成日時'],
		'modified' => ['type' => 'datetime', 'null' => true, 'default' => null, 'comment' => '更新日時'],
		'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
		//'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci']
	];

}
