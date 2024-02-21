<?php
/**
 * [InstantPage] インスタントページユーザー管理
 */
class InstantPageUserStatus extends AppModel {
	public $useTable = 'instant_page_user_status';

	/**
	 * belongsTo
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'User' => array(
			'className'	 => 'User',
			'foreignKey' => 'user_id'
		),
	);

	/**
	 * construct
	 */
	public function __construct() {
		parent::__construct();

	}

}
