<?php
//
// Database Configuration File created by baserCMS Installation
//
class DATABASE_CONFIG {
public $default = array(
	'datasource' => 'Database/BcMysql',
	'persistent' => false,
	'host' => '127.0.0.1',
	'port' => '3306',
	'login' => 'xb934289_catchup',
	'password' => 'instant001',
	'database' => 'xb934289_db',
	'schema' => '',
	'prefix' => 'mysite_',
	'encoding' => 'utf8mb4'
);
public $test = array(
	'datasource' => 'Database/BcMysql',
	'persistent' => false,
	'host' => '127.0.0.1',
	'port' => '3306',
	'login' => 'xb934289_catchup',
	'password' => 'instant001',
	'database' => 'xb934289_db',
	'schema' => '',
	'prefix' => 'mysite_test_',
	'encoding' => 'utf8mb4'
);
	public function __construct() {
		// staging（ステージング環境）
		if (strpos($_SERVER['HTTP_HOST'], '.demo2022.e-catchup.jp') !== false) {
			$this->default['host'] = 'localhost';
			$this->default['login'] = 'catchup';
			$this->default['password'] = 'catchup55';
			$this->default['database'] = 'instant-page';
		}
		// development（開発環境）
		if (strpos($_SERVER['HTTP_HOST'], '.localhost') !== false) {
			$this->default['host'] = 'localhost';
			$this->default['login'] = 'catchup';
			$this->default['password'] = 'catchup55';
			$this->default['database'] = 'instant-page';
		}
	}
}

