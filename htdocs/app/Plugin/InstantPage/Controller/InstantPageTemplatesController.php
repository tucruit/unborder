<?php
/*
 * [Contoller] InstantPageTemplates
 */
//App::uses('ThemesController', 'Controller');
App::uses('BcZip', 'Lib');
class InstantPageTemplatesController extends AppController {
	/**
	 * ControllerName
	 *
	 * @var string
	 */
	public $name = 'InstantPageTemplates';

	/**
	 * Model
	 *
	 * @var array
	 */
	public $uses = array(
		'InstantPage.InstantPageTemplate',
		'InstantPage.InstantPage',
		'InstantPage.InstantPageUser',
		'User',
		'Theme',
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
	public $adminTitle = 'インスタントページテーマ';

	/**
	 * メッセージ用機能名
	 *
	 * @var string
	 */
	public $controlName = 'インスタントページテーマ';

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
		$this->BcAuth->allow('name_check');

		if (empty($this->siteConfigs['editor']) || $this->siteConfigs['editor'] === 'none') {
			return;
		}
		$this->helpers[] = $this->siteConfigs['editor'];
	}

	/**
	 * [ADMIN] テーマ一覧管理
	 *
	 */
	public function admin_index() {
		$this->pageTitle = $this->controlName . '一覧';

		// テーマフォルダ内のテーマを取得
		$themes = BcUtil::getThemeList();
		$themedatas = [];
		$currentTheme = null;
		foreach($themes as $themename) {
			if ($themename !== 'core' && $themename !== '_notes') {
				if ($themename == $this->siteConfigs['theme']) {
					$currentTheme = InstantPageUtil::loadThemeInfo($themename);
				} else {
					$themedatas[$themename] = InstantPageUtil::loadThemeInfo($themename);
				}
			}
		}
		$this->set('themedatas', $themedatas);

		// ユーザーがセットされないので、find
		$users = $this->User->find('all', ['order' => 'User.created ASC',]);
		$userDatas = Hash::combine($users, '{n}.User.id', '{n}.User.real_name_1');
		$this->set('userDatas',$userDatas);
		$this->search = 'instant_page_templates_index';
		$default = [
			'named' => [
				'num' => $this->siteConfigs['admin_list_num'],
				'sortmode' => 0
			]
		];
		$this->setViewConditions([$this->modelClass, 'InstantPage'], ['default' => $default]);

		$conditions = $this->_createAdminIndexConditions($this->request->data);

		// インスタントページテーマユーザーでログイン中は自分の作成ページのみ参照
		$user = BcUtil::loginUser();
		if (InstantPageUtil::isMemberGroup($user['user_group_id'])) {
			$conditions['InstantPageTemplate.user_id'] = $user['id'];
		}
		// 登録されているインスタントページをバインドする
		$this->{$this->modelClass}->bindModel([
					'hasMany' => ['InstantPage']
		]);
		$this->paginate = array(
			'conditions'	=> $conditions,
			'fields'		=> array(),
			'limit'			=> $this->passedArgs['num'],
			'order' => 'InstantPageTemplate.created DESC',
		);

		$datas = $this->paginate();
		if ($datas) {
			$this->set('datas',$datas);
		}

		if ($this->RequestHandler->isAjax() || !empty($this->request->query['ajax'])) {
			$this->render('ajax_index');
			return;
		}
	}

	/**
	 * [ADMIN] 追加
	 *
	 */
	public function admin_add() {
		if ($this->request->data) {

			// テーマのアップロード
			if (!$this->request->is(['post', 'put'])) {
				return;
			}

			if ($this->Theme->isOverPostSize()) {
				$this->BcMessage->setError(
					__d(
						'baser',
						'送信できるデータ量を超えています。合計で %s 以内のデータを送信してください。',
						ini_get('post_max_size')
					)
				);
			}
			if (empty($this->request->data['Theme']['file']['tmp_name'])) {
				$message = __d('baser', 'ファイルのアップロードに失敗しました。');
				if (!empty($this->request->data['Theme']['file']['error']) && $this->request->data['Theme']['file']['error'] == 1) {
					$message .= __d('baser', 'サーバに設定されているサイズ制限を超えています。');
				}
				$this->BcMessage->setError($message);
				return;
			}

			$name = $this->request->data['Theme']['file']['name'];
			// テーマ名のチェックと生成
			$fileInfo = pathinfo($name);
			if ($fileInfo['extension'] !== 'zip') {
				$this->BcMessage->setError('zip圧縮してください。');
				return;
			} else {
				$nameCheck = $this->_nameCheck($fileInfo['filename']);
				if ($nameCheck['status'] === true) {
					$savedata = $this->request->data;
					$savedata['InstantPageTemplate']['name'] = $fileInfo['filename'];
				} else {
					$this->BcMessage->setError($nameCheck['message']);
					return;
				}
			}

			// 圧縮解凍 → themeフォルダに配置
			move_uploaded_file($this->request->data['Theme']['file']['tmp_name'], TMP . $name);
			$BcZip = new BcZip();
			if (!$BcZip->extract(TMP . $name, BASER_THEMES)) {
				$msg = __d('baser', 'アップロードしたZIPファイルの展開に失敗しました。');
				$msg .= "\n" . $BcZip->error;
				$this->BcMessage->setError($msg);
				return;
			}
			unlink(TMP . $name);
			$this->BcMessage->setInfo('テーマファイル「' . $name . '」を追加しました。');


			$this->{$this->modelClass}->create($savedata);
			if ($this->{$this->modelClass}->save()) {
				$message = $this->controlName . '「'.$savedata[$this->modelClass]['name'] . '」を追加しました。';
				$this->setMessage($message, false, true);
				$this->redirect(array('action' => 'index'));
			} else {
				$message = '入力エラーです。内容を修正してください。';
				$this->setMessage($message, true);
			}
		} else {
			$this->request->data = $this->InstantPage->getDefaultValue();
			// $user = BcUtil::loginUser();
			// if (isset($user['InstantPageUser']['id']) && $user['InstantPageUser']['id']) {
			// 	$this->request->data['InstantPage']['instant_page_users_id'] = $user['InstantPageUser']['id'];
			// }
		}
		// ユーザー一覧
		// ユーザー一覧
		$users = $this->User->find('all', [
			'conditions' => ['OR' => [
				['InstantPageUser.creator_flg = ' => 1], //インスタントページユーザーの場合クリエイターのみ
				['User.user_group_id' => [1, 2, 3]], //もしくは、インスタントページユーザー以外
			]
		],
			'order' => 'User.created ASC',
		]);
		$this->set('users', Hash::combine($users, '{n}.User.id', '{n}.User.real_name_1'));
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
		// インスタントページテーマユーザーでログイン中は自分の作成ページのみ編集可能
		// if (!BcUtil::isAdminUser();) {
		// 	if ($this->request->data['InstantPage']['instant_page_users_id'] != $user['InstantPageUser']['id']) {
		// 		$this->setMessage('無効な処理です。', true);
		// 		$this->redirect(array('action' => 'index'));
		// 	}
		// }

		// ユーザー一覧
		$users = $this->User->find('all', [
			'conditions' => ['OR' => [
				['InstantPageUser.creator_flg = ' => 1], //インスタントページユーザーの場合クリエイターのみ
				['User.user_group_id' => [1, 2, 3]], //もしくは、インスタントページユーザー以外
			]
		],
			'order' => 'User.created ASC',
		]);
		$this->set('users', Hash::combine($users, '{n}.User.id', '{n}.User.real_name_1'));
		$this->pageTitle = $this->controlName . '編集';
		$this->render('form');
	}


	/*
	 * name チェック
	 */

	protected function _nameCheck($name = null) {

		$this->layout = false;
		$this->autoRender = false;
		$errParams = [];
		if (!$name && $this->request->data('name')) {
			$name = $this->request->data('name');
		}

		// 英数字 +ハイフン・アンダースコア以外が使われていないかチェック
		if (!InstantPageUtil::alphaNumericPlus($name)) {
			$name = false;
			$errParams = ['status' => false, 'message' => 'テーマ名の形式が無効です。'];
		}

		if ($name) {
			$instantPageTemplate = $this->{$this->modelClass}->find('all', array(
				'conditions' => array(
					'InstantPageTemplate.name' => $name,
				),
				'recursive' => -1
			));

			if ($instantPageTemplate) {
				$errParams = [
					'status' => false,
					'message' => '既に登録されているテーマ名です。別のテーマ名をご入力ください。',
				];
			} else {
				$errParams = [
					'status' => true,
					'message' => '利用可能なテーマ名です。',
				];
			}
		} elseif(empty($errParams)) {
			$errParams = [
				'status' => false,
				'message' => 'テーマ名が入力されていません。テーマ名をご入力ください。',
			];
		}
		return $errParams;
	}


	/**
	 * テーマを削除する　(ajax)
	 *
	 * @param string $theme
	 * @return void
	 */
	public function admin_ajax_delete($id = null) {
		$this->_checkSubmitToken();
		if (!$id) {
			$this->ajaxError(500, __d('baser', '無効な処理です。'));
		}
		$theme = $this->{$this->modelClass}->findById($id);
		if ($this->{$this->modelClass}->delete($id)) {
			// インスタントページに登録されたidが残ってしまうので対応
			$instantPages = $this->InstantPage->find('all', [
				'conditions' => ['InstantPage.instant_page_template_id' => $id],
				'recursive' => -1,
			]);
			if (!empty($instantPages)) {
				foreach ($instantPages as $instantPage) {
					$saveData['InstantPage']['id'] = $instantPage['InstantPage']['id'];
					$saveData['InstantPage']['instant_page_template_id'] = 1; // default_grayに戻す
					$this->InstantPage->save($saveData);
				}
			}
			// テーマフォルダの
			if (!$this->_del($theme['InstantPageTemplate']['name'])) {
				$this->ajaxError(500, __d('baser', 'テーマフォルダを手動で削除してください。'));
				exit;
			}
		} else {
			$this->setMessage('データベース処理中にエラーが発生しました。', true);
		}
		clearViewCache();
		exit(true);
	}

	/**
	 * データを削除する
	 *
	 * @param string $theme テーマ名
	 * @return bool
	 */
	protected function _del($theme)
	{
		$path = WWW_ROOT . 'theme' . DS . $theme;
		$folder = new Folder();
		if (!$folder->delete($path)) {
			return false;
		}
		$siteConfig = ['SiteConfig' => $this->siteConfigs];
		if ($theme == $siteConfig['SiteConfig']['theme']) {
			$siteConfig['SiteConfig']['theme'] = '';
			$this->SiteConfig->saveKeyValue($siteConfig);
		}
		return true;
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
		$theme = $this->{$this->modelClass}->findById($id);
		if ($this->{$this->modelClass}->delete($id)) {
			if (!$this->_del($theme['InstantPageTemplate']['name'])) {
				$this->setMessage('テーマフォルダを手動で削除してください。', true, true);
			} else {

				$message = $this->controlName . ' ' . $theme['InstantPageTemplate']['name'] . ' を削除しました。';
				$this->setMessage($message, false, true);
			}
			clearAllCache();
			$this->redirect(array('action' => 'index'));
		} else {
			$this->setMessage('データベース処理中にエラーが発生しました。', true);
		}
		$this->redirect(array('action' => 'index'));
	}


	/**
	 * 一覧用の検索条件を生成する
	 *
	 * @param array $data
	 * @return array $conditions
	 */
	protected function _createAdminIndexConditions($data) {
		$conditions = array();
		$user_id = '';
		if (isset($data['User']['id'])) {
			$user_id = $data['User']['id'];
			unset($data['User']['id']);
		}
		unset($data['_Token']);

		if (isset($data['User']) && $data['User'])  {
			$conditions = $this->postConditions($data);
		}
		if ($user_id) {
			$conditions['InstantPageTemplate.user_id'] = $user_id;
		}

		if(isset($data['User']['_id']) && $data['User']['_id'] == 'NULL') {
			$conditions['User._id'] = NULL;
		}

		if ($conditions) {
			return $conditions;
		} else {
			return array();
		}
	}

}
