<?php
class SiteKeywordTest implements CuReleaseCheckTestInterface {
	protected $result = false;
	protected $message = "サイト基本キーワードが正しく設定されていません。";
	
	public function title() {
		return 'サイト基本キーワードの設定チェック';
	}

	// テスト実行処理
	public function test() {
		$defaultKeyword = 'baser,CMS,コンテンツマネジメントシステム,開発支援';
		$siteConfigMdl = ClassRegistry::init('SiteConfig');
		$siteKeyword = $siteConfigMdl->find('first', array(
			'conditions' => array(
				'SiteConfig.name' => 'keyword',
			),
			'cache' => false
		));
		
		if (!empty($siteKeyword['SiteConfig']['value'])
			&& strpos($siteKeyword['SiteConfig']['value'], $defaultKeyword) === false ){
			$this->result = true;
			$this->message = 'サイト基本キーワードは正しく設定されています。';
		}
	}
	
	// データ取得処理
	public function getResult() {
		return $this->result;
	}

	public function getMessage() {
		return $this->message;
	}

}
