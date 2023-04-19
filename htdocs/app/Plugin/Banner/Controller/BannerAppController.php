<?php
/**
 * [BANNER] 基底コントローラ
 *
 * @copyright		Copyright 2014 - 2018, D-ZERO Co.,LTD.
 * @link			http://www.d-zero.co.jp/
 * @package			Banner
 * @license			MIT
 */
class BannerAppController extends AppController {
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
	 * Help表示
	 * 
	 * @var string 
	 */
	public $help = 'banners';

	/**
	 * メッセージ用機能名
	 * 
	 * @var string
	 */
	public $controlName = 'バナー';

	/**
	 * beforeFilter
	 *
	 */
	public function beforeFilter() {
		parent::beforeFilter();
	}

	/**
	 * [ADMIN] 一覧表示
	 * 
	 */
	public function admin_index() {
		$default = array(
			'named' => array(
				'num' => $this->siteConfigs['admin_list_num'],
				'sortmode' => 0));
		$this->setViewConditions($this->modelClass, array('default' => $default));

		$conditions = $this->_createAdminIndexConditions($this->request->data);
		$this->paginate = array(
			'conditions'	=> $conditions,
			'fields'		=> array(),
			'limit'			=> $this->passedArgs['num']
		);
		$datas = $this->paginate();
		if ($datas) {
			$this->set('datas',$datas);
		}

		$this->pageTitle = $this->controlName . '一覧';
	}

	/**
	 * [ADMIN] 追加
	 * 
	 */
	public function admin_add() {
		if ($this->request->data) {
			$this->{$this->modelClass}->create($this->request->data);
			if ($this->{$this->modelClass}->save()) {
				$message = $this->controlName . '「'.$this->request->data[$this->modelClass]['name'] . '」を追加しました。';
				$this->setMessage($message, false, true);
				$this->redirect(array('action' => 'index'));
			} else {
				$message = '入力エラーです。内容を修正してください。';
				$this->setMessage($message, true);
			}
		}

		$this->pageTitle = $this->controlName . '新規登録';
		$this->render('form');
	}

	/**
	 * [ADMIN] 編集
	 * 
	 * @param int $id
	 */
	public function admin_edit($id = null) {
		if (!$id) {
			$this->setMessage('無効な処理です。', true);
			$this->redirect(array('action' => 'index'));			
		}
		if (empty($this->request->data)) {
			$this->{$this->modelClass}->id = $id;
			$this->request->data = $this->{$this->modelClass}->read();
		} else {
			$this->{$this->modelClass}->set($this->request->data);
			if ($this->{$this->modelClass}->save($this->request->data)) {
				$message = $this->controlName . ' NO.' . $id . ' を更新しました。';
				$this->setMessage($message, false, true);
				clearAllCache();
				$this->redirect(array('action' => 'index'));
			} else {
				$this->setMessage('入力エラーです。内容を修正して下さい。', true);
			}
		}

		$this->pageTitle = $this->controlName . '編集';
		$this->render('form');
	}

	/**
	 * [ADMIN] 削除
	 *
	 * @param int $id
	 */
	public function admin_delete($id = null) {
		if (!$id) {
			$this->setMessage('無効な処理です。', true);
			$this->redirect(array('action' => 'index'));
		}
		if ($this->{$this->modelClass}->delete($id)) {
			$message = $this->controlName . ' NO.' . $id . ' を削除しました。';
			$this->setMessage($message, false, true);
			clearAllCache();
			$this->redirect(array('action' => 'index'));
		} else {
			$this->setMessage('データベース処理中にエラーが発生しました。', true);
		}
		$this->redirect(array('action' => 'index'));
	}

	/**
	 * [ADMIN] 削除処理 (ajax)
	 *
	 * @param int $id
	 */
	public function admin_ajax_delete($id = null) {
		if (!$id) {
			$this->ajaxError(500, '無効な処理です。');
		}

		// メッセージ用にデータを取得
		$data = $this->{$this->modelClass}->read(null, $id);
		if ($this->{$this->modelClass}->delete($id)) {
			$message = $this->controlName . '「' . $data[$this->modelClass]['name'] . '」を削除しました。';
			$this->{$this->modelClass}->saveDbLog($message);
			exit(true);
		}
		exit();
	}

	/**
	 * [ADMIN] コピー (ajax)
	 * 
	 * @param int $id
	 */
	public function admin_ajax_copy($id) {
		$result = $this->{$this->modelClass}->copy($id);
		clearDataCache();
		if ($result) {
			$this->setViewConditions($this->modelClass, array('action' => 'admin_index'));
			$this->set('data', $result);
		} else {
			$this->ajaxError(500, $this->{$this->modelClass}->validationErrors);
		}
	}

	/**
	 * ステータスを変更する
	 * 
	 * @param int $id
	 * @param boolean $status
	 * @return boolean 
	 */
	protected function _changeStatus($id, $status) {
		$statusTexts = array(0 => '非公開状態', 1 => '公開状態');
		$data = $this->{$this->modelClass}->find('first', array(
			'conditions' => array($this->modelClass . '.id' => $id), 'recursive' => -1));
		$data[$this->modelClass]['status'] = $status;
		$this->{$this->modelClass}->set($data);

		if ($this->{$this->modelClass}->save()) {
			$statusText = $statusTexts[$status];
			$message = $statusText . 'にしました。';
			$this->{$this->modelClass}->saveDbLog($message);
			return true;
		} else {
			return false;
		}
	}

}
