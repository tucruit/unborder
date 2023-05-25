
<?php
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

		if (empty($this->siteConfigs['editor']) || $this->siteConfigs['editor'] === 'none') {
			return;
		}
		$this->helpers[] = $this->siteConfigs['editor'];
	}

	/**
	 * インスタントページ一覧を表示する
	 *
	 * @param int $bannerArea
	 */
	public function index($id = null) {
		$this->BcMessage->setError(__d('baser', 'まだ実装されていません'));
	}

	/**
	 * [ADMIN] インスタントページ一覧管理
	 *
	 */
	public function admin_index() {
		$this->pageTitle = $this->controlName . '一覧';
		$this->BcMessage->setError(__d('baser', 'まだ実装されていません'));
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

}
