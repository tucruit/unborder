<?php 
/* SVN FILE: $Id$ */
/* BannerFiles schema generated on: 2013-04-29 23:04:49 : 1367244469*/
class BannerFilesSchema extends CakeSchema {
	public $name = 'BannerFiles';

	public $file = 'banner_files.php';

	public $connection = 'plugin';

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
		'url' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'alt' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'description' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'blank' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'status' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'publish_begin' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'publish_end' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
	);
}
?>