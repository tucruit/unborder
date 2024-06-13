<?php
/**
 * [InstantPage] インスタントページ決済ログ管理
 */
class InstantPagePaymentLog extends AppModel {

	public $useTable = 'instant_page_payment_logs';

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

}
