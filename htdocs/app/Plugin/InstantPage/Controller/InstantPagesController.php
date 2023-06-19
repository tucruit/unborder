<?php
/*
 * [Contoller] InstantPages
 */
class InstantPagesController extends AppController {
	/**
	 * ControllerName
	 *
	 * @var string
	 */
	public $name = 'InstantPages';

	/**
	 * Model
	 *
	 * @var array
	 */
	public $uses = array(
		'InstantPage.InstantPage',
		'InstantPage.InstantPageTemplate',
		'InstantPage.InstantPageUser',
		'User',
	);
	/**
	 * ヘルパー
	 *
	 * @var array
	 */
	public $helpers = [
		'Html', 'Session', 'BcGooglemaps',
		'BcXml', 'BcText',
		'BcFreeze', 'BcPage'
	];
	/**
	 * コンポーネント
	 *
	 * @var array
	 * @deprecated useViewCache 5.0.0 since 4.0.0
	 *    CakePHP3では、ビューキャッシュは廃止となるため、別の方法に移行する
	 */
	public $components = [
		'BcAuth',
		'Cookie',
		'BcAuthConfigure',
		'BcEmail',
		'BcContents' => ['useForm' => true, 'useViewCache' => false]
	];

	/**
	 * 管理画面タイトル
	 *
	 * @var string
	 */
	public $adminTitle = 'インスタントページ';

	/**
	 * メッセージ用機能名
	 *
	 * @var string
	 */
	public $controlName = 'インスタントページ';

	/**
	 * beforeFilter
	 *
	 * @return void
	 */
	public function beforeFilter()
	{
		parent::beforeFilter();

		// 認証設定
		$this->BcAuth->allow('display');
		$this->BcAuth->allow('detail');
		$this->BcAuth->allow('ajax_name_check');

		if (empty($this->siteConfigs['editor']) || $this->siteConfigs['editor'] === 'none') {
			return;
		}
		$this->helpers[] = $this->siteConfigs['editor'];
	}

	/**
	 * インスタントページ一覧を表示する
	 *
	 * @param int id
	 */
	public function index($id = null) {
		$this->BcMessage->setError(__d('baser', '準備中です'));
	}

	/**
	 * [ADMIN] インスタントページ一覧管理
	 *
	 */
	public function admin_index() {
		$this->pageTitle = $this->controlName . '一覧';
		//$this->BcMessage->setError(__d('baser', 'まだ実装されていません'));
		$users = $this->InstantPageUser->find('all');
		$userDatas = Hash::combine($users, '{n}.InstantPageUser.id', '{n}.User');
		$this->set('users',$users);
		$this->set('userDatas',$userDatas);
		$this->search = 'instant_pages_index';
		$default = [
			'named' => [
				'num' => $this->siteConfigs['admin_list_num'],
				'sortmode' => 0
			]
		];
		$this->setViewConditions([$this->modelClass, 'InstantPage'], ['default' => $default]);

		$conditions = $this->_createAdminIndexConditions($this->request->data);

		// インスタントページユーザーでログイン中は自分の作成ページのみ参照
		$user = BcUtil::loginUser();
		if (InstantPageUtil::isMemberGroup($user['user_group_id'])) {
			$user = $this->InstantPageUser->find('first', ['conditions' => ['InstantPageUser.user_id' => $user['id']]]);
		}
		if (isset($user['InstantPageUser']['id']) && $user['InstantPageUser']['id']) {
			$conditions['InstantPage.instant_page_users_id'] = $user['InstantPageUser']['id'];
		}

		$this->paginate = array(
			'conditions'	=> $conditions,
			'fields'		=> array(),
			'limit'			=> $this->passedArgs['num'],
			'order'			=> ['InstantPage.created' => 'DESC'],
		);

		$datas = $this->paginate();
		if ($datas) {
			$this->set('datas',$datas);
		}

		$this->pageTitle = $this->controlName . '一覧';
		if ($this->RequestHandler->isAjax() || !empty($this->request->query['ajax'])) {
			$this->render('ajax_index');
			return;
		}
	}

	/**
	 * [PUBLISH]
	 *
	 * @param int $id
	 */
	public function detail($instantPageUsersName = '', $name = null) {
		if (!$name) {
			$this->notFound();
		}

		$conditions = $this->InstantPage->getConditionAllowPublish();
		$conditions['InstantPage.name'] = $name;

		$data = $this->InstantPage->find('first', [
			'conditions' => $conditions,
			'recursive' => 2 //作成者ユーザー情報まで取得
		]);
		if (!$data) {
			$this->notFound();
		}

		$this->set('data', $data);
	}

	// /**
	//  * [ADMIN] 追加
	//  *
	//  */
	// public function add() {
	// 	$user = BcUtil::loginUser();
	// 	if ($this->request->data && isset($user['InstantPageUser']['id']) && $user['InstantPageUser']['id']) {
	// 		$this->admin_add();
	// 	}
	// }


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
		} else {
			$this->request->data = $this->InstantPage->getDefaultValue();
			$user = BcUtil::loginUser();
			if (isset($user['InstantPageUser']['id']) && $user['InstantPageUser']['id']) {
				$this->request->data['InstantPage']['instant_page_users_id'] = $user['InstantPageUser']['id'];
			}
		}
		// テーマテンプレート一覧
		$InstantpageTemplateList = $this->InstantPageTemplate->find('list',['fields' => ['id', 'name']]);
		$this->set('InstantpageTemplateList', $InstantpageTemplateList );
		// テーマのconfig情報の読み込み
		$themedatas = [];
		if (!empty($InstantpageTemplateList)) {
			foreach($InstantpageTemplateList as $themename) {
				if ($themename !== 'core' && $themename !== '_notes') {
					if ($themename == $this->siteConfigs['theme']) {
						continue;
					} else {
						$themedatas[$themename] = InstantPageUtil::loadThemeInfo($themename);
					}
				}
			}
		}
		$this->set('themedatas', $themedatas );
		// ユーザー一覧
		$this->set('users', $this->InstantPageUser->getUserList());
		$this->pageTitle = $this->controlName . '新規登録';
		$this->render('form');
	}
	/**
	 * [ADMIN] 編集
	 *
	 * @param int $id
	 */
	public function admin_edit($id = null) {
		$user = BcUtil::loginUser();
		if (!$id || empty($user)) {
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
				//$this->redirect(array('action' => 'index'));
			} else {
				$this->setMessage('入力エラーです。内容を修正して下さい。', true);
			}
		}
		// インスタントページユーザーでログイン中は自分の作成ページのみ編集可能
		if (isset($user['InstantPageUser']['id']) && $user['InstantPageUser']['id']) {
			if ($this->request->data['InstantPage']['instant_page_users_id'] != $user['InstantPageUser']['id']) {
				$this->setMessage('無効な処理です。', true);
				$this->redirect(array('action' => 'index'));
			}
		}


		// テーマテンプレート一覧
		$InstantpageTemplateList = $this->InstantPageTemplate->find('list',['fields' => ['id', 'name']]);
		$this->set('InstantpageTemplateList', $InstantpageTemplateList );
		// テーマのconfig情報の読み込み
		$themedatas = [];
		if (!empty($InstantpageTemplateList)) {
			foreach($InstantpageTemplateList as $themename) {
				if ($themename !== 'core' && $themename !== '_notes') {
					if ($themename == $this->siteConfigs['theme']) {
						continue;
					} else {
						$themedatas[$themename] = InstantPageUtil::loadThemeInfo($themename);
					}
				}
			}
		}
		$this->set('themedatas', $themedatas );
		// 管理画面にテーマのセット
		$template = isset($this->request->data['InstantPage']['instant_page_template_id']) ? $this->request->data['InstantPage']['instant_page_template_id'] : 1;
		if (array_key_exists($template, $InstantpageTemplateList)) {
			Configure::write('BcSite.theme', $InstantpageTemplateList[$template]);
		}


		// ユーザー一覧
		$this->set('users', $this->InstantPageUser->getUserList());
		$this->pageTitle = $this->controlName . '編集';
		$this->render('form');
	}


	/*
	 * name チェック
	 */

	public function ajax_name_check($name = null) {

		$this->layout = false;
		$this->autoRender = false;
		$errParams = array();
		if (!$name && $this->request->data('name')) {
			$name = $this->request->data('name');
		}

		// 英数字 +ハイフン・アンダースコア以外が使われていないかチェック
		if (!InstantPageUtil::alphaNumericPlus($name)) {
			$name = false;
			$errParams = ['status' => false, 'message' => '形式が無効です。'];
		}

		if ($name) {
			$user =
			$names = $this->{$this->modelClass}->find('all', array(
				'conditions' => array(
					'InstantPage.name' => $name,
					'InstantPage.instant_page_users_id >=' => date('Y-m-d H:i:s'),
				),
				'recursive' => -1
			));

			if ($users || $InstantPage) {
			//if ($users) {
				$errParams = [
					'status' => false,
					'message' => '既に登録されているページ名名です。別のページ名名をご入力ください。',
				];
			} else {
				$errParams = [
					'status' => true,
					'message' => '利用可能なページ名名です。',
				];
			}
		} elseif(empty($errParams)) {
			$errParams = [
				'status' => false,
				'message' => 'ページ名名が入力されていません。ページ名名をご入力ください。',
			];
		}
		$errParams['field'] = '.nameCheck';
		return json_encode($errParams);
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
	 * [ADMIN] 削除処理 (ajax)
	 *
	 * @param int $id
	 */
	public function admin_ajax_unpublish($id) {
		$this->_checkSubmitToken();
		if (!$id) {
			$this->ajaxError(500, __d('baser', '無効な処理です。'));
		}
		if ($this->_changeStatus($id, false)) {
			clearViewCache();
			exit(true);
		} else {
			$this->ajaxError(500, $this->InstantPage->validationErrors);
		}
		exit();

	}

	/**
	 * [ADMIN] 削除処理 (ajax)
	 *
	 * @param int $id
	 */
	public function admin_ajax_publish($id) {
		$this->_checkSubmitToken();
		if (!$id) {
			$this->ajaxError(500, __d('baser', '無効な処理です。'));
		}
		if ($this->_changeStatus($id, true)) {
			clearViewCache();
			exit(true);
		} else {
			$this->ajaxError(500, $this->InstantPage->validationErrors);
		}
		exit();
	}

	/**
	 * 一括公開
	 *
	 * @param array $ids
	 * @return boolean
	 * @access protected
	 */
	protected function _batch_publish($ids)
	{
		if ($ids) {
			foreach($ids as $id) {
				$this->_changeStatus($id, true);
			}
		}
		clearViewCache();
		return true;
	}

	/**
	 * 一括非公開
	 *
	 * @param array $ids
	 * @return boolean
	 * @access protected
	 */
	protected function _batch_unpublish($ids)
	{
		if ($ids) {
			foreach($ids as $id) {
				$this->_changeStatus($id, false);
			}
		}
		clearViewCache();
		return true;
	}

	/**
	 * ステータスを変更する
	 *
	 * @param int $id
	 * @param boolean $status
	 * @return boolean
	 */
	protected function _changeStatus($id, $status)
	{
		$statusTexts = [0 => __d('baser', '非公開状態'), 1 => __d('baser', '公開状態')];
		$data = $this->InstantPage->find('first', ['conditions' => ['InstantPage.id' => $id], 'recursive' => -1]);
		$data['InstantPage']['status'] = $status;
		$data['InstantPage']['publish_begin'] = '';
		$data['InstantPage']['publish_end'] = '';
		unset($data['InstantPage']['eye_catch']);
		$this->InstantPage->set($data);

		if ($this->InstantPage->save()) {
			$statusText = $statusTexts[$status];
			$this->InstantPage->saveDbLog(sprintf(__d('baser', 'インスタントページ「%s」 を %s に設定しました。'), $data['InstantPage']['name'], $statusText));
			return true;
		} else {
			return false;
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
		$instant_page_users_id = '';
		// $company = '';
		// $real_name_1 = '';
		$prefectureId = '';
		// $email = '';
		// ユーザーname
		if (isset($data['InstantPageUser']['id'])) {
			$instant_page_users_id = $data['InstantPageUser']['id'];
			unset($data['InstantPageUser']['id']);
		}
		// // ユーザー姓
		// if (isset($data['User']['real_name_1'])) {
		// 	$real_name_1 = $data['User']['real_name_1'];
		// }
		// // ユーザーemail
		// if (isset($data['User']['email'])) {
		// 	$email = $data['User']['email'];
		// }
		// // インスタントページユーザー会社名
		// if (isset($data['InstantPageUser']['company'])) {
		// 	$company = $data['InstantPageUser']['company'];
		// }
		// インスタントページユーザー県名
		if (isset($data['InstantPage']['prefecture_id'])) {
			$prefectureId = $data['InstantPage']['prefecture_id'];
			unset($data['InstantPage']['prefecture_id']);
		}
		//$dataに残すと完全一致となるため、unset
		unset($data['_Token']);

		// unset($data['User']['name']);
		// unset($data['User']['real_name_1']);
		// unset($data['User']['email']);
		// unset($data['InstantPageUser']['company']);

		// 条件指定のないフィールドを解除
		// if (isset($data['InstantPageUser'])) {
		// 	foreach ($data['InstantPageUser'] as $key => $value) {
		// 		if ($value === '') {
		// 			unset($data['InstantPageUser'][$key]);
		// 		}
		// 	}
		// }

		if (isset($data['InstantPageUser']) && $data['InstantPageUser'])  {
			$conditions = $this->postConditions($data);
		}
		if ($instant_page_users_id) {
			$conditions['InstantPage.instant_page_users_id'] = $instant_page_users_id;
		}
		// if ($real_name_1) {
		// 	$conditions['User.real_name_1 LIKE'] = '%'.$real_name_1.'%';
		// }
		// if ($email) {
		// 	$conditions['User.email LIKE'] = '%'.$email.'%';
		// }
		// if ($company) {
		// 	$conditions['InstantPageUser.company LIKE'] = '%'.$company.'%';
		//}
		// // JOIN 県名をセット
		// if ($prefectureId) {
		// 	$conditions['InstantPageUser.prefecture_id ='] = $prefectureId;
		// }
		if(isset($data['InstantPageUser']['_id']) && $data['InstantPageUser']['_id'] == 'NULL') {
			$conditions['InstantPageUser._id'] = NULL;
		}

		// p($conditions);
		// exit;
		if ($conditions) {
			return $conditions;
		} else {
			return array();
		}
	}

}
