<?php
/**
 * [BANNER][ControllerEventListener] バナー管理
 *
 * @copyright		Copyright 2014 - 2018, D-ZERO Co.,LTD.
 * @link			http://www.d-zero.co.jp/
 * @package			Banner
 * @license			MIT
 */
class BannerControllerEventListener extends BcControllerEventListener {
	/**
	 * 登録イベント
	 *
	 * @var array
	 */
	public $events = array(
		'initialize',
		'Banner.BannerFiles.beforeRender'
		);

	/**
	 * initialize
	 * 
	 * @param CakeEvent $event
	 */
	public function initialize(CakeEvent $event) {
		$Controller = $event->subject();
		$Controller->helpers[] = 'Banner.Banner';
	}

	/**
	 * bannerBannerFileBeforeRender
	 * 
	 * @param CakeEvent $event
	 */
	public function bannerBannerFilesBeforeRender(CakeEvent $event) {
		$Controller = $event->subject();
		if (!empty($Controller->BannerFile->validationErrors)) {
			if (@$Controller->BannerFile->validationErrors['name'][0] == '設定値より大きい縦横サイズの画像です。') {
				// バナーエリアの設定値を取得する
				$limitWidth = $Controller->bannerArea['BannerArea']['width'];
				$limitHeight = $Controller->bannerArea['BannerArea']['height'];
				$message = '横'. $limitWidth .'ピクセル、縦'. $limitHeight .'ピクセルより小さい画像を登録してください。';

				// BcAppControllerのsetMessageがprotectedになった為変更
//				$Controller->setMessage($message, true);
				$Controller->Flash->set($message, [
					'element' => 'default',
					'params' => ['class' => 'alert-message']
				]);
			}
		}
	}

}
