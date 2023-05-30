<?php

App::uses('CakeRequest', 'Network');
App::uses('Controller', 'Controller');

/**
 * GoogleAnalyticsの設定チェック
 *  → トップページを確認して、analyticsのコードが入っているか？
 * 
 */
class GoogleAnalyticsTest implements CuReleaseCheckTestInterface {

	protected $result	 = false;
	protected $message	 = 'GoogleAnalyticsの設定チェック';

	public function title() {
		return 'GoogleAnalyticsの設定チェック';
	}

	public function test() {
		$SiteConfigModel = ClassRegistry::init('SiteConfig');
		$data			 = $SiteConfigModel->find('first', array(
			'conditions' => array(
				'SiteConfig.name' => 'google_analytics_id',
			),
			'recursive'	 => -1,
			'callbacks'	 => false,
			'cache'		 => false,
		));

		if (!Hash::get($data, 'SiteConfig.value')) {
			$this->message = 'トラッキングIDが設定されていません。';
			return;
		}

		if (Configure::read('debug') > -1) {
			clearAllCache();
		}

		$siteUrl	 = Router::url('/', true);
		$contents	 = file_get_contents($siteUrl);
		if (!$contents) {
			$this->message = 'トップページの内容が取得できません。';
			return;
		}

		// 従来の ga('create'判定　
		// gtag('config'判定 ADD 2018/11
		if (strpos($contents, "ga('create'") === false && strpos($contents, "gtag('config'") === false) {
			$this->message = 'アナリティクスタグが確認できません。';
			return;
		}

		// トラッキングコード判別
		if (preg_match_all("/(ga\(\'create',|gtag\(\'config',|)\s*?'(UA-[0-9\-]+?).*/", $contents, $matches)) {
			$multiTag	 = '';
			foreach ($matches[0] as $tag) {
				$multiTag .= $tag;
			}
			$this->message = '設定されています。<br>';
			$this->message .= $multiTag;
			$this->result = true;
		} else {
			$this->message = '正しい値が設定されていません。（「UA-」で始まる文字列が正しいです。）<br>';
			$this->message .= $data['SiteConfig']['value'];
		}
	}

	public function getResult() {
		return $this->result;
	}

	public function getMessage() {
		return $this->message;
	}

}
