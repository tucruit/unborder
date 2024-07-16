<?php
/*
 * [Contoller] InstantPageTemplateCategories
 */
//App::uses('ThemesController', 'Controller');

class InstantPageTemplateCategoriesController extends AppController {



	/**
	 * ControllerName
	 *
	 * @var string
	 */
	public $name = 'InstantPageTemplateCategories';




	/**
	 * Model
	 *
	 * @var array
	 */
	public $uses = array(
		'InstantPage.InstantPageTemplateCategory',
		'InstantPage.InstantPage','InstantPage.InstantPageTemplate','InstantPage.InstantPageUser'
	);




	/**
	 * コンポーネント
	 *
	 * @var array
	 */
	public $components = [
		'BcAuth',
		'Cookie',
		'BcAuthConfigure',
		'BcContents'
	];




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
	 * 管理画面タイトル
	 *
	 * @var string
	 */
	public $adminTitle = 'インスタントページ テンプレートカテゴリ';



	/**
	 * メッセージ用機能名
	 *
	 * @var string
	 */
	public $controlName = 'テンプレートカテゴリ';



	/**
	 * beforeFilter
	 *
	 * @return void
	 */
	public function beforeFilter()
	{
		parent::beforeFilter();
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
		//ページネーションの条件設定
		$this->paginate = array(
			//とりあえず全て取得するので条件なし
			//'conditions'	=> $conditions,
			'fields'		=> array(),
			'limit'			=> 30,
			'order' => 'InstantPageTemplateCategory.created DESC',
		);
		//現在のページの情報を自動で取得
		$datas = $this->paginate('InstantPageTemplateCategory');
		if ($datas) {
			$this->set('datas',$datas); //Viewへセットする。
		}
		$this->pageTitle = $this->controlName . '一覧';
	}




	/**
	 * [ADMIN] 追加
	 *
	 */
	public function admin_add() {
		//保存ボタン押下時
		if ($this->request->data) {
			//画像を一時ファイルからimgフォルダに格納する。
			$insertData = $this->InstantPageTemplateCategory->savePostImg($this->request->data, 1000, 1000);
			//送信されたデータを新規のレコードとしてDB挿入する
			if ($this->InstantPageTemplateCategory->save($insertData)) {
				$message = $this->controlName . '「'.$this->request->data['InstantPageTemplateCategory']['name'] . '」を追加しました。';
				$this->setMessage($message, false, true);
				$this->redirect(array('action' => 'index'));
			} else {
				$message = '入力エラーです。内容を修正してください。';
				$this->setMessage($message, true);
			}
		}
		//画像保存パス
		$this->set('imgPath', '/img/instant_page_template_category/');
		$this->pageTitle = $this->controlName . '新規登録';
		$this->render('form');
	}


	/**
	 * [ADMIN] 編集
	 *
	 * @param int $id
	 */
	public function admin_edit($id = null) {
		//保存ボタン押下時
		if ($this->request->data) {
			//画像を一時ファイルからimgフォルダに格納する。
			$insertData = $this->InstantPageTemplateCategory->savePostImg($this->request->data, 1000, 1000);
			//送信されたデータを新規のレコードとしてDB挿入する
			if ($this->InstantPageTemplateCategory->save($insertData)) {
				$message = $this->controlName . '「'.$this->request->data['InstantPageTemplateCategory']['name'] . '」を編集しました。';
				$this->setMessage($message, false, true);
				$this->redirect(array('action' => 'index'));
			} else {
				$message = '入力エラーです。内容を修正してください。';
				$this->setMessage($message, true);
			}
		}
		//編集フォームに入力するデータを取得しておく。
		$this->request->data = $this->InstantPageTemplateCategory->findById($id);
		//画像保存パス
		$this->set('imgPath', '/img/instant_page_template_category/');
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
