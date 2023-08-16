<?php
App::uses('UsersController', 'Controller');
App::uses('User', 'Model');
class InstantPageUsersController extends AppController {
	public $uses = ['InstantPage.InstantPageUser'];
	public function mypage_index() {
		$this->viewPath = 'InstantPageUsers';
	}
	/**
	 * コントローラー名
	 *
	 * @var string
	 */
	public $name = 'InstantPageUsers';

	/* コンポーネント */
	public $components = [
		'RequestHandler',
		'BcReplacePrefix',
		'BcAuth',
		'Cookie',
		'BcAuthConfigure',
		'BcEmail'
	];

	/**
	 * 管理画面タイトル
	 *
	 * @var string
	 */
	public $adminTitle = 'インスタントページユーザー設定';

	/**
	 * メッセージ用機能名
	 *
	 * @var string
	 */
	public $controlName = 'インスタントページユーザー';


/**
 * ヘルパー
 *
 * @var array
 * @access public
 */
	public $helpers = array(
		'InstantPage.InstantPage',
		'BcText'
	);


	/**
	 * [ADMIN] beforeFilter
	 *
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->BcAuth->allow('verify');
		$this->BcAuth->allow('edit_password');
		$this->BcAuth->allow('reset_password');
		$this->BcAuth->allow('send_activate_url');
		$this->BcAuth->allow('sent_activate_url');
		$this->BcAuth->allow('login');
		$this->BcAuth->allow('admin_login');
		$this->BcAuth->allow('activate');
		$this->BcAuth->allow('ajax_id_check');
		$this->BcAuth->allow('ajax__check');
		$this->Security->validatePost = false;
		$this->Security->csrfCheck = false;
	}


	/**
	 * [ADMIN] インスタントページユーザー一覧管理
	 *
	 */
	public function admin_index() {
		$this->search = 'instant_page_users_index';
		$default = array(
			'named' => array(
				'num' => $this->siteConfigs['admin_list_num'],
				'sortmode' => 0));
		$this->setViewConditions([$this->modelClass, 'InstantPageUser'], array('default' => $default));

		$conditions = $this->_createAdminIndexConditions($this->request->data);
		$this->paginate = array(
			'conditions'	=> $conditions,
			'fields'		=> array(),
			'limit'			=> $this->passedArgs['num'],
			'order'			=> ['InstantPageUser.created' => 'DESC'],
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
 * [ADMIN] ユーザー情報登録
 *
 * @return void
 */
	public function admin_add() {
		if (empty($this->request->data)) {
			$this->request->data = $this->{$this->modelClass}->getDefaultValue();
		} else {

			// 入力補助用データのunset
			if (isset($this->request->data['InstantPage'])) {
				unset($this->request->data['InstantPage']);
			}

			/* 登録処理 */
			$this->request->data['User']['password'] = $this->request->data['User']['password_1'];
			$this->{$this->modelClass}->create($this->request->data);

			if ($this->InstantPageUser->save()) {

				$this->request->data['InstantPageUser']['id'] = $this->{$this->modelClass}->id;
				$this->getEventManager()->dispatch(new CakeEvent('Controller.InstantPageUser.afterAdd', $this, [
					'InstantPageUser' => $this->request->data
				]));

				$this->BcMessage->setSuccess('インスタントページユーザー「' . $this->request->data['User']['name'] . '」を追加しました。');
				$this->redirect(['action' => 'edit', $this->{$this->modelClass}->getInsertID()]);
			} else {
				$this->BcMessage->setError(__d('baser', '入力エラーです。内容を修正してください。'));
			}
		}

		/* 表示設定 */
		$userGroups = $this->User->getControlSource('user_group_id');
		$user = $this->BcAuth->user();
		if ($user['user_group_id'] != Configure::read('BcApp.adminGroupId')) {
			unset($userGroups[1]);
		}

		$this->set('userGroups', $userGroups);
		$this->set('editable', true);
		$this->set('selfUpdate', false);
		$this->subMenuElements = ['site_configs', 'instant_page_users'];
		$this->pageTitle = __d('baser', 'インスタントページユーザー登録');
		$this->help = 'instant_page_users_form';
		$this->render('form');
	}

/**
 * [ADMIN] ユーザー情報編集
 *
 * @param int user_id
 * @return void
 */
	public function edit($id) {
		$this->admin_edit($id);
	}


/**
 * [ADMIN] ユーザー情報編集
 *
 * @param int user_id
 * @return void
 */
	public function admin_edit($id) {
		/* 除外処理 */
		if (!$id && empty($this->request->data)) {
			$this->setMessage('無効な処理です。', true);
			$this->redirect(array('action' => 'index'));
		}

		$selfUpdate = false;
		$updatable = true;
		$user = $this->BcAuth->user();
		if (empty($this->request->data)) {
			$this->request->data = $this->{$this->modelClass}->getDefaultValue();
			$this->request->data = $this->{$this->modelClass}->find('first', array(
				'conditions' => array($this->modelClass . '.id' => $id),
			));
		} else {

			// パスワードがない場合は更新しない
			if ($this->request->data['User']['password_1'] || $this->request->data['User']['password_2']) {
				$this->request->data['User']['password'] = $this->request->data['User']['password_1'];
			}

			// 権限確認
			if (!$updatable) {
				$this->BcMessage->setError(__d('baser', '指定されたページへのアクセスは許可されていません。'));
			// 自身のアカウントは変更出来ないようにチェック
			} elseif ($selfUpdate && $user['user_group_id'] != $this->request->data['User']['user_group_id']) {
				$this->BcMessage->setError(__d('baser', '自分のアカウントのグループは変更できません。'));
			} else {
				$this->{$this->modelClass}->set($this->request->data);
				if ($this->{$this->modelClass}->save()) {
					$this->getEventManager()->dispatch(new CakeEvent('Controller.Users.afterEdit', $this, [
						'user' => $this->request->data
					]));
					if ($selfUpdate) {
						$this->admin_logout();
					}
					$this->BcMessage->setSuccess('ユーザー「' . $this->request->data['User']['real_name_1'] . '」を更新しました。');
					$this->redirect(['action' => 'edit', $id]);
				} else {
					$this->BcMessage->setError(__d('baser', '保存できませんでした。'));

					// // よく使う項目のデータを再セット
					// $user = $this->{$this->modelClass}->find('first', ['conditions' => ['User.id' => $id]]);
					// unset($user['User']);
					// $this->request->data = array_merge($user, $this->request->data);
					// $this->BcMessage->setError(__d('baser', '入力エラーです。内容を修正してください。'));
				}
			}
		}

		/* 表示設定 */
		$userGroups = $this->User->getControlSource('user_group_id');
		$editable = true;
		$deletable = true;

		if (@$user['user_group_id'] != Configure::read('BcApp.adminGroupId') && Configure::read('debug') !== -1) {
			$editable = false;
		} elseif ($selfUpdate && @$user['user_group_id'] == Configure::read('BcApp.adminGroupId')) {
			$deletable = false;
		}

		$this->set(compact('userGroups', 'editable', 'selfUpdate', 'deletable'));
		$this->subMenuElements = ['site_configs', 'users'];
		$this->pageTitle = __d('baser', 'インスタントページユーザー情報編集');
		$this->help = 'instant_page_users_form';
		$this->render('form');
	}

/**
 * [ADMIN] インスタントページユーザー情報削除 (ajax)
 *
 * @param int id
 * @return void
 */
	public function admin_ajax_delete($id = null) {
		$this->_checkSubmitToken();
		/* 除外処理 */
		if (!$id) {
			$this->ajaxError(500, __d('baser', '無効な処理です。'));
		}
		// メッセージ用にデータを取得
		$user = $this->{$this->modelClass}->read(null, $id);

		/* 削除処理 */
		if ($this->{$this->modelClass}->delete($id)) {
			$this->{$this->modelClass}->saveDbLog('担当者「' . $user['InstantPageUser']['real_name_1'] . '」を削除しました。');
			exit(true);
		}
		exit();
	}

/**
 * 一括削除
 *
 * @param array $ids
 * @return boolean
 */
	protected function _batch_del($ids) {
		if ($ids) {
			foreach ($ids as $id) {
				$this->_del($id);
			}
		}
		return true;
	}

/**
 * データを削除する
 *
 * @param int $id
 * @return boolean
 */
	protected function _del($id) {
		// メッセージ用にデータを取得
		$user = $this->{$this->modelClass}->read(null, $id);

		/* 削除処理 */
		if ($this->{$this->modelClass}->delete($id)) {
			$this->{$this->modelClass}->saveDbLog('担当者「' . $user['InstantPageUser']['real_name_1'] . '」を削除しました。');
			return true;
		} else {
			return false;
		}
	}



/**
 * [ADMIN] インスタントページユーザー情報削除
 *
 * @param int id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->_checkSubmitToken();
		/* 除外処理 */
		if (!$id) {
			$this->BcMessage->setError(__d('baser', '無効なIDです。'));
			$this->redirect(['action' => 'index']);
		}

		// メッセージ用にデータを取得
		$user = $this->{$this->modelClass}->read(null, $id);

		/* 削除処理 */
		if ($this->{$this->modelClass}->delete($id)) {
			$this->BcMessage->setSuccess('担当者「: ' . $user['User']['real_name_1'] . ' を削除しました。');
		} else {
			$this->BcMessage->setError(__d('baser', 'データベース処理中にエラーが発生しました。'));
		}

		$this->redirect(['action' => 'index']);
	}


/**
 * パスワードリセットのためアクティベーション処理を行なう
 * アクティベーション画面のURLを指定したメールアドレス宛に送信する
 *
 * @return void
 */
 	public function send_activate_url() {
		$this->redirect('/cmsadmin/users/send_activate_url');
	}


	public function reset_password() {
		$this->admin_reset_password();
	}

	/**
	 * ログインパスワードをリセットする
	 * 新しいパスワードを生成し、指定したメールアドレス宛に送信する
	 *
	 * @return void
	 */
	public function admin_reset_password() {
		// $this->params['prefix'] == 'admin';
		// if ((empty($this->params['prefix']) && !Configure::read('BcAuthPrefix.front'))) {
		// 	$this->notFound();
		// }
		if($this->BcAuth->user()) {
			$this->BcMessage->setError(__d('baser', '現在ログイン中です。'));
			$this->redirect(['action' => 'edit_password']);
		}
		$this->pageTitle = __d('baser', 'パスワードの再設定');

		$userModel = $this->BcAuth->authenticate['Form']['userModel'];
		if(strpos($userModel, '.') !== false) {
			list(, $userModel) = explode('.', $userModel);
		}
		if ($this->request->data) {
			$email = isset($this->request->data[$userModel]['email']) ? $this->request->data[$userModel]['email'] : '';

			if (mb_strlen($email) === 0) {
				$this->BcMessage->setError('メールアドレスを入力してください。');
				return;
			}
			$UserModel = InstantPageUtil::users();
			$user = $UserModel->findByEmail($email);
			if ($user) {
				$email = $user['User']['email'];
			}
			if (!$user || mb_strlen($email) === 0) {
				$this->BcMessage->setError('送信されたメールアドレスは登録されていません。');
				$this->request->data = [];
				return false;
			}

			$Token = ClassRegistry::init('InstantPage.InstantPagePasswordToken');
			$token = $Token->generate(array('InstantPageUser' => $user[$userModel]));

			$body = ['email' => $email, 'token' => $token, 'user' => $user];
			if (!$this->sendMail($email, __d('baser', 'パスワードを編集してください。'), $body, ['template' => 'InstantPage.instant_pages_reset_password'])) {
				$this->BcMessage->setError('メール送信時にエラーが発生しました。');
				return;
			}
			$this->BcMessage->setSuccess($email . ' 宛に新しいパスワード編集画面のURLを送信しました。'."\n".'メールを確認しパスワードの再設定を行ってください。');
			$this->request->data = [];
		}
	}


	/**
	 * [MyPage] ログイン画面
	 *
	 * @return void
	 */
	public function mypage_login() {
		$user = BcUtil::loginUser();
		if (!empty($user)) {
			$this->redirect('/cmsadmin/instant_page/instant_pages/');
		} else {
			$this->admin_login();
		}
	}
	/**
	 * [PUBLIC] ログイン画面
	 *
	 * @return void
	 */
	public function login() {
		$user = BcUtil::loginUser();
		if (!empty($user)) {
			$this->redirect('/cmsadmin/instant_page/instant_pages/');
		} else {
			$this->admin_login();
		}
	}


	/**
	 * [ADMIN] 管理者ログイン画面
	 *
	 * @return void
	 */
	public function admin_login() {
		if ($this->BcAuth->loginAction != ('/' . $this->request->url) && $this->request->url !== 'instant_page/instant_page_users/login' ) {
			$this->notFound();
		}
		if ($this->request->data) {
			//ログイン実行
			if ($this->request->is('post')) {
				$this->BcAuth->login();
			}
			// ログインユーザー取得
			$user = $this->BcAuth->user();
			$userModel = $this->BcAuth->authenticate['Form']['userModel'];
			if ($user && $this->isAuthorized($user)) {
				//ログイン成功時
				if (!empty($this->request->data[$userModel]['saved'])) {
					if (!$this->request->is('mobile')) {
						$this->setAuthCookie($this->request->data);
					} else {
						$this->BcAuth->saveSerial();
					}
					unset($this->request->data[$userModel]['save']);
				} else {
					$this->Cookie->destroy();
				}
				App::uses('BcBaserHelper', 'View/Helper');
				$BcBaser = new BcBaserHelper(new View());
				$this->BcMessage->setInfo(sprintf(__d('baser', 'ようこそ、%s さん。'), h($user['real_name_1'])));
				// インスタントページ一覧へリダイレクト
				$this->redirect('/cmsadmin/instant_page/instant_pages/');
				//$this->redirect($this->BcAuth->redirect());
			} else {
				// 失敗時
				$this->BcMessage->setError(__d('baser', 'アカウント名、パスワードが間違っています。'));
			}
		} else {
			$user = $this->BcAuth->user();
			if ($user && $this->isAuthorized($user)) {
				//$this->redirect($this->BcAuth->redirectUrl());
				$this->redirect('/cmsadmin/instant_page/instant_pages/');
			}
		}

		$pageTitle = __d('baser', 'ログイン');
		// $prefixAuth = Configure::read('BcAuthPrefix.' . $this->request->params['prefix']);
		// if ($prefixAuth && isset($prefixAuth['loginTitle'])) {
		// 	$pageTitle = $prefixAuth['loginTitle'];
		// }

		/* 表示設定 */
		$this->crumbs = [];
		$this->subMenuElements = '';
		$this->pageTitle = $pageTitle;
	}

	/**
	 * 認証クッキーをセットする
	 *
	 * @param array $data
	 * @return void
	 */
	public function setAuthCookie($data)
	{
		$userModel = $this->BcAuth->authenticate['Form']['userModel'];
		$cookie = [];
		foreach($data[$userModel] as $key => $val) {
			// savedは除外
			if ($key !== 'saved') {
				$cookie[$key] = $val;
			}
		}
		$this->Cookie->httpOnly = true;
		$this->Cookie->write(
			Inflector::camelize(str_replace('.', '', BcAuthComponent::$sessionKey)),
			$cookie,
			true,
			'+2 weeks'
		);	// 3つめの'true'で暗号化
	}

	public function mypage_logout() {
		$this->redirect('/cmsadmin/users/logout');
		$this->logout();
	}
	public function logout() {
		$this->redirect('/cmsadmin/users/logout');
		$user = BcUtil::loginUser();
	}
	/**
	 * [ADMIN] 管理者ログアウト画面
	 *
	 * @return void
	 */
	public function admin_logout() {
		$this->redirect('/cmsadmin/users/logout');
		$user = BcUtil::loginUser();
		// $this->request->data['User'] = $user;
		// $UserModel = InstantPageUtil::users();
		// $loginUser = $UserModel->find('first', ['conditions' => ['User.name' => $this->request->data['User']['name']]]);
		if (!empty($user)) {
			if ($this->BcAuth->logout()) {
				$this->redirect('/cmsadmin/instant_page/instant_pages/');
			}
		} else {
			$this->admin_logout();
		}
	}

/**
 * ログインパスワード編集前のトークンチェック
 * 新しいパスワードを生成し、指定したメールアドレス宛に送信する
 *
 * @return void
 */
	public function verify($token_str = null) {
		if ($this->BcAuth->user()) {
			$this->BcMessage->setError(__d('baser', '現在ログイン中です。'));
			$this->redirect(array('controller' => 'instant_page_users', 'action' => 'edit_password'));
		}
		$Token = ClassRegistry::init('InstantPage.InstantPagePasswordToken');
		$res = $Token->get($token_str);
		if ($res) {
			//$data = $res;
			$this->data = $res;
			//$this->set(compact('data'));
			$this->redirect(array(
				'controller' => 'instant_page_users',
				'action' => 'edit_password',
				'data' => $res
			));
			$this->edit_password($res);
		} else {
			$this->BcMessage->setError(__d('baser', '指定されたページを開くにはログインする必要があります。'));
			$this->redirect(array('controller' => 'instant_page_users', 'action' => 'login'));
		}
	}

	/**
	 * ログインパスワードを編集する
	 * 新しいパスワードを生成し、指定したメールアドレス宛に送信する
	 *
	 * @return void
	 */
	public function edit_password($data = []) {
		if (isset($this->request->params['named']['data'])) {
			$data = $this->request->params['named']['data'];
		} else {
			$data = $_SESSION['Auth'];
			unset($data['Admin']);
		}
		// if(!isset($data['InstantPageUser'])) {
		// 	$this->BcMessage->setError(__d('baser', '指定されたページを開くにはログインする必要があります。'));
		// 	$this->redirect(array('controller' => 'instant_page_users', 'action' => 'login'));
		// }

		if($this->request->data) {
			$id = $this->request->data['User']['id'];
			$selfUpdate = false;
			$updatable = true;
			// 保存先はユーザーモデルになる
			$UserModel = InstantPageUtil::users();
			if (empty($this->request->data)) {
				$this->request->data = $UserModel->read(null, $id);
			} else {
				// 入力補助用データのunset
				if (isset($this->request->data['InstantPageUser'])) {
					unset($this->request->data['InstantPageUser']);
				}
				if (isset($this->request->data['dummypass'])) unset($this->request->data['dummypass']);

				// パスワードがない場合は更新しない
				if ($this->request->data['User']['password_1'] || $this->request->data['User']['password_2']) {
					$this->request->data['User']['password'] = $this->request->data['User']['password_1'];
				}
				//独自バリデート
				$password1 = $this->request->data['User']['password_1'];
				if ($password1) unset($this->request->data['User']['password_1']);
				$password2 = $this->request->data['User']['password_2'];
				if ($password2) unset($this->request->data['User']['password_2']);
				$valdate = true;
				switch ($password1) {
					case strlen($password1) == '':
						$this->BcMessage->setError(__d('baser', 'パスワードを入力してください。'));
						$valdate = false;
						break;
					case strlen($password1) < 6:
						$this->BcMessage->setError(__d('baser', 'パスワードは6文字以上で入力してください。'));
						$valdate = false;
						break;
					case strlen($password1) >= 255:
						$this->BcMessage->setError(__d('baser', 'パスワードは255文字以内で入力してください。'));
						$valdate = false;
						break;
					case preg_match('/[a-z0-9@\.:\/\(\)#,@\[\]\+=&;\{\}!\$\*]+/i', $password1) == false;
						$this->BcMessage->setError(__d('baser', 'パスワードは半角英数字(英字は大文字小文字を区別)とスペース、記号(._-:/()#,@[]+=&;{}!$*)のみで入力してください。'));
						$valdate = false;
						break;
					case $password2 == '':
						$this->BcMessage->setError(__d('baser', '確認用のパスワードを入力してください。'));
						$valdate = false;
						break;
					case $password1 !== $password2:
						$this->BcMessage->setError(__d('baser', 'パスワードが同じものではありません。'));
						$valdate = false;
						break;

					default:
						break;
				}
				if ($valdate == false){
					return false;
				}

				// 権限確認
				if (!$updatable) {
					$this->BcMessage->setError(__d('baser', '指定されたページへのアクセスは許可されていません。'));

				// 自身のアカウントは変更出来ないようにチェック
				} elseif ($selfUpdate && $user['user_group_id'] != $this->request->data['User']['user_group_id']) {
					$this->BcMessage->setError(__d('baser', '自分のアカウントのグループは変更できません。'));
				} else {
					$saveData['User']['id'] = $this->request->data['User']['id'];
					$saveData = $UserModel->findById($saveData['User']['id']);
					// 管理画面内扱いなので、パスワードのハッシュ化は不要
					$saveData['User']['password'] = $this->request->data['User']['password'];
					$saveData['User']['user_group_id'] = $this->request->data['User']['user_group_id'];
					$UserModel->set($saveData['User'], ['calback' => false]);
					if ($UserModel->save()) {
						$this->getEventManager()->dispatch(new CakeEvent('Controller.Users.afterEdit', $this, [
							'user' => $this->request->data
						]));
						if ($selfUpdate) {
							$this->admin_logout();
						}
						$this->BcMessage->setSuccess('ユーザー「' . $this->request->data['User']['real_name_1'] . '」を更新しました。');
						$this->redirect(['controller' => 'instant_page_users', 'action' => 'login', $id]);
					} else {
						$this->BcMessage->setError($this->request->data['User']['real_name_1'] . '様のパスワードを'. __d('baser', '保存できませんでした。'));
						$this->redirect(['controller' => 'instant_page_users', 'action' => 'login', $id]);
					}
				}
			}

			exit;
		}
		// 入力用にユーザーデータもセットしておく
		if (!empty($data['InstantPageUser']['user_id'])) {
			$userData = $UserModel->findById($data['InstantPageUser']['user_id']);
			if (!empty($userData)) {
				$data['User'] = $userData['User'];
			}
		} else {
			// インスタントページユーザーにユーザーデータがセットされているので、入れ替える
			$data['User'] = $data['InstantPageUser'];
			$InstantPageUser = $this->{$this->modelClass}->find('first', ['conditions' => ['InstantPageUser.user_id' => $data['User']['id']]]);
			$data['InstantPageUser'] = $InstantPageUser['InstantPageUser'];
		}

		$this->data = $data;
		$this->set(compact('data'));
		$this->render('edit_password');

	}

/**
 * メッセージCSVファイルをダウンロードする
 *
 * @param int $mailContentId
 * @return void
 */
	public function admin_download_csv($data = null) {
		// メモリーオーバーのため、一時的にメモリーを開放する(要確認)
		//ini_set('memory_limit', '-1');
		// 1行目の見出し
		$csvHeader = [
			'id' => 'id',
			'name' => 'お名前',
			'real_name_1' => 'ご担当者名',
			'email' => 'E-mail',
			'company' => '企業名',
			'zip_code' => '郵便番号',
			'prefecture_id' => '都道府県',
			'address' => '住所',
			'tel' => '電話番号	',
			'created' => '作成日時',
			'modified' => '更新日時	',
		];
		$csv = [];

		App::uses('BcTextHelper', 'View/Helper');
		$this->BcText = new BcTextHelper(new View());
		$now = date('Y/m/d');

		$order = 'InstantPageUser.created DESC';
		$encoding = $this->request->query['encoding'];
		// 絞り込み検索結果を反映する。
		parse_str($this->request->query['conditions'], $output); //JSのシリアライズ文字列を配列に戻す
		// 不要なカラムのunset
		unset($output['_method']);
		unset($output['data']['_Token']);
		// conditionsの生成。
		$conditions = $this->_createAdminIndexConditions($output['data']);
		$records = $this->InstantPageUser->find('all', ['conditions' => $conditions, 'order' => $order]);

		foreach ($records as $data) {
			$line = [
			// 担当者情報
				'id' => $data['InstantPageUser']['id'],
				'name' => $data['User']['name'],
				'real_name_1' => $data['User']['real_name_1'],
				'email' => $data['User']['email'],
				'company' => $data['InstantPageUser']['company'],
				'prefecture_id' => $data['InstantPageUser']['prefecture_id'],
				'address' => $data['InstantPageUser']['address'],
				'tel' => $data['InstantPageUser']['tel'],
				'created' => date('Y/m/d H:i:s', strtotime($data['InstantPageUser']['created'])),
				'modified' => date('Y/m/d H:i:s', strtotime($data['InstantPageUser']['modified'])),
			];
			$line = $this->_CsvMsExcelFix($line);
			$csv[] = $line;
		}

			if ($csv) {
				// fファイル名を作成
				$filename = 'instant_page_users_' . date('YmdHis') . '.csv';

				// メモリ上に領域確保
				$fp = fopen('php://temp/maxmemory:' . (5 * 1024 * 1024), 'r+');

				// ヘッダのカラム順に書き出す
				foreach (array_merge(array($csvHeader), $csv) as $record) {
					$list = array();
					foreach ($csvHeader as $key => $value) {
						$list[] = $record[$key];
					}
					fputcsv($fp, $list);
				}

				// ファイルポインタを先頭へ
				rewind($fp);

				// リソースを読み込み文字列取得
				$output = stream_get_contents($fp);

				// CSVをWindowsのExcelで開くことを想定して
				// 改行コードをCRLF、文字コードをSJIS-win(CP932)へ
				$output = str_replace(PHP_EOL, "\r\n", $output);
				if ($this->request->query['encoding'] == 'SJIS-win') {
					$output = mb_convert_encoding($output, 'SJIS-win', 'utf8');
				}

				header('Content-Type: application/octet-stream');
				header("Content-Disposition: attachment; filename=" . $filename);

				echo $output;

				fclose($fp);
				exit;
			} else {
				$this->setMessage('データが見つかりませんでした。', true);
			}

		// 表示設定
		// $this->_setAdminIndexViewData();

		$this->subMenuElements = array();
		$this->pageTitle = 'CSVダウンロード';
				$this->autoRender = false;

		$unset = [
			'user_group_id' => '4',
			'nickname' => '',
		];
		$this->autoRender = false;
	}

	/**
	 * _CsvMsExcelFix
	 *
	 * @param type $line
	 * @return string
	 */
	protected function _CsvMsExcelFix($line) {

		if (!is_array($line)) {
			return $line;
		}

		foreach ($line as $key => $item) {
			// Excelで前ゼロつき数字を読み込んだ時に消えない対策
			// 数字で先頭が0の時は="0001"のようにする
			if (is_numeric($item) && substr($item, 0, 1) === '0') {
				$line[$key] = '="'. $item . '"';
			}
		}

		return $line;
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
		$company = '';
		$real_name_1 = '';
		$prefectureId = '';
		$email = '';
		// ユーザーname
		if (isset($data['User']['name'])) {
			$name = $data['User']['name'];
		}
		// ユーザー姓
		if (isset($data['User']['real_name_1'])) {
			$real_name_1 = $data['User']['real_name_1'];
		}
		// ユーザーemail
		if (isset($data['User']['email'])) {
			$email = $data['User']['email'];
		}
		// インスタントページユーザー会社名
		if (isset($data['InstantPageUser']['company'])) {
			$company = $data['InstantPageUser']['company'];
		}
		// インスタントページユーザー県名
		if (isset($data['InstantPageUser']['prefecture_id'])) {
			$prefectureId = $data['InstantPageUser']['prefecture_id'];
		}

		//$dataに残すと完全一致となるため、unset
		unset($data['_Token']);
		unset($data['User']['name']);
		unset($data['User']['real_name_1']);
		unset($data['User']['email']);
		unset($data['InstantPageUser']['company']);

		// 条件指定のないフィールドを解除
		if (isset($data['InstantPageUser'])) {
			foreach ($data['InstantPageUser'] as $key => $value) {
				if ($value === '') {
					unset($data['InstantPageUser'][$key]);
				}
			}
		}

		if (isset($data['InstantPageUser']) && $data['InstantPageUser'])  {
			$conditions = $this->postConditions($data);
		}
		if ($name) {
			$conditions['User.name LIKE'] = '%'.$name.'%';
		}
		if ($real_name_1) {
			$conditions['User.real_name_1 LIKE'] = '%'.$real_name_1.'%';
		}
		if ($email) {
			$conditions['User.email LIKE'] = '%'.$email.'%';
		}
		if ($company) {
			$conditions['InstantPageUser.company LIKE'] = '%'.$company.'%';
		}
		// JOIN 県名をセット
		if ($prefectureId) {
			$conditions['InstantPageUser.prefecture_id ='] = $prefectureId;
		}
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


/**
 * 仮登録インスタントページユーザーを実登録する
 */
	public function activate($token = null) {
		$activate = false;
		$checkDate = date('Y-m-d H:i:s');
		$RegisterMessage = ClassRegistry::init('InstantPage.RegisterMessage');
		$userInfo = $RegisterMessage->find('first', array(
			'conditions' => array(
				'token' => $token,
				'token_limit >=' => $checkDate,
			),
			'recursive' => -1
		));
		if ($userInfo) {
			if ($userInfo['RegisterMessage']['token_access']) {
				$this->setMessage(
				'登録を完了できませんでした。'. "\n"
				. "\n"
				. '既に登録済みか、URLが間違っています。'. "\n", true);
			} else {
				// インスタントページユーザーを新規作成
				$activate = $this->registerUser($userInfo['RegisterMessage']);
			}
		} else {
			$this->setMessage(
			'登録を完了できませんでした。'
			. "\n"
			. '既に有効期限が過ぎているか、URLが間違っています。'. "\n"
			. '再度、登録フォームから登録してください。'. "\n"
			. "\n"
			. 'ご不明な点がございましたら、お問い合わせフォームからお願いします。', true);
		}

		$this->set('activate', $activate);
		if ($activate) {

			$email = $activate['InstantPageUser']['email'];

			$RegisterMessage->id = $userInfo['RegisterMessage']['id'];
			$RegisterMessage->saveField('token_access', $checkDate);

			$options = array(
				'template' => 'InstantPage.activate',
			);
			$data['InstantPage'] = $activate;

			// インスタントページユーザーが登録完了した通知を、インスタントページユーザーにメールを送信する
			if (!$this->sendMail($email, 'インスタントページユーザー登録完了のお知らせ', $data, $options)) {
				$this->setMessage('登録完了メールを送信できませんでした。', true);
			}
			$MailContents = ClassRegistry::init('Mail.MailContents');
			$registerMailContents = $MailContents->find('first', array('conditions' => array('id' => 1)));

			// 管理者メール、または、メールプラグインで指定したメールアドレスを取得
			$emails = array();
			if ($registerMailContents['MailContents']['sender_1']) {
				// メールの送信先メールアドレス指定がある場合
				$sernder1 = explode(',', $registerMailContents['MailContents']['sender_1']);
				$emails = array_merge($emails, $sernder1);
			} else {
				// システム管理者の場合
				$systemAdmin = explode(',', $this->siteConfigs['email']);
				$emails = array_merge($emails, $systemAdmin);
			}

			$emails = array_unique($emails);
			$emails = array_filter($emails, "strlen");
			$emailAddresses = implode(",", $emails);

			// メールテンプレートで管理者用の文言の判定に使う
			$data['InstantPage']['Admin'] = true;

			// インスタントページユーザーが登録完了した通知を、管理者にメールを送信する
			if (!$this->sendMail($emailAddresses, 'インスタントページユーザー登録完了の通知', $data, $options)) {
				$this->setMessage('登録完了メールを送信できませんでした。', true);
			}
			// メールに保存されているパスワードのハッシュ化
			App::uses('AuthComponent', 'Controller/Component');
			$mailSaveData['id'] = $userInfo['RegisterMessage']['id'];
			$mailSaveData['password_1'] = AuthComponent::password($userInfo['RegisterMessage']['password_1']);
			$mailSaveData['password_2'] = AuthComponent::password($userInfo['RegisterMessage']['password_2']);
			$RegisterMessage->save($mailSaveData);

			// ログイン状態にする
			$this->BcAuth->login($activate['User']);

		} else {
			$this->setMessage('データベース処理中にエラーが発生しました。', true);
		}

		// 表示設定
		$this->pageTitle = 'インスタントページユーザー 登録確認';
	}


/**
 * インスタントページユーザーを登録する
 *
 * @return mixed
 */
	public function registerUser($userInfo) {
// $this->log('本登録');
// $this->log($userInfo['password_1']);
		// 県名がテキストだった場合、idで保存する
		if (isset($userInfo['prefecture_id'])) {
			$prefIds =Configure::read('InstantPage.pref');
			if (array_key_exists($userInfo['prefecture_id'], $prefIds)) {
				$userInfo['prefecture_id'] = $prefIds[$userInfo['prefecture_id']];
			}
		}
		$data = array();
		$data['InstantPageUser'] = $userInfo;
		$data['User']['name'] = $userInfo['name'];
		$data['User']['real_name_1'] = $userInfo['real_name_1'];
		$data['User']['real_name_2'] = $userInfo['real_name_2'];
		$data['User']['email'] = $userInfo['email'];
		$data['User']['password'] = $userInfo['password_1'];
		$data['User']['user_group_id'] = 4;
		//$data['InstantPageUser']['from'] = 'active_action';
		unset($data['InstantPageUser']['mode']);
		unset($data['InstantPageUser']['id']);
		unset($data['InstantPageUser']['referer']);
		unset($data['InstantPageUser']['_id_name']);
		unset($data['InstantPageUser']['name']);
		unset($data['InstantPageUser']['real_name_1']);
		unset($data['InstantPageUser']['real_name_2']);
		//unset($data['InstantPageUser']['email']);
		unset($data['InstantPageUser']['password_1']);
		unset($data['InstantPageUser']['password_2']);
		unset($data['InstantPageUser']['token_limit']);
		unset($data['InstantPageUser']['token_access']);

		$this->User->create($data);
		if (!$this->User->save($data, false)) {
//			return false;
		} else {
			$this->getEventManager()->dispatch(new CakeEvent('Model.User.AfterSave', $this, [
					'InstantPageUser' => $data['InstantPageUser']
				]));
			clearAllCache();
		}
		$data['User']['id'] = $this->User->getLastInsertId();

		$this->Session->delete("InstantPage.Register.referer");

		return $data;
	}


	public function payment()
	{
		//決済画面へのリンク生成
		//決済システムに送るパラメータを生成する。
	}


	public function payment_result()
	{
		//決済の結果取得
	}
}
