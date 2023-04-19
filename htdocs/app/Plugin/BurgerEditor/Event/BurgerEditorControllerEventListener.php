<?php
/**
 * BurgerEditor <baserCMS plugin>
 *
 * @copyright		Copyright 2013 -, D-ZERO Co.,LTD.
 * @link			https://www.d-zero.co.jp/
 * @package			burger_editor
 * @since			Baser v 3.0.0
 * @license			https://market.basercms.net/files/baser_market_license.pdf
 */
class BurgerEditorControllerEventListener extends BcControllerEventListener {

/**
 * 登録イベント
 *
 * @var array
 */
	public $events = array(
		'initialize',
	);

/**
 * initialize
 * 利用Helperの追加
 *
 * @param CakeEvent $event
 */
	public function initialize(CakeEvent $event) {
		$Controller = $event->subject();
		$Controller->helpers[] = 'BurgerEditor.BurgerEditor';
	}

}
