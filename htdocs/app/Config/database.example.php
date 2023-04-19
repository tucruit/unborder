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
	'login' => 'catchup',
	'password' => 'password',
	'database' => 'database',
	'schema' => '',
	'prefix' => 'mysite_',
	'encoding' => 'utf8mb4'
);
public $test = array(
	'datasource' => 'Database/BcMysql',
	'persistent' => false,
	'host' => '127.0.0.1',
	'port' => '3306',
	'login' => 'catchup',
	'password' => 'password',
	'database' => 'database',
	'schema' => '',
	'prefix' => 'mysite_test_',
	'encoding' => 'utf8mb4'
);
	public function __construct() {
		// staging（ステージング環境）
		if (strpos($_SERVER['HTTP_HOST'], '.demo.domain') !== false) {
			$this->default['host'] = 'localhost';
			$this->default['login'] = 'catchup';
			$this->default['password'] = 'password';
			$this->default['database'] = 'database';
		}
		// development（開発環境）
		if (strpos($_SERVER['HTTP_HOST'], '.localhost') !== false) {
			$this->default['host'] = 'localhost';
			$this->default['login'] = 'catchup';
			$this->default['password'] = 'password';
			$this->default['database'] = 'database';
		}
	}
}

