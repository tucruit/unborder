<?php

class BannerBreakpointsSchema extends CakeSchema {

	public $name = 'BannerBreakpoint';
	public $file = 'banner_breakpoints.php';

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {

	}

	public $banner_breakpoints = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 8, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
		'media_script' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'status' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
	);

}
