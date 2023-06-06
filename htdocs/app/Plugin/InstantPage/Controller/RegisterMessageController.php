<?php
class RegisterMessageController extends AppController {
	public $uses = ['InstantPage.RegisterMessage', 'InstantPage.InstantPageUser'];
	/**
	 * コントローラー名
	 *
	 * @var string
	 */
	public $name = 'RegisterMessage';


/**
 * ヘルパー
 *
 * @var array
 * @access public
 */
	public $helpers = array(
		'BcText',
		'BcAuth'
	);

/**
 * コンポーネント
 *
 * @var array
 */
	public $components = array(
		'BcReplacePrefix',
		'Cookie',
		'BcAuthConfigure',
		'BcEmail',
		'BcAuth' => array(
			'authenticate' => array(
				'all' => array(
					'userModel' => 'InstantPage.InstantPageUser'
				)
			)
		)
	);


	/**
	 * [ADMIN] beforeFilter
	 *
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->BcAuth->allow('ajax_id_check','ajax_email_check');
		$this->Security->validatePost = false;
		$this->Security->csrfCheck = false;
	}

	/*
	 * id チェック
	 */

	public function ajax_id_check($id = null) {

		$this->layout = false;
		$this->autoRender = false;
		$errParams = array();
		if (!$id && $this->request->data('id')) {
			$id = $this->request->data('id');
		}

		// 英数字 +ハイフン・アンダースコア以外が使われていないかチェック
		if (!InstantPageUtil::alphaNumericPlus($id)) {
			$id = false;
			$errParams = ['status' => false, 'message' => '形式が無効です。'];
		}

		if ($id) {
			$InstantPageUser = ClassRegistry::init('User');
			$users = $InstantPageUser->find('all', array(
				'conditions' => [
					'User.name' => $id,
				],
				'recursive' => -1
			));
			$registerMessage = $this->{$this->modelClass}->find('all', array(
				'conditions' => array(
					'RegisterMessage.name' => $id,
					'RegisterMessage.token_limit >=' => date('Y-m-d H:i:s'),
				),
				'recursive' => -1
			));

			if ($users || $registerMessage) {
			//if ($users) {
				$errParams = [
					'status' => false,
					'message' => '既に登録されているアカウント名です。別のアカウント名をご入力ください。',
				];
			} else {
				$errParams = [
					'status' => true,
					'message' => '利用可能なアカウント名です。',
				];
			}
		} elseif(empty($errParams)) {
			$errParams = [
				'status' => false,
				'message' => 'ログインアカウント名が入力されていません。ログインアカウント名をご入力ください。',
			];
		}
		$errParams['field'] = '.nameCheck';
		return json_encode($errParams);
	}

	/*
	 * e-mail チェック
	 */
	public function ajax_email_check($email = null) {

		$this->layout = false;
		$this->autoRender = false;
		$errParams = array();
		if (!$email && $this->request->data('id')) {
			$email = $this->request->data('id');
		}
		if (!Validation::email($email)) {
			$text = $email. 'E-mailの形式が無効です。';
			$email = false;
			$errParams = ['status' => false, 'message' => $text];
		}

		if ($email) {
			$InstantPageUser = ClassRegistry::init('User');
			$users = $InstantPageUser->find('all', array(
				'conditions' => [
					'User.email' => $email
				],
				'recursive' => -1
			));

			if ($users) {
				$errParams = [
					'status' => false,
					'message' => '既に登録されているメールアドレスです。別のメールアドレスをご入力ください。',
				];
			} else {
				$errParams = [
					'status' => true,
					'message' => '利用可能なメールアドレスです。',
				];
			}
		} elseif(empty($errParams)) {
			$errParams = [
				'status' => false,
				'message' => 'メールアドレスが入力されていません。メールアドレスをご入力ください。',
			];
		}
		$errParams['field'] = '.mailCheck';
		return json_encode($errParams);
	}

}
