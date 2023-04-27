<?php
class InstantPageViewEventListener extends BcControllerEventListener {
	public $events = array(
			'beforeRender',
		);

	public function beforeRender(CakeEvent $event) {
		$View = $event->subject();
		// if ($View->request->here === '/mypage/instant_page/instant_page_users/login') {
		// 	$View->layout = 'mypage_login';
		// }
	}
}

