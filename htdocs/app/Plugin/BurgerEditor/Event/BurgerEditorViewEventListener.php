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
class BurgerEditorViewEventListener extends BcViewEventListener {
/**
 * 登録イベント
 *
 * @var array
 */
	public $events = array(
		'beforeLayout',
		'afterLayout'
	);

/**
 * beforeLayout
 *
 * @param CakeEvent $event
 * @return boolean
 */
	public function beforeLayout(CakeEvent $event) {
		$View = $event->subject;

		// GoogleMaps APIの取得
		$googleMapsApiKey = BurgerEditorUtil::getGoogleMapApiKey();

		// 管理画面はJS・CSSの自動的用を除外
		if(BcUtil::isAdminSystem()) {
			// プレビューは適用
			if (($View->request['controller'] == 'pages' && $View->request['action'] == 'display')
					|| ($View->request['plugin'] == 'blog' && $View->request['action'] == 'archives')) {
			} else {
				return true;
			}
		}

		// 除外レイアウト
		$excdlueViewPath = array(
			'Emails/text',	// メール
			'Blog/rss',		// ブログRSS
			'Feed',			// フィード
		);
		if (in_array($View->viewPath, $excdlueViewPath)) {
			return true;
		}

		// ユーザ(サイト制作者)定義CSSの自動読込
		$userCssList = array();
		$userCssList[] = "BurgerEditor.bge_style_default";

		$version = $View->BurgerEditor->getMajorVersionOfSystem();

		if ($version < 4) {
			// baserCMS3系までの対応
			$themeDirectory = Configure::read('BcSite.theme');
		} else {
			// baserCMS4系対応
			$site = BcSite::findCurrent();
			$themeDirectory = $site->theme;
		}
		// themeのcss優先
		if (file_exists(WWW_ROOT . 'theme' . DS . $themeDirectory . DS . 'css' . DS . 'bge_style.css')) {
			$path = WWW_ROOT . 'theme' . DS . $themeDirectory . DS . 'css' . DS . 'bge_style.css';
			$userCssList[] = 'bge_style.css' . BurgerEditorUtil::getSuffix($path);;
		// themeになく、webroot/cssにあれば読込
		} elseif (file_exists(WWW_ROOT . 'css' . DS . 'bge_style.css')) {
			$path = WWW_ROOT . 'css' . DS . 'bge_style.css';
			$userCssList[] = 'bge_style.css' . BurgerEditorUtil::getSuffix($path);
		// themeになく、webroot/cssにもない場合、プラグイン標準のファイルを読み込む
		} else {
			$path = WWW_ROOT . 'app' . DS . 'Plugin' . DS . 'BurgerEditor' . DS . 'webroot' . DS . 'css' . DS . 'bge_style.css';
			$userCssList[] = 'BurgerEditor.bge_style' . BurgerEditorUtil::getSuffix($path);
		}
		// colorbox用スタイル
		$userCssList[] = 'BurgerEditor.colorbox';
		$View->BcBaser->css($userCssList, array('inline' => false));

		// JSの自動読込
		$jsList = array();

		// bge_functions.min.jsの読み込み
		$jsList[] = 'BurgerEditor.bge_modules/bge_functions.min.js';
		// jquery.colorbox.jsの自動読み込み
		$jsList[] = 'BurgerEditor.bge_modules/jquery.colorbox-min.js';

		if ($jsList) $View->BcBaser->js($jsList, false, array('defer' => 'defer'));

		// google map APIの読込
		if ($googleMapsApiKey) {
			$googleMapsAPIURL = '//maps.google.com/maps/api/js?key=' . $googleMapsApiKey;
			$View->BcBaser->js($googleMapsAPIURL, false, array('defer' => 'defer'));
		}


		// 表示が不要なBurgerEditorのhidden要素を削除
		if (class_exists('DOMDocument')) {
			// baserCMSが出力するコードの取得
			$matches = null;
			$output = $View->output;
			preg_match('/<\!\-\- BaserPageTagBegin \-\->.*?<\!\-\- BaserPageTagEnd \-\->/is', $output, $matches);

			$bcPageTag = empty($matches[0]) ? '' : $matches[0];
			$output = preg_replace('/<\!\-\- BaserPageTagBegin \-\->.*?<\!\-\- BaserPageTagEnd \-\->/is', '', $output);

			// TODO baserCMSがPHP5.4以上のみをサポートするようになると、Goutterに移行予定
			App::import('Vendor', 'BurgerEditor.simple_html_dom');
			$dom = new simple_html_dom();
			$dom->load($output, null, false);
			$bgbList = $dom->find('[data-bgb]');



			// キャッシュ時間判別
			// ブログの場合はそもそもキャッシュを利用しないのでfalse
			// viewキャッシュを利用する場合はviewDurationの値が入る
			// 固定ページでviewDurationより短い更新時間が設定されていればより短い切り替え時間がないか走査する
			$nearlyCacheTime = $View->cacheAction;
			$useCache = false;
			if (Configure::read('Bge.publishTimer') && $nearlyCacheTime !== false) {
				$nearlyCacheTime = $View->cacheAction;
				$useCache = true;
			}

			// BurgerEditorのコンテンツの場合
			if (count($bgbList)) {
				foreach ($bgbList as $bgb) {
					// hidden 削除
					foreach ($bgb->find('input[type=hidden]') as $hidden) {
						$hidden->outertext = '';
					}
					// embed typeの変換
					foreach ($bgb->find('[data-bgt=embed]') as $embed) {
						$inject = '';
						foreach($embed->find('[data-bge=embed-code]') as $code) {
							$inject .= base64_decode($code->text());
						}
						$embed->outertext = $inject;
					}
					// 公開日時の変換
					if (Configure::read('Bge.publishTimer')) {
						// previewかつdate検証指定
						if (isset($View->request->query['preview']) && isset($View->request->query['bgtimerdate'])) {
							$bgPublishTimerDate = strtotime($View->request->query['bgtimerdate']);
						} else {
							$bgPublishTimerDate = time();
						}
						foreach ($bgb->attr as $attrName => $attrVal) {
							$updateTime = false;
							if ($attrName === 'data-bgb-publish-datetime') {
								if (strtotime($attrVal) > $bgPublishTimerDate) {
									$bgb->outertext = '';
								}
								// 切り替え時間が未来の時間かつキャッシュ有効時間より短い場合は更新
								$updateTime = strtotime($attrVal) - $bgPublishTimerDate;
								if ($useCache && $updateTime > 0 && $updateTime < $nearlyCacheTime) {
									$nearlyCacheTime = $updateTime;
								}
							}
							if ($attrName === 'data-bgb-unpublish-datetime') {
								if (strtotime($attrVal) <= $bgPublishTimerDate) {
									$bgb->outertext = '';
								}
								// 切り替え時間が未来の時間かつキャッシュ有効時間より短い場合は更新
								$updateTime = strtotime($attrVal) - $bgPublishTimerDate;
								if ($useCache && $updateTime > 0 && $updateTime < $nearlyCacheTime) {
									$nearlyCacheTime = $updateTime;
								}
							}
						}
					}
				}
				// キャッシュ更新時間変更
				if ($useCache && $nearlyCacheTime !== $View->cacheAction && !isset($View->request->query['preview'])) {
					$View->cacheAction = $nearlyCacheTime;
				}
			}


			if (Configure::read('Bge.autoWrapper')) {
				$View->output = '<div class="bge-contents">' . $bcPageTag . $dom . '</div>';
			} else {
				$View->output = $bcPageTag . $dom;
			}

			unset($dom);

		} else {

			if (strpos($View->output, 'data-bgb')) {
				if (Configure::read('Bge.autoWrapper')) {
					$View->output = '<div class="bge-contents">' . $View->output . '</div>';
				} else {
				}
			}
			// Domパーサが利用できない場合は正規表現で書き換え
			if (preg_match_all("/<div class=\"bge-embed-code\".*?>(.*?)<\/div>/", $View->output, $matches)) {
				if (isset($matches[0]) && $matches[1]) {
					$View->output = str_replace($matches[0], array_map("base64_decode", $matches[1]) , $View->output);
				}
				$View->output = preg_replace("/<div class=\"bge-embed-label\".*?<\/div>/", "", $View->output);
			}
		}

		return true;

	}

	/**
	 * afterLayout
	 *
	 * @param CakeEvent $event
	 * @return boolean
	 */
	public function afterLayout(CakeEvent $event) {
		$View = $event->subject;

		// google map apiが２回呼ばれていたら、後で呼ばれたものを削除する
		$output = $View->output;
		if (preg_match_all('/<script.*?<\/script>/i', $output, $matches)) {
			$times = 0;
			$gmList = array();
			foreach($matches[0] as $match) {
				if (preg_match('/src=("|\').*?\/\/maps\.google\.com\/maps\/api\/js/i', $match, $gmMatchs)) {
					$times++;
					$gmList[] = $match;
				}
			}
			// 書き換え
			if ($times >= 2) {
				foreach($gmList as $i => $gmScript) {
					$output = preg_replace("/" . preg_quote($gmScript, "/") . "/", "__REPACE_MAP{$i}__", $output, 1);
				}
				foreach($gmList as $i => $gmScript) {
					if ($i === 0) {
						$output = str_replace("__REPACE_MAP{$i}__", $gmScript, $output);
					} else {
						$output = str_replace("__REPACE_MAP{$i}__", '<!-- delete google map api calling by BGE -->', $output);
					}
				}
				$View->output = $output;

			}
		}
		return true;
	}
}
