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
					$db->query("ALTER TABLE {$tableName} CHANGE draft draft LONGTEXT");
					break;
			}
		}

	}

	public $instant_pages = [
		'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary', 'comment' => 'ID'],
		'title' => ['type' => 'string', 'null' => true, 'default' => null, 'comment' => 'ページタイトル'],
		'name' => ['type' => 'string', 'null' => true, 'default' => null, 'comment' => 'url'],
		'instant_page_users_id' => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 11, 'comment' => 'インスタントページユーザーID'],
		'instant_page_template_id' => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 11, 'comment' => 'テンプレート'],
		'contents' => ['type' => 'string', 'null' => true, 'default' => null, 'comment' => '詳細'],
		'draft' => ['type' => 'string', 'null' => true, 'default' => null, 'comment' => '下書き'],
		'page_key_word' => ['type' => 'string', 'null' => true, 'default' => null, 'comment' => 'キーワード'],
		'page_description' => ['type' => 'string', 'null' => true, 'default' => null, 'comment' => 'ディスクリプション'],
		'status' => ['type' => 'boolean', 'null' => false, 'default' => '0', 'comment' => '公開'],
		'publish_begin' => ['type' => 'datetime', 'null' => true, 'default' => NULL, 'comment' => '公開開始'],
		'publish_end' => ['type' => 'datetime', 'null' => true, 'default' => NULL, 'comment' => '公開終了'],
		'created' => ['type' => 'datetime', 'null' => true, 'default' => null, 'comment' => '作成日時'],
		'modified' => ['type' => 'datetime', 'null' => true, 'default' => null, 'comment' => '更新日時'],
		'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
		//'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci']
	];

}
