<?php
class InstantPageControllerEventListener extends BcControllerEventListener {
	public $events = array(
		'startup',
		'initialize',
		'beforeRender',
		'Users.beforeRender',
		'Mail.Mail.startup',
		'Mail.Mail.beforeSendEmail',
		'Mail.Mail.afterSendEmail'
		);

	/**
	 * startup
	 * @param CakeEvent $event
	 * @return type
	 */
	public function startup(CakeEvent $event) {
		$Controller = $event->subject();
		// 管理画面の場合はスルー
		if(BcUtil::isAdminSystem()) {
			return;
		}
		// フィードの場合はスルー（管理画面のダッシュボードで読み込まれているため）
		if(preg_match('/^feed\//', $Controller->request->url)) {
			return;
		}
		// フィードの場合はスルー（管理画面のダッシュボードで読み込まれているため）
		if(preg_match('/^lp\//', $Controller->request->url)) {
			return;
		}
		// 認証クラスがない場合はスルー
		if(!isset($Controller->BcAuth)) {
			return;
		}
		// ログイン画面の場合はスルー
		if(('/' . $Controller->request->url) == $Controller->BcAuth->loginAction) {
			return;
		}
		// パスワードリセット画面の場合はスルー
		if(($Controller->request->url) == 'users/reset_password') {
			return;
		}
		// パスワードリセット画面の場合はスルー
		if(($Controller->request->url) == 'instant_page/instant_page_users/reset_password') {
			return;
		}
		// パスワード編集画面の場合はスルー
		if(preg_match('/^edit_password\//', $Controller->request->url)) {
			return;
		}
		// パスワードリセット画面の場合はスルー
		if(preg_match('/^partner_list\//', $Controller->request->url)) {
			return;
		}
		// リクエストアクションの場合はスルー
		if($Controller->request->is('requested')) {
			return;
		}
		// 認証されていない場合はログイン画面にリダイレクト
		if(preg_match('/^instant_pages\//', $Controller->request->url) && !$Controller->BcAuth->user()) {
			/** 管理画面にログインしている場合は除外 **/
			//if (!BcUtil::isAdminUser()) {
				// /partner/** に未ログイン状態でアクセス後、ログインした後に /partner/** へ遷移させる。
				// $current_path = $Controller->request->url;
				// $Controller->Session->write('Auth.redirect', '/'. $current_path);
				//$Controller->redirect($Controller->BcAuth->loginAction);
			//}
		}
	}

	/**
	 * initialize
	 *
	 * @param CakeEvent $event
	 */
	public function initialize(CakeEvent $event) {
		$Controller = $event->subject();

		// /**
		//  * authCookie 期限延長
		//  * ログイン状態保持設定 & ログイン画面からの遷移 & 認証情報有り
		//  */
		// //1. セッション一時書き込み
		// if (preg_match('/^\/users\/login/', $Controller->referer()) &&
		// 	Hash::get($Controller->request->params, 'controller') == 'users' &&
		// 	Hash::get($Controller->request->params, 'action')     == 'login' &&
		// 	Hash::get($Controller->request->data, 'User.saved')) {

		// 	CakeSession::write(FrontAuthUtil::$tmpLoginDataKey, $Controller->request->data);
		// }
		// //2. セッション一時データ取り出し 期限更新
		// if (preg_match('/^teacher\//', $Controller->params->url)) {
		// 	$tmpLoginData = CakeSession::read(FrontAuthUtil::$tmpLoginDataKey);

		// 	if ($tmpLoginData) {
		// 		$sessionKey = Configure::read('BcAuthPrefix.front.sessionKey');
		// 		$authKey    = "Auth{$sessionKey}";
		// 		$auth       = $Controller->Cookie->read($authKey);
		// 		if ($auth) {
		// 			$this->log($auth);
		// 			//期限更新
		// 			$Controller->Cookie->write($authKey, $auth, true, '+'.FrontAuthUtil::$extensionAuthExpires.' days');	// 3つめの'true'で暗号化
		// 			//削除
		// 			CakeSession::delete(FrontAuthUtil::$tmpLoginDataKey);
		// 		}
		// 	}
		// }
	}

	public function beforeRender(CakeEvent $event) {
		$Controller = $event->subject();
		if (!BcUtil::isAdminSystem()) {
			$params = $Controller->request->params;
			if ($params['controller'] == 'instant_pages' && $params['action'] == 'detail') {
				$instantPageData = isset($Controller->viewVars['data']['InstantPage']) ? $Controller->viewVars['data']['InstantPage'] : [];
				if (!empty($instantPageData) && $instantPageData['instant_page_template_id']) {
					$InstantpageTemplateModel = ClassRegistry::init('InstantPage.InstantpageTemplate');
					$InstantpageTemplateList = $InstantpageTemplateModel->find('list',['fields' => ['id', 'name']]);
					$Controller->theme = $InstantpageTemplateList[$instantPageData['instant_page_template_id']];
				}
				$Controller->layout = 'InstantPage.instant';
			}
			return;
		}
		$user = BcUtil::loginUser();
		if (isset($user['user_group_id']) && InstantPageUtil::isMemberGroup($user['user_group_id'])) {
			$Controller->layout = 'InstantPage.mypage';
			$current_path = $Controller->request->url;
			$InstantPageUserModel = ClassRegistry::init('InstantPage.InstantPageUser');
			$instantPageUser = $InstantPageUserModel->find('first', [
				'conditions' => ['InstantPageUser.user_id = ' => $user['id']],
				'recursive'	 => -1
			]);
			// インスタントページユーザーは自分のアカウント設定ページにリダイレクト
			if (strpos($current_path, 'cmsadmin/instant_page/instant_page_users/') !== false &&
				$current_path !== 'cmsadmin/instant_page/instant_page_users/edit/'. $instantPageUser['InstantPageUser']['id']) {
				$Controller->redirect('/cmsadmin/instant_page/instant_page_users/edit/'. $instantPageUser['InstantPageUser']['id']);
			} elseif (strpos($current_path, 'cmsadmin/users/edit/') !== false) {
				$Controller->redirect('/cmsadmin/instant_page/instant_page_users/edit/'. $instantPageUser['InstantPageUser']['id']);
			}
		}
	}

	/**
	 * usersBeforeRender
	 * ユーザー情報追加画面で実行し、InstantPageUserの初期値を設定する
	 *
	 * @param CakeEvent $event
	 */
	public function usersBeforeRender(CakeEvent $event) {
		if (!BcUtil::isAdminSystem()) {
			return;
		}

		$Controller = $event->subject();
		if (in_array($Controller->request->params['action'], array('admin_edit', 'admin_add'))) {
			if ($Controller->request->params['action'] === 'admin_add') {
				App::uses('InstantPageUser', 'InstantPage.Model');
				$InstantPageUserModel = new InstantPageUser();
				$default = $InstantPageUserModel->getDefaultValue();
				$Controller->request->data['InstantPageUser'] = $default['InstantPageUser'];
				return;
			}

			if (empty($Controller->request->data['InstantPageUser']['id'])) {
				App::uses('InstantPageUser', 'InstantPage.Model');
				$InstantPageUserModel = new InstantPageUser();
				$default = $InstantPageUserModel->getDefaultValue();
				$Controller->request->data['InstantPageUser'] = $default['InstantPageUser'];
			}
		}

		return;
	}



	public function mailMailStartup(CakeEvent $event) {

		$Controller = $event->subject();
		//baserCMS４系ではmail_contentテーブルにnameが無い
		if ($Controller->dbDatas['mailContent']['MailContent']['id'] == 1 && $Controller->action == 'index') {
			$refererSession = $event->subject->Session->read("InstantPage.Register.referer");
			if (!$refererSession) {
				$referer = env('HTTP_REFERER');
				$event->subject->Session->write("InstantPage.Register.referer", $referer);
			}
		}

		$event->subject->Security->disabledFields = array_merge(
			$event->subject->Security->disabledFields,
			array(
				'agree',
				// 'MailMessage.email_check',
				// 'MailMessage.name_check',
				'MailMessage.token',
				'MailMessage.token_limit',
				'MailMessage.token_access',
				'MailMessage.password_1',
				'MailMessage.referer',
			)
		);

		//baserCMS４系ではmail_contentテーブルにnameが無い
		if ($Controller->dbDatas['mailContent']['MailContent']['id'] == 1 && $Controller->action == 'submit') {

			foreach ($event->subject->dbDatas['mailFields'] as $key => $item) {
				if ($item['MailField']['field_name'] == 'token') {
					$event->subject->request->data['MailMessage']['token'] = rand(0, 100) . uniqid();
				}
				if ($item['MailField']['field_name'] == 'token_limit') {
					$activateMaxTime = Configure::read('InstantPage.activateMaxTime');
					$event->subject->request->data['MailMessage']['token_limit'] = date('Y-m-d H:i:s', (time() + 3600 * $activateMaxTime));
				}
				if ($item['MailField']['field_name'] == 'token_access') {
					$event->subject->request->data['MailMessage']['token_access'] = null;
				}
				if ($item['MailField']['field_name'] == 'referer') {
					$referer = $event->subject->Session->read("Config.userAgent");
					$event->subject->request->data['MailMessage']['referer'] = $referer;
				}

			}
		}
		return true;
	}

	/**
	 * mailMailBeforeSendEmail
	 *
	 * @param CakeEvent $event
	 * @return boolean
	 */
	public function mailMailBeforeSendEmail(CakeEvent $event)
	{
		$Controller = $event->subject();
		if ($Controller->dbDatas['mailContent']['MailContent']['id'] == 3) {
			$authorId = $event->data['data']['MailMessage']['author'];
			if ($authorId) {
				if (ClassRegistry::isKeySet('InstantPage.InstantPageUser')) {
					$InstantPageUserModel = ClassRegistry::getObject('InstantPage.InstantPageUser');
				} else {
					$InstantPageUserModel = ClassRegistry::init('InstantPage.InstantPageUser');
				}
				// 管理者の送信先をインスタントページ制作者あてに変更する
				$author = $InstantPageUserModel->find('first', ['conditions' => ['InstantPageUser.id' => $authorId]]);
				$email = $author['User']['email'];
				$Controller->siteConfigs['email'] = $email;
			}
		}

		return true;
	}

	/**
	 * mailMailAfterSendEmail
	 *
	 * @param CakeEvent $event
	 * @return boolean
	 */
	public function mailMailAfterSendEmail(CakeEvent $event)
	{
		$Controller = $event->subject();
		//スマートな方法を考える
		if ($Controller->dbDatas['mailContent']['MailContent']['id'] == 3
			|| $Controller->dbDatas['mailContent']['MailContent']['id'] == 5) {
			$url = $event->data['data']['MailMessage']['url'];
			if ($url) {
				$Controller->redirect($url.'?status=thanks');
			}
		}

		return true;
	}

}
