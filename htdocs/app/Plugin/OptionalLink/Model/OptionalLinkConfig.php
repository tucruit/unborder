<?php

/**
 * [Model] オプショナルリンク設定
 *
 * @copyright		Copyright, Catchup, Inc.
 * @link			https://catchup.co.jp
 * @package			OptionalLink
 */
class OptionalLinkConfig extends AppModel {

	/**
	 * ModelName
	 * 
	 * @var string
	 */
	public $name = 'OptionalLinkConfig';

	/**
	 * PluginName
	 * 
	 * @var string
	 */
	public $plugin = 'OptionalLink';

	/**
	 * Behavior
	 * 
	 * @var array
	 */
	public $actsAs = array(
		'BcCache',
	);

	/**
	 * Validation
	 *
	 * @var array
	 */
	public $validate = array(
		'blog_content_id' => array(
			'notBlank' => array(
				'rule'		 => array('notBlank'),
				'message'	 => '必須入力です。'
			)
		)
	);

	/**
	 * 初期値を取得する
	 *
	 * @return array
	 */
	public function getDefaultValue() {
		$data = array(
			'OptionalLinkConfig' => array(
				'status' => true
			)
		);
		return $data;
	}
}
