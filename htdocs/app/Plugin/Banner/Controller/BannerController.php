<?php
/**
 * [BANNER] コントローラ
 *
 * @copyright		Copyright 2014 - 2018, D-ZERO Co.,LTD.
 * @link			http://www.d-zero.co.jp/
 * @package			Banner
 * @license			MIT
 */
App::uses('Banner.BannerApp', 'Controller');
class BannerController extends BannerAppController {
	/**
	 * コントローラー名
	 *
	 * @var string
	 */
	public $name = 'Banner';
	
	/**
	 * モデル
	 *
	 * @var array
	 */
	public $uses = array('Banner.BannerFile', 'Banner.BannerArea', 'Banner.BannerBreakpoint');
	
	/**
	 * ヘルパー
	 *
	 * @var array
	 */
	public $helpers = array('Banner.Banner', 'BcUpload');
	
	/**
	 * コンポーネント
	 * 
	 * @var array
	 */
	public $components = array('BcAuth', 'Cookie', 'BcAuthConfigure');
	
	/**
	 * サブメニューエレメント
	 *
	 * @var array
	 */
	public $subMenuElements = array('banner');
	
	/**
	 * ぱんくずナビ
	 *
	 * @var array
	 */
	public $crumbs = array(
		array('name' => 'プラグイン管理', 'url' => array('plugin' => '', 'controller' => 'plugins', 'action' => 'index'))
	);
	
	/**
	 * beforeFilter
	 *
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		/* 認証設定 */
		$this->BcAuth->allow(
			'index', 'mobile_index', 'smartphone_index'
		);
	}

	/**
	 * 設定
	 */
	public function admin_config() {
		if (empty($this->request->data['BannerBreakpoint'])) {
			$bannerBreakPoints = $this->BannerBreakpoint->find('all');
			foreach ($bannerBreakPoints as $bannerBreakPoint) {
				$requestKey = $bannerBreakPoint['BannerBreakpoint']['id'] - 1;
				$this->request->data['BannerBreakpoint'][$requestKey] = $bannerBreakPoint['BannerBreakpoint'];
			}
		} else {
			$saveData = [];
			foreach ($this->request->data['BannerBreakpoint'] as $key => $bannerBreakPoint) {
				$bannerBreakPoint['id'] = $bannerBreakPoint['id'];
				$saveData[] = $bannerBreakPoint;
			}
			if ($this->BannerBreakpoint->saveMany($saveData)) {
				$this->setMessage('バナープラグイン設定を更新しました。', false, true);
			} else {
				$this->setMessage('入力エラーです。内容を修正して下さい。', true);
			}
		}

		$this->pageTitle = 'バナープラグイン設定';
	}
	
	/**
	 * バナー一覧を表示する
	 * 
	 * @param int $bannerArea
	 */
	public function index($bannerArea = null) {
		if (!$bannerArea) {
			$bannerArea = 1;
		}
		
		$conditions = array(
			'BannerFile.banner_area_id' => $bannerArea
		);
		$conditions = Hash::merge($conditions, $this->BannerFile->getConditionAllowPublish());
		$datas = $this->BannerFile->find('all', array(
			'conditions' => $conditions
		));
		
		// バナー画像の保存先パスを作成する
		$fileUrl = $this->webroot . 'files' .DS. $this->BannerFile->actsAs['BcUpload']['saveDir'] .DS;
		
		foreach ($datas as $key => $data) {
			if ($datas[$key]['BannerFile']['name']) {
				$datas[$key]['BannerFile']['name'] = $fileUrl . $datas[$key]['BannerFile']['name'];
			}
		}
		
		$this->set(compact('datas'));
		$this->layout = null;
	}
	
	/**
	 * [SMARTPHONE] バナー一覧を表示する
	 *
	 */
	public function smartphone_index() {
		$this->setAction('index');
	}
	
	/**
	 * [MOBILE] バナー一覧を表示する
	 *
	 */
	public function mobile_index() {
		$this->setAction('index');
	}

}
