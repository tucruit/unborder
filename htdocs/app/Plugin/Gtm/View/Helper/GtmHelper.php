<?php
/**
 * [GTM] Google Tag Manager コンテナID設定ヘルパー
 *
 * @package			gtm.views
 * @license			MIT
 */
class GtmHelper extends AppHelper {

/**
 * ヘルパー
 *
 * @var array
 */
	public $helpers = array('BcBaser', 'BcHtml', 'Html');

/**
 * head内GTMタグを出力する。
 * $this->Gtm->headGtm() で呼び出す。
 */
	public function headGtm(){
		$key = $this->BcBaser->siteConfig[Configure::read('Gtm.keyName')];
		if (empty($key)) {
			return true;
		}
		if (strpos($key, 'GTM-') === false) {
			$key = 'GTM-'. $key;
		}
		$this->BcBaser->element('Gtm.gtm_head', ['key' => $key]);
	}
/**
 * body直後のGTMタグを出力する。
 * $this->Gtm->bodyGtm() で呼び出す。
 */
	public function bodyGtm(){
		$key = $this->BcBaser->siteConfig[Configure::read('Gtm.keyName')];
		if (empty($key)) {
			return true;
		}
		if (strpos($key, 'GTM-') === false) {
			$key = 'GTM-'. $key;
		}
		$this->BcBaser->element('Gtm.gtm_body', ['key' => $key]);
	}
}
