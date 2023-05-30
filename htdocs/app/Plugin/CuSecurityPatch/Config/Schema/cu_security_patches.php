<?php
class CuSecurityPatchesSchema extends CakeSchema {

	public $file = 'cu_security_patches.php';

	public function before($event = []) {
		return true;
	}

	public function after($event = []) {
	}

	public $cu_security_patches = [
		'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'],
		'title' => ['type' => 'string', 'null' => true, 'default' => null],
		'version' => ['type' => 'string', 'null' => true, 'default' => null],
		'url' => ['type' => 'string', 'null' => true, 'default' => null],
		'done' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1, 'unsigned' => false],
		'comment' => ['type' => 'text', 'null' => true, 'default' => null],
		'publish_date' => ['type' => 'datetime', 'null' => true, 'default' => null],
		'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
		'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
		'indexes' => [
			'PRIMARY' => ['column' => 'id', 'unique' => 1]
		],
		'tableParameters' => ['engine' => 'InnoDB']
	];

}
