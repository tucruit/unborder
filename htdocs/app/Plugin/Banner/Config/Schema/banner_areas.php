<?php

class BannerAreasSchema extends CakeSchema {

	public $name = 'BannerAreas';
	public $file = 'banner_areas.php';

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
		
	}

	public $banner_areas = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 8, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
		'width' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'height' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'description_flg' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'breakpoint1_width' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'breakpoint1_height' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'breakpoint2_width' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'breakpoint2_height' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'breakpoint3_width' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'breakpoint3_height' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'breakpoint4_width' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'breakpoint4_height' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'breakpoint5_width' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'breakpoint5_height' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'breakpoint6_width' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'breakpoint6_height' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'breakpoint7_width' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'breakpoint7_height' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'breakpoint8_width' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'breakpoint8_height' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'breakpoint9_width' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'breakpoint9_height' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'breakpoint10_width' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'breakpoint10_height' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'breakpoint11_width' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'breakpoint11_height' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'breakpoint12_width' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'breakpoint12_height' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'breakpoint13_width' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'breakpoint13_height' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'breakpoint14_width' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'breakpoint14_height' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'breakpoint15_width' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'breakpoint15_height' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
	);

}
