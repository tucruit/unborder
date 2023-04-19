<?php

/**
 * [Controller] 基底コントローラ
 *
 * @copyright		Copyright, Catchup, Inc.
 * @link			https://catchup.co.jp
 * @package			OptionalLink
 */
class OptionalLinkAppController extends AppController {

	/**
	 * ヘルパー
	 *
	 * @var array
	 */
	public $helpers = array('Blog.Blog');

	/**
	 * コンポーネント
	 * 
	 * @var     array
	 */
	public $components = array('BcAuth', 'Cookie', 'BcAuthConfigure');

	/**
	 * サブメニューエレメント
	 *
	 * @var array
	 */
	public $subMenuElements = array('optional_link');

	/**
	 * ぱんくずナビ
	 *
	 * @var string
	 */
	public $crumbs = array(
		array('name' => 'プラグイン管理', 'url' => array('plugin' => '', 'controller' => 'plugins', 'action' => 'index'))
	);

	/**
	 * 管理画面タイトル
	 *
	 * @var string
	 */
	public $adminTitle = '';

	/**
	 * ブログコンテンツデータ
	 * 
	 * @var array
	 */
	public $blogContentDatas = array();

	/**
	 * beforeFilter
	 *
	 */
	public function beforeFilter() {
		parent::beforeFilter();

		// ブログ情報を取得
		$ContentModel				 = ClassRegistry::init('Content');
		$ContentModel->virtualFields = array('blog_name' => 'concat("[", ifnull(Site.title, "' . $this->siteConfigs['main_site_display_name'] . '"), "] ", Content.title)');
		$this->blogContentDatas		 = $ContentModel->find('list', array(
			'fields'	 => array(
				'entity_id',
				'blog_name',
			),
			'conditions' => array(
				'plugin' => 'Blog',
				'type'	 => 'BlogContent',
				'alias_id' => null,
			),
			'joins'		 => array(array(
					'type'		 => 'LEFT',
					'alias'		 => 'Site',
					'table'		 => 'sites',
					'conditions' => array('Content.site_id=Site.id')
				)),
			'recursive'	 => -1,
		));
	}

	/**
	 * [ADMIN] 一覧表示
	 * 
	 */
	public function admin_index() {
		$default = array('named' => array(
				'num'		 => $this->siteConfigs['admin_list_num'],
				'sortmode'	 => 0)
		);
		$this->setViewConditions($this->modelClass, array('default' => $default));

		$conditions		 = $this->_createAdminIndexConditions($this->request->data);
		$this->paginate	 = array(
			'conditions' => $conditions,
			'fields'	 => array(),
			//'order'	=> '{$this->modelClass}.id DESC',
			'limit'		 => $this->passedArgs['num']
		);
		$this->set('datas', $this->paginate($this->modelClass));
		$this->set('blogContentDatas', array('0' => '指定しない') + $this->blogContentDatas);

		if ($this->RequestHandler->isAjax() || !empty($this->request->query['ajax'])) {
			Configure::write('debug', 0);
			$this->render('ajax_index');
			return;
		}
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
			$this->{$this->modelClass}->id	 = $id;
			$this->request->data			 = $this->{$this->modelClass}->read();
		} else {
			$this->{$this->modelClass}->set($this->request->data);
			if ($this->{$this->modelClass}->save($this->request->data)) {
				$this->setMessage('更新が完了しました。');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->setMessage('入力エラーです。内容を修正して下さい。', true);
			}
		}
		$this->set('blogContentDatas', array('0' => '指定しない') + $this->blogContentDatas);
		$this->render('form');
	}

	/**
	 * [ADMIN] 追加
	 * 
	 */
	public function admin_add() {
		$this->pageTitle = $this->adminTitle . '追加';

		if ($this->request->is('post')) {
			if ($this->{$this->modelClass}->save($this->request->data)) {
				$this->setMessage('追加が完了しました。');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->setMessage('入力エラーです。内容を修正して下さい。', true);
			}
		} else {
			$this->request->data = $this->{$this->modelClass}->getDefaultValue();
		}

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
			$message = 'NO.' . $id . 'のデータを削除しました。';
			$this->setMessage($message);
			$this->redirect(array('action' => 'index'));
		} else {
			$this->setMessage('データベース処理中にエラーが発生しました。', true);
		}
		$this->redirect(array('action' => 'index'));
	}

	/**
	 * [ADMIN] 削除処理　(ajax)
	 *
	 * @param int $id
	 */
	public function admin_ajax_delete($id = null) {
		if (!$id) {
			$this->ajaxError(500, '無効な処理です。');
		}
		// 削除実行
		if ($this->_delete($id)) {
			clearViewCache();
			exit(true);
		}
		exit();
	}

	/**
	 * データを削除する
	 * 
	 * @param int $id
	 * @return boolean 
	 */
	protected function _delete($id) {
		// メッセージ用にデータを取得
		$data = $this->{$this->modelClass}->read(null, $id);
		// 削除実行
		if ($this->{$this->modelClass}->delete($id)) {
			$this->{$this->modelClass}->saveDbLog($data[$this->modelClass]['id'] . ' を削除しました。');
			return true;
		} else {
			return false;
		}
	}

	/**
	 * [ADMIN] 無効状態にする
	 * 
	 * @param int $id
	 */
	public function admin_unpublish($id) {
		if (!$id) {
			$this->setMessage('この処理は無効です。', true);
			$this->redirect(array('action' => 'index'));
		}
		if ($this->_changeStatus($id, false)) {
			$this->setMessage('「無効」状態に変更しました。');
			$this->redirect(array('action' => 'index'));
		}
		$this->setMessage('処理に失敗しました。', true);
		$this->redirect(array('action' => 'index'));
	}

	/**
	 * [ADMIN] 有効状態にする
	 * 
	 * @param int $id
	 */
	public function admin_publish($id) {
		if (!$id) {
			$this->setMessage('この処理は無効です。', true);
			$this->redirect(array('action' => 'index'));
		}
		if ($this->_changeStatus($id, true)) {
			$this->setMessage('「有効」状態に変更しました。');
			$this->redirect(array('action' => 'index'));
		}
		$this->setMessage('処理に失敗しました。', true);
		$this->redirect(array('action' => 'index'));
	}

	/**
	 * [ADMIN] 無効状態にする（AJAX）
	 * 
	 * @param int $id
	 */
	public function admin_ajax_unpublish($id) {
		if (!$id) {
			$this->ajaxError(500, '無効な処理です。');
		}
		if ($this->_changeStatus($id, false)) {
			clearViewCache();
			exit(true);
		} else {
			$this->ajaxError(500, $this->{$this->modelClass}->validationErrors);
		}
		exit();
	}

	/**
	 * [ADMIN] 有効状態にする（AJAX）
	 * 
	 * @param int $id
	 */
	public function admin_ajax_publish($id) {
		if (!$id) {
			$this->ajaxError(500, '無効な処理です。');
		}
		if ($this->_changeStatus($id, true)) {
			clearViewCache();
			exit(true);
		} else {
			$this->ajaxError(500, $this->{$this->modelClass}->validationErrors);
		}
		exit();
	}

	/**
	 * ステータスを変更する
	 * 
	 * @param int $id
	 * @param boolean $status
	 * @return boolean 
	 */
	protected function _changeStatus($id, $status) {
		$data = $this->{$this->modelClass}->find('first', array(
			'conditions' => array('id' => $id),
			'recursive'	 => -1
		));
		$data[$this->modelClass]['status']	 = $status;
		if ($status) {
			$data[$this->modelClass]['status'] = 1;
		} else {
			$data[$this->modelClass]['status'] = 0;
		}
		$this->{$this->modelClass}->set($data);
		if ($this->{$this->modelClass}->save()) {
			return true;
		} else {
			return false;
		}
	}
}
