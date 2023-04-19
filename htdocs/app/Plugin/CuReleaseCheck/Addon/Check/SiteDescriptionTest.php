<?php

/**
 * サイト基本説明文の設定チェック
 * → デフォルトから変更されているか・空じゃないか
 * 
 */
class SiteDescriptionTest implements CuReleaseCheckTestInterface {

	protected $result	 = false;
	protected $message	 = 'サイト基本説明文の設定';

	public function title() {
		return 'サイト基本説明文の設定チェック';
	}

	public function test() {
		$default = 'baserCMS は、CakePHPを利用し、環境準備の素早さに重点を置いた基本開発支援プロジェクトです。WEBサイトに最低限必要となるプラグイン、そしてそのプラグインを組み込みやすい管理画面、認証付きのメンバーマイページを最初から装備しています。';

		$SiteConfigModel = ClassRegistry::init('SiteConfig');
		$data			 = $SiteConfigModel->find('first', array(
			'conditions' => array(
				'SiteConfig.name' => 'description',
			),
			'recursive'	 => -1,
			'callbacks'	 => false,
			'cache'		 => false,
		));

		if (!Hash::get($data, 'SiteConfig.value')) {
			$this->message = 'サイト基本説明文が設定されていません。';
			return;
		}

		if (strpos($data['SiteConfig']['value'], $default) !== false) {
			$this->message = 'サイト基本説明文がデフォルトから変更されていません。';
		} else {
			$this->result	 = true;
			$this->message	 = 'サイト基本説明文は設定されています。';
		}
	}

	public function getResult() {
		return $this->result;
	}

	public function getMessage() {
		return $this->message;
	}

}
