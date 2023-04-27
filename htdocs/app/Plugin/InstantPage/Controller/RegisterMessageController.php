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
		$this->BcAuth->allow('ajax_id_check');
		$this->Security->validatePost = false;
		$this->Security->csrfCheck = false;
	}

	/*
	 * e-mail チェック
	 */

	public function ajax_id_check($id = null) {

		$this->layout = false;
		$this->autoRender = false;
		$errParams = array();
		if (!$id && $this->request->data('id')) {
			$id = $this->request->data('id');
		}
		if (!Validation::alphaNumeric($id)) {
			$id = false;
			$errParams = array(
				'status' => false,
				'message' => '形式が無効です。',
			);
		}

		if ($id) {
			$InstantPageUser = ClassRegistry::init('InstantPage.InstantPageUser');
			$users = $InstantPageUser->find('all', array(
				'conditions' => array(
					'InstantPageUser.name' => $id,
				),
				'recursive' => -1
			));
			// $registerMessage = $this->{$this->modelClass}->find('all', array(
			// 	'conditions' => array(
			// 		'RegisterMessage.name' => $id,
			// 		'RegisterMessage.token_limit >=' => date('Y-m-d H:i:s'),
			// 	),
			// 	'recursive' => -1
			// ));

			//if ($users || $registerMessage) {
			if ($users) {
				$errParams = array(
					'status' => false,
					'message' => '既に登録されているIDです。別のIDをご入力ください。',
				);
			} else {
				$errParams = array(
					'status' => true,
					'message' => '利用可能なIDです。',
				);
			}
		} elseif(empty($errParams)) {
			$errParams = array(
				'status' => false,
				'message' => 'ログインIDが入力されていません。ログインIDをご入力ください。',
			);
		}
		$errParams['field'] = '.nameCheck';
		return json_encode($errParams);
	}

}
