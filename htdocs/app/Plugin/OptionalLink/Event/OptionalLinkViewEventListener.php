<?php

/**
 * [ViewEventListener] OptionalLink
 *
 * @copyright		Copyright, Catchup, Inc.
 * @link			https://catchup.co.jp
 * @package			OptionalLink
 */
class OptionalLinkViewEventListener extends BcViewEventListener {

	/**
	 * 登録イベント
	 * 
	 * @var array
	 */
	public $events = array(
		'Blog.Blog.beforeGetViewFileName'
	);

	/**
	 * blogBlogbeforeGetViewFileName
	 * 
	 * @param CakeEvent $event
	 * @return string
	 */
	public function blogBlogbeforeGetViewFileName(CakeEvent $event) {
		if (!BcUtil::isAdminSystem()) {
			return $event->data['name'];
		}

		$View = $event->subject();
		// Feedへのアクセスの場合、プラグイン側のビューに切り替える
		if (isset($View->request->params['ext']) && $View->request->params['ext'] == 'rss') {
			// Viewが持つプロパティ（name、plugin、viewPath）の切替だけではできなかったため、実パスを生成
			$viewPath			 = App::path('View', 'OptionalLink');
			$pluginViewPath		 = $viewPath[0] . 'Blog/rss/index.php';
			$event->data['name'] = $pluginViewPath;
		}
		return $event->data['name'];
	}
}
