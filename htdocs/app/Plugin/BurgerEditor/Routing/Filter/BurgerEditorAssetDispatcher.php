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
App::uses('AssetDispatcher', 'Routing/Filter');

/**
 * Class BurgerEditorAssetDispatcher
 */
class BurgerEditorAssetDispatcher extends AssetDispatcher {

/**
 * @var int
 */
	public $priority = 3;
/**
 * アセットファイルのパスを生成する
 *
 * @param string $url URL
 * @return string|null Absolute path for asset file
 */
	protected function _getAssetFile($url) {
		$parts = explode('/', $url);
		if(!($parts[1] === 'burger_editor' && $parts[2] === 'panel' && !empty($parts[3]) && preg_match('/\.png$/', $parts[3]))) {
			return null;
		}
		$plugin = Inflector::camelize($parts[0]);
		if ($plugin && CakePlugin::loaded($plugin)) {
			$block = preg_replace('/\.png$/', '', $parts[3]);
			$addonPath = BurgerEditorUtil::getAddonPath();
			foreach($addonPath as $path) {
				$panelPath = $path . 'block' . DS . $block . DS . 'panel.png';
				if(file_exists($panelPath)) {
					return $panelPath;
				}
			}
		}
	}
}
