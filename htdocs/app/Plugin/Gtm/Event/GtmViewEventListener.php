<?php
class GtmViewEventListener extends BcViewEventListener {
/**
 * 登録イベント
 *
 * @var array
 */
	public $events = array(
		'afterLayout'
	);

	public function afterLayout(CakeEvent $event) {
		// 自動追加がfalseなら何もしない。
		if (!Configure::read('Gtm.auto')) {
			return true;
		}
		$View = $event->subject;
		// site_configテーブルに保存されたGtm.keyのレコードを取得
		$siteConfig = $View->BcBaser->siteConfig;
		// noticeエラー解消
		if (empty($siteConfig[Configure::read('Gtm.keyName')])) {
			return true;
		} else {
			$key = $siteConfig[Configure::read('Gtm.keyName')];
		}

		if (strpos($key, 'GTM-') === false) {
			$key = 'GTM-'. $key;
		}

		$this->headGtm($View, $key);
		$this->bodyGtm($View, $key);
		return true;
	}

	/*
	 * headタグ内にGTMタグを追加
	 */
	protected function headGtm($View, $key){
		$output = $View->output;
		$matches = array();
		if (preg_match_all('/<head>/i', $output, $matches) == false) {
			preg_match_all('/<meta.*?charset=\".*>/i', $output, $matches);
		}
		if (!empty($matches) && isset($matches[0][0])) {
			// 重複を避ける
			$headIgnore = Configure::read('Gtm.headIgnore');
			if (preg_match_all($headIgnore, $output, $gtmStart) == false) {
				$gtmHead = $View->element('Gtm.gtm_head', ['key' => $key]);
				$gmApiUrl = $matches[0][0];
				$gtmTag = $matches[0][0] == '<head>' ? $matches[0][0]. "\n" .$gtmHead : $gtmHead. "\n" . $matches[0][0];
				$View->output = str_replace($matches[0][0],$gtmTag, $output);
			}
		}
	}

	/*
	 * body開始タグ直後にGTMタグを追加
	 */
	protected function bodyGtm($View, $key){
		$output = $View->output;
		$matches = array();
		if (preg_match_all('/<body>/i', $output, $matches) == false) {
			preg_match_all('/<body.*>/i', $output, $matches);
		}
		if (!empty($matches) && isset($matches[0][0])) {
			// 重複を避ける
			$bodyIgnore = Configure::read('Gtm.bodyIgnore');
			if (preg_match_all($bodyIgnore, $output, $gtmStart) == false) {
				$gtmBody = $View->element('Gtm.gtm_body', ['key' => $key]);
				$View->output = str_replace($matches[0][0], $matches[0][0]. "\n" . $gtmBody, $output);
			}
		}

	}

}
