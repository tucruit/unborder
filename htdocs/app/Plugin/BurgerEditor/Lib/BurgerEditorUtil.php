<?php

class BurgerEditorUtil {

	/**
	 * GoogleMapAPI Keyを取得
	 *
	 * GoogleMapsApiKeyPacher プラグイン、および、
	 * baserCMS 3.0.11 以降の仕様に対応
	 *
	 * @return string
	 */
	public static function getGoogleMapApiKey() {

		$siteConfigs = Configure::read('BcSite');
		if (isset($siteConfigs['google_maps_api_key']) &&
			!empty($siteConfigs['google_maps_api_key'])) {

			// baserCMS 3.0.11 以降の仕様
			$googleMapsApiKey = $siteConfigs['google_maps_api_key'];

		} elseif (isset($siteConfigs['GoogleMapsApiKeyPacher.key']) &&
			!empty($siteConfigs['GoogleMapsApiKeyPacher.key'])) {

			// GoogleMapsApiKeyPacher プラグインの仕様
			$googleMapsApiKey = $siteConfigs['GoogleMapsApiKeyPacher.key'];

		} else {
			// キーが取得できない場合
			$googleMapsApiKey = '';

		}

		return $googleMapsApiKey;
	}

	/**
	 * 静的ファイルに対するサフィックスを取得する
	 *
	 * @param string filePath
	 * @return string
	 */
	public static function getSuffix($filePath) {
		if (!Configure::read('Bge.enableStaticFileSuffix')) {
			return '';
		}

		$modifiedTime = filemtime($filePath);
		if (!$modifiedTime) {
			return '';
		}
		$suffix = '?' . $modifiedTime;

		$suffixText = Configure::read('Bge.staticFileSuffix');
		if ($suffixText) {
			return $suffix .= '-' . $suffixText;
		}

		return $suffix;
	}

	/**
	 * Addon のパスを取得する
	 * @return string[]
	 */
	public static function getAddonPath() {
		$addonDir = array(dirname(dirname(__FILE__)).DS.'Addon'.DS);
		$enableAddonPlugin = Configure::read('Bge.enableAddonPlugin');
		if($enableAddonPlugin) {
			foreach($enableAddonPlugin as $plugin) {
				if(!CakePlugin::loaded($plugin)) {
					continue;
				}
				$plguinPath = CakePlugin::path($plugin);
				$pluginAddonPath = $plguinPath . 'BurgerAddon' . DS ;
				if(is_dir($pluginAddonPath)) {
					$addonDir[] = $pluginAddonPath;
				}
			}
		}
		return $addonDir;
	}

	/**
	 * タイプのパスを取得する
	 * @param string $typeName
	 * @return bool|string
	 */
	public static function getTypePath($typeName) {
		$addonPath = self::getAddonPath();
		foreach($addonPath as $path) {
			$path = $path . 'type' . DS . $typeName . DS;
			if (is_dir($path)) {
				return $path;
			}
		}
		return false;
	}
	/**
	 * ブロックのパスを取得する
	 * @param string $typeName
	 * @return bool|string
	 */
	public static function getBlockPath($blockName) {
		$addonPath = self::getAddonPath();
		foreach($addonPath as $path) {
			$path = $path . 'block' . DS . $blockName . DS;
			if (is_dir($path)) {
				return $path;
			}
		}
		return false;
	}

}
