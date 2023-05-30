<?php
/**
 * [BANNER] バナーエリア管理
 *
 * @copyright		Copyright 2014 - 2018, D-ZERO Co.,LTD.
 * @link			http://www.d-zero.co.jp/
 * @package			Banner
 * @license			MIT
 */
App::uses('Banner.BannerApp', 'Controller');
class BannerAreasController extends BannerAppController {
	/**
	 * コントローラー名
	 * 
	 * @var string
	 */
	public $name = 'BannerAreas';

	/**
	 * モデル
	 * 
	 * @var array
	 */
	public $uses = array('Banner.BannerArea', 'Banner.BannerFile', 'Banner.BannerBreakpoint');

	/**
	 * ぱんくずナビ
	 *
	 * @var array
	 */
	public $crumbs = array(
		array('name' => 'プラグイン管理', 'url' => array('plugin' => '', 'controller' => 'plugins', 'action' => 'index')),
		array('name' => 'バナーエリア管理', 'url' => array('plugin' => 'banner', 'controller' => 'banner_areas', 'action' => 'index'))
	);

	/**
	 * Help表示
	 * 
	 * @var string 
	 */
	public $help = 'banner_areas';

	/**
	 * メッセージ用機能名
	 * 
	 * @var string
	 */
	public $controlName = 'バナーエリア';

	/**
	 * [ADMIN] 一覧表示
	 * 
	 */
	public function admin_index() {
		$this->search = 'banner_areas_index';
		parent::admin_index();
	}

	/**
	 * [ADMIN] 追加
	 * 
	 */
	public function admin_add() {
		$this->set('breakpoints', $this->BannerBreakpoint->find('all'));
		parent::admin_add();
	}

	/**
	 * [ADMIN] 編集
	 * 
	 * @param int $id
	 */
	public function admin_edit($id = null) {
		$this->set('breakpoints', $this->BannerBreakpoint->find('all'));
		parent::admin_edit($id);
	}

	/**
	 * [ADMIN] 削除
	 *
	 * @param int $id
	 */
	public function admin_delete($id = null) {
		$this->_delete_banner_files($id);
		parent::admin_delete($id);
	}

	/**
	 * [ADMIN] 削除処理 (ajax)
	 *
	 * @param int $id
	 */
	public function admin_ajax_delete($id = null) {
		$this->_delete_banner_files($id);
		parent::admin_ajax_delete($id);
	}

	/**
	 * [ADMIN] コピー (ajax)
	 * 
	 * @param int $id 
	 */
	public function admin_ajax_copy($id) {
		parent::admin_ajax_copy($id);
	}

	/**
	 * バナーエリアに属しているバナーを削除する
	 * 
	 * @param int $id
	 */
	public function _delete_banner_files($id = null) {
		if ($id) {
			$datas = $this->BannerFile->find('all', array(
				'conditions' => array('BannerFile.banner_area_id' => $id)
			));
			if ($datas) {
				foreach ($datas as $data) {
					$this->BannerFile->delete($data['BannerFile']['id']);
				}
			}
		}
	}

	/**
	 * 一覧用の検索条件を生成する
	 *
	 * @param array $data
	 * @return array $conditions
	 */
	protected function _createAdminIndexConditions($data) {
		$conditions = array();
		$name = '';
		
		if (isset($data['BannerArea']['name'])) {
			$name = $data['BannerArea']['name'];
		}
		
		unset($data['_Token']);
		unset($data['BannerArea']['name']);
		
		// 条件指定のないフィールドを解除
		foreach ($data['BannerArea'] as $key => $value) {
			if ($value === '') {
				unset($data['BannerArea'][$key]);
			}
		}
		
		if ($data['BannerArea']) {
			$conditions = $this->postConditions($data);
		}
		
		if ($name) {
			$conditions[] = array(
				'BannerArea.name LIKE' => '%'.$name.'%'
			);
		}
		
		if ($conditions) {
			return $conditions;
		} else {
			return array();
		}
	}

}
