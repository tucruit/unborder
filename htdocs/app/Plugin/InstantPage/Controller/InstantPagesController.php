
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
		'InstantPages.InstantPages',
		'InstantPages.InstantPageUsers',
	);
	/**
	 * Components
	 *
	 * @var array
	 */
	public $components = array('BcAuth', 'Cookie', 'BcAuthConfigure', 'BcContents');

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
	 */
	public function beforeFilter() {
		parent::beforeFilter();
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

}
