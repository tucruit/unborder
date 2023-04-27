
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
	 * beforeFilter
	 *
	 */
	public function beforeFilter() {
		parent::beforeFilter();
	}

	/**
	 * バナー一覧を表示する
	 *
	 * @param int $bannerArea
	 */
	public function index($id = null) {
		$this->BcMessage->setError(__d('baser', 'まだ実装されていません'));
	}

	/**
	 * [ADMIN] インスタントページユーザー一覧管理
	 *
	 */
	public function admin_index() {
		$this->BcMessage->setError(__d('baser', 'まだ実装されていません'));
	}

}
