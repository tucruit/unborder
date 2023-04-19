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

// GoogleMaps APIの取得
$googleMapsApiKey = BurgerEditorUtil::getGoogleMapApiKey();

if ($googleMapsApiKey) {
	$this->BcBaser->js(array('https://maps.google.com/maps/api/js?key=' . $googleMapsApiKey), array('inline' => false));
}
