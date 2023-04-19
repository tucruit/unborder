<?php
/**
 * [BANNER] バナーブレークポイント管理
 *
 * @copyright		Copyright 2014 - 2018, D-ZERO Co.,LTD.
 * @link			http://www.d-zero.co.jp/
 * @package			Banner
 * @license			MIT
 */
App::uses('Banner.BannerAppModel', 'Model');
class BannerBreakpoint extends BannerAppModel {
	/**
	 * モデル名
	 *
	 * @var string
	 */
	public $name = 'BannerBreakpoint';

	/**
	 * beforeValiate
	 *
	 * @param array $options
	 */
	public function beforeValidate($options = array()) {
		if (!empty($this->data['BannerBreakpoint']['status'])
			&& empty($this->data['BannerBreakpoint']['name']))
		{
			$this->invalidate('name', '入力必須です。');
		}
		if (!empty($this->data['BannerBreakpoint']['status'])
			&& empty($this->data['BannerBreakpoint']['media_script']))
		{
			$this->invalidate('media_script', '入力必須です。');
		}
	}
}
