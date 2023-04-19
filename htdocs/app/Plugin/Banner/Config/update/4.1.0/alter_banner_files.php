<?php

class BannerFilesSchema extends CakeSchema {

	public $name = 'BannerFiles';
	public $file = 'banner_files.php';

	function before($event = array()) {
		return true;
	}

	function after($event = array()) {
		
	}

	public $banner_files = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 8, 'key' => 'primary'),
		'banner_area_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 8),
		'no' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 8),
		'sort' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 8),
		'name' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'breakpoint1_name' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'breakpoint2_name' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'breakpoint3_name' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'breakpoint4_name' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'breakpoint5_name' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'breakpoint6_name' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'breakpoint7_name' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'breakpoint8_name' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'breakpoint9_name' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'breakpoint10_name' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'breakpoint11_name' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'breakpoint12_name' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'breakpoint13_name' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'breakpoint14_name' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'breakpoint15_name' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'url' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'alt' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'description' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'blank' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'status' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'publish_begin' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'publish_end' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 8),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
	);

}
