<?php
//
// Database Configuration File created by baserCMS Installation
//
class DATABASE_CONFIG {
public $default = array(
	'datasource' => 'Database/BcMysql',
	'persistent' => false,
	'host' => 'localhost',
	'port' => '3306',
	'login' => 'catchup',
	'password' => 'catchup55',
	'database' => 'instant-page',
	'schema' => '',
	'prefix' => 'mysite_',
	'encoding' => 'utf8'
);
public $test = array(
	'datasource' => 'Database/BcMysql',
	'persistent' => false,
	'host' => 'localhost',
	'port' => '3306',
	'login' => 'catchup',
	'password' => 'catchup55',
	'database' => 'instant-page',
	'schema' => '',
	'prefix' => 'mysite_test_',
	'encoding' => 'utf8'
);
}
