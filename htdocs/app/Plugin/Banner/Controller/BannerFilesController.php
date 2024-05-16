<?php
/**
 * [BANNER] バナー管理
 *
 * @copyright		Copyright 2014 - 2018, D-ZERO Co.,LTD.
 * @link			http://www.d-zero.co.jp/
 * @package			Banner
 * @license			MIT
 */
App::uses('Banner.BannerApp', 'Controller');
class BannerFilesController extends BannerAppController
{
	/**
	 * コントローラー名
	 *
	 * @var string
	 */
	public $name = 'BannerFiles';

	/**
	 * モデル
	 *
	 * @var array
	 */
	public $uses = array('Banner.BannerFile', 'Banner.BannerArea', 'Banner.BannerBreakpoint');

	/**
	 * ぱんくずナビ
	 *
	 * @var string
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
	public $help = 'banner_files';

	/**
	 * メッセージ用機能名
	 *
	 * @var string
	 */
	public $controlName = 'バナー';

	/**
	 * バナーエリア情報
	 *
	 * @var array
	 */
	public $bannerArea = null;

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

		if (isset($this->params['pass'][0])) {
			$this->bannerArea = $this->BannerArea->read(null, $this->params['pass'][0]);
			$this->crumbs[] = array('name' => $this->bannerArea['BannerArea']['name'] . '管理', 'url' => array('controller' => 'banner_areas', 'action' => 'index', $this->params['pass'][0]));
		}
	}

	/**
	 * beforeRender
	 *
	 * @return void
	 */
	public function beforeRender() {
		parent::beforeRender();
		$this->set('bannerArea', $this->bannerArea);
		$this->set('statuses', array(0 => '非公開', 1 => '公開'));
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
		$conditions = Set::merge($conditions, $this->BannerFile->getConditionAllowPublish());
		$datas = $this->BannerFile->find('all', array(
			'conditions' => $conditions
		));
		
		// バナー画像の保存先パスを作成する
		$fileUrl = $this->webroot . 'files' .DS. $this->BannerFile->actsAs['Banner.BcUpload']['saveDir'] .DS;

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
	 * @return void
	 */
	public function smartphone_index() {
		$this->setAction('index');
	}

	/**
	 * [MOBILE] バナー一覧を表示する
	 *
	 * @return void
	 */
	public function mobile_index() {
		$this->setAction('index');
	}

	/**
	 * [ADMIN] 一覧表示
	 *
	 * @param int $bannerAreaId
	 */
	public function admin_index($bannerAreaId = null) {
		if (!$bannerAreaId || !$this->bannerArea) {
			$this->setMessage('無効な処理です。', true);
			$this->redirect(array('controller' => 'banner_areas', 'action' => 'index'));
		}

		$default = array('named' => array('num' => $this->siteConfigs['admin_list_num'], 'sortmode' => 0));
		$this->setViewConditions('BannerFile', array('group' => $bannerAreaId, 'default' => $default));

		// 並び替えモードの場合は、強制的にsortフィールドで並び替える
		if ($this->passedArgs['sortmode']) {
			$this->passedArgs['sort'] = 'sort';
			$this->passedArgs['direction'] = 'asc';
		}

		$conditions = $this->_createAdminIndexConditions($bannerAreaId, $this->request->data);
		$options = array(
			'conditions' => $conditions,
			'order'	=> 'BannerFile.sort ASC',
			'limit'	=> $this->passedArgs['num'],
			'recursive' => 2
		);

		// EVENT BannerFiles.searchIndex
		$event = $this->dispatchEvent('searchIndex', array(
			'options' => $options
		));
		if ($event !== false) {
			$options = ($event->result === null || $event->result === true) ? $event->data['options'] : $event->result;
		}

		$this->paginate = $options;
		$this->set('datas', $this->paginate('BannerFile'));
		if (!isset($this->passedArgs['sortmode'])) {
			$this->passedArgs['sortmode'] = false;
		}
		$this->set('sortmode', $this->passedArgs['sortmode']);

		if ($this->RequestHandler->isAjax() || !empty($this->params['url']['ajax'])) {
			$this->render('ajax_index');
			return;
		}

		$this->pageTitle = '[' . $this->bannerArea['BannerArea']['name'] . '] バナー一覧';
		$this->search = 'banner_files_index';
	}

	/**
	 * [ADMIN] 追加
	 *
	 * @param int $bannerAreaId
	 */
	public function admin_add($bannerAreaId = null) {
		if (!$bannerAreaId || !$this->bannerArea) {
			$this->setMessage('無効な処理です。', true);
			$this->redirect(array('controller' => 'banner_areas', 'action' => 'index'));
		}

		if (empty($this->request->data)) {
			$this->request->data = $this->BannerFile->getDefaultValue();
		} else {
			/* 登録ユーザを保存 */
			$user = BcUtil::loginUser();
			$this->request->data['BannerFile']['user_id'] = $user['id'];
			$this->request->data['BannerFile']['banner_area_id'] = $bannerAreaId;
			$this->request->data['BannerFile']['no'] = $this->BannerFile->getMax('no', array('BannerFile.banner_area_id' => $bannerAreaId)) + 1;
			$this->request->data['BannerFile']['sort'] = $this->BannerFile->getMax('sort') + 1;

			// EVENT BannerFiles.beforeAdd
			$event = $this->dispatchEvent('beforeAdd', array(
				'data' => $this->request->data
			));
			if ($event !== false) {
				$this->request->data = $event->result === true ? $event->data['data'] : $event->result;
			}

			$this->BannerFile->create($this->request->data);

			if ($this->BannerFile->save()) {
				$id = $this->BannerFile->getLastInsertId();
				$insertData = $this->BannerFile->read(null, $id);
				clearAllCache();
				$message = 'バナー「' . $insertData['BannerFile']['name'] . '」を追加しました。';
				$this->setMessage($message, false, true);

				// EVENT BannerFiles.afterAdd
				$this->dispatchEvent('afterAdd', array(
					'data' => $this->BannerFile->find('first', ['conditions' => ['BannerFile.id' => $bannerAreaId]])
				));

				$this->redirect(array('action' => 'index', $bannerAreaId));
			} else {
				$this->setMessage('エラーが発生しました。内容を確認してください。', true);
			}
		}
		
		$this->set('bannerAreaId', $this->bannerArea['BannerArea']['id']);

		$this->set('users', $this->User->getUserList());

		$this->set('breakpoints', $this->BannerBreakpoint->find('all'));

		$this->pageTitle = '[' . $this->bannerArea['BannerArea']['name'] . '] 新規バナー登録';
		$this->help = 'banner_files_form';
		$this->render('form');
	}

	/**
	 * [ADMIN] 編集
	 *
	 * @param int $id
	 */
	public function admin_edit($bannerAreaId = null, $id = null) {
		if (!$bannerAreaId || !$id) {
			$this->setMessage('無効な処理です。', true);
			$this->redirect(array('action' => 'index'));
		}
		if (empty($this->request->data)) {
			$this->BannerFile->id = $id;
			$this->request->data = $this->BannerFile->read();
		} else {

			// EVENT BannerFiles.beforeEdit
			$event = $this->dispatchEvent('beforeEdit', array(
				'data' => $this->request->data
			));
			if ($event !== false) {
				$this->request->data = $event->result === true ? $event->data['data'] : $event->result;
			}

			$this->BannerFile->set($this->request->data);
			$result = $this->BannerFile->save();
			if ($result) {
				clearAllCache();
				$message = $this->controlName . ' NO.' . $result['BannerFile']['no'] . ' を更新しました。';
				$this->setMessage($message, false, true);

				// EVENT BannerFiles.afterEdit
				$this->dispatchEvent('afterEdit', array(
					'data' => $this->BannerFile->find('first', ['conditions' => ['BannerFile.id' => $bannerAreaId]])
				));

				$this->redirect(array('action' => 'index', $bannerAreaId));
			} else {
				// 既に登録済みの画像がある時に表示するようにデータを戻す
				if (isset($this->request->data['BannerFile']['name_']) && 
					!empty($this->request->data['BannerFile']['name_'])) {
					$this->request->data['BannerFile']['name'] = $this->request->data['BannerFile']['name_'];
				}
				// <<<
				$this->setMessage('入力エラーです。内容を修正して下さい。', true);
			}
		}

		// ユーザー一覧
		$this->set('users', $this->User->getUserList());

		$this->set('breakpoints', $this->BannerBreakpoint->find('all'));

		$this->pageTitle = $this->controlName . '編集';
		$this->help = 'banner_files_form';
		$this->render('form');
	}

	/**
	 * [ADMIN] 削除
	 * 
	 * @param int $bannerAreaId
	 * @param int $id
	 */
	public function admin_delete($bannerAreaId = null, $id = null) {
		if (!$bannerAreaId || !$id) {
			$this->setMessage('無効な処理です。', true);
			$this->redirect(array('controller' => 'banner_areas', 'action' => 'index'));
		}

		if ($this->{$this->modelClass}->delete($id)) {
			$message = $this->controlName . ' NO.' . $id . ' を削除しました。';
			$this->setMessage($message, false, true);
			clearAllCache();
			$this->redirect(array('action' => 'index', $bannerAreaId));
		} else {
			$this->setMessage('データベース処理中にエラーが発生しました。', true);
		}
		$this->redirect(array('action' => 'index', $bannerAreaId));
	}

	/**
	 * [ADMIN] 削除処理　(ajax)
	 *
	 * @param int $bannerAreaId
	 * @param int $id
	 */
	public function admin_ajax_delete($bannerAreaId = null, $id = null) {
		if (!$bannerAreaId) {
			$this->ajaxError(500, '無効な処理です。');
		}
		clearAllCache();
		parent::admin_ajax_delete($id);
	}

	/**
	 * [ADMIN] コピー (ajax)
	 *
	 * @param int $id
	 */
	public function admin_ajax_copy($bannerAreaId = null, $id = null) {
		if (!$bannerAreaId) {
			$this->ajaxError(500, '無効な処理です。');
		}

		$result = $this->BannerFile->copy($bannerAreaId, $id);
		clearDataCache();
		if ($result) {
			$this->setViewConditions($this->modelClass, array('action' => 'admin_index'));
			$this->set('data', $result);
		} else {
			$this->ajaxError(500, $this->BannerFile->validationErrors);
		}

		if (!isset($this->passedArgs['sortmode'])) {
			$this->passedArgs['sortmode'] = false;
		}
		$this->set('sortmode', $this->passedArgs['sortmode']);
	}

	/**
	 * [ADMIN] 有効状態にする（AJAX）
	 *
	 * @param int $bannerAreaId
	 * @param int $id
	 */
	public function admin_ajax_publish($bannerAreaId, $id) {
		if (!$bannerAreaId) {
			$this->ajaxError(500, '無効な処理です。');
		}

		if ($this->_changeStatus($id, true)) {
			clearAllCache();
			exit(true);
		} else {
			$this->ajaxError(500, $this->{$this->modelClass}->validationErrors);
		}
		exit();
	}

	/**
	 * [ADMIN] 無効状態にする（AJAX）
	 *
	 * @param int $bannerAreaId
	 * @param int $id
	 */
	public function admin_ajax_unpublish($bannerAreaId, $id) {
		if (!$bannerAreaId) {
			$this->ajaxError(500, '無効な処理です。');
		}

		if ($this->_changeStatus($id, false)) {
			clearAllCache();
			exit(true);
		} else {
			$this->ajaxError(500, $this->{$this->modelClass}->validationErrors);
		}
		exit();
	}

	/**
	 * [ADMIN] 並び替えを更新する（AJAX）
	 *
	 * @param int $bannerAreaId
	 * @return boolean
	 */
	public function admin_ajax_update_sort ($bannerAreaId) {
		if ($this->request->data){
			$this->setViewConditions('BannerFile', array('group' => $bannerAreaId, 'action' => 'admin_index'));
			$conditions = $this->_createAdminIndexConditions($bannerAreaId, $this->request->data);
			if ($this->BannerFile->changeSort($this->request->data['Sort']['id'],$this->request->data['Sort']['offset'],$conditions)){
				echo true;
				clearAllCache();
			} else {
				$this->ajaxError(500, '一度リロードしてから再実行してみてください。');
			}
		} else {
			$this->ajaxError(500, '無効な処理です。');
		}
		exit();
	}

	/**
	 * 一覧用の検索条件を生成する
	 *
	 * @param array $data
	 * @return array $conditions
	 */
	protected function _createAdminIndexConditions($bannerAredId, $data) {
		// $conditions = array();
		$name = '';
		$alt = '';
		$url = '';

		if (isset($data['BannerFile']['name'])) {
			$name = $data['BannerFile']['name'];
		}
		if (isset($data['BannerFile']['alt'])) {
			$alt = $data['BannerFile']['alt'];
		}
		if (isset($data['BannerFile']['url'])) {
			$url = $data['BannerFile']['url'];
		}
		if (isset($data['BannerFile']['status']) && $data['BannerFile']['status'] === '') {
			unset($data['BannerFile']['status']);
		}

		unset($data['_Token']);
		unset($data['BannerFile']['name']);
		unset($data['BannerFile']['alt']);
		unset($data['BannerFile']['url']);

		// 条件指定のないフィールドを解除
		foreach ($data['BannerFile'] as $key => $value) {
			if ($value === '') {
				unset($data['BannerFile'][$key]);
			}
		}

		$conditions = array('BannerFile.banner_area_id' => $bannerAredId);

		if ($data['BannerFile']) {
			$conditions = $this->postConditions($data);
		}

		if ($name) {
			$conditions[] = array(
				'BannerFile.name LIKE' => '%'.$name.'%'
			);
		}
		if ($alt) {
			$conditions[]['or'] = array(
				'BannerFile.alt LIKE' => '%'.$alt.'%'
			);
		}
		if ($url) {
			$conditions[]['or'] = array(
				'BannerFile.url LIKE' => '%'.$url.'%'
			);
		}

		if ($conditions) {
			return $conditions;
		} else {
			return array();
		}
	}

}
