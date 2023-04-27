<?php
/**
 * [Model] RegisterHistory
 *
 * @copyright		Copyright, catchup.
 * @link			http://www.e-catchup.jp/
 */
	App::import('Model', 'MailMessage');
class RegisterMessage extends AppModel {

/**
 * クラス名
 *
 * @var		string
 * @access	public
 */
	public $name = 'mail_message_3';
	public $useTable = 'mail_message_3';
	// public $model = 'Mail.MailMessage';

// /**
//  * DB接続設定
//  *
//  * @var		string
//  * @access	public
//  */
// 	public $useDbConfig = 'plugin';

}
