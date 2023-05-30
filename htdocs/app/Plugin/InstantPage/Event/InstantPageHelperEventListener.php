<?php

/**
 * [HelperEventListener] InstantPage
 *
 */
class InstantPageHelperEventListener extends BcHelperEventListener {

	/**
	 * 登録イベント
	 *
	 * @var array
	 */
	public $events = array(
		'BcFormTable.after',
		// 'Form.afterInput',
	);

	/**
	 * formAfterForm
	 * ユーザー編集・登録画面にプロフィール表示指定欄を追加する
	 *
	 * @param CakeEvent $event
	 */
	public function bcFormTableAfter(CakeEvent $event) {
		if (!BcUtil::isAdminSystem()) {
			return;
		}

		$View = $event->subject();
		if ($View->request->params['controller'] !== 'users') {
			return;
		}

		if (!in_array($View->request->params['action'], array('admin_edit', 'admin_add'))) {
			return;
		}

		if (in_array($event->data['id'], array('UserAdminEditForm', 'UserAdminAddForm'))) {
			echo $View->element('InstantPage.admin/instant_page_user_form');
		}
	}
}
