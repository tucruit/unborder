<?php
class DontTouchMeViewEventListener extends BcViewEventListener {

	/**
	 * 登録イベント
	 *
	 * @var array
	 */
	public $events = array(
		'Contents.footer',
	);

	/**
	 * contentsFooter
	 * コンテンツコントローラのfooter
	 *
	 */
	public function contentsFooter(CakeEvent $event) {
		// 管理システムへのアクセスの場合はFbOgpを表示しない
		if (BcUtil::isAdminSystem()) {
			echo $event->subject()->element('DontTouchMe.dont_touch_me_contents_footer');
			return;
		}
		return;
	}

}
