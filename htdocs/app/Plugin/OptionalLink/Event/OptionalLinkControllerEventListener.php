<?php

/**
 * [ControllerEventListener] OptionalLink
 *
 * @copyright		Copyright, Catchup, Inc.
 * @link			https://catchup.co.jp
 * @package			OptionalLink
 */
class OptionalLinkControllerEventListener extends BcControllerEventListener {

	/**
	 * 登録イベント
	 *
	 * @var array
	 */
	public $events = array(
		'initialize',
		'startup',
		'Blog.Blog.startup',
		'Blog.Blog.beforeRender',
		'Blog.BlogPosts.beforeRender',
	);

	/**
	 * OptionalLink設定情報
	 * 
	 * @var array
	 */
	private $optionalLinkConfigs = array();

	/**
	 * OptionalLinkモデル
	 * 
	 * @var Object
	 */
	private $OptionalLinkModel = null;

	/**
	 * OptionalLink設定モデル
	 * 
	 * @var Object
	 */
	private $OptionalLinkConfigModel = null;

	/**
	 * initialize
	 * 
	 * @param CakeEvent $event
	 */
	public function initialize(CakeEvent $event) {
		$Controller				 = $event->subject();
		$Controller->helpers[]	 = 'OptionalLink.OptionalLink';

		$enablePlugins = Configure::read('BcStatus.enablePlugins');
		if (in_array('Blog', $enablePlugins)) {
			if (!in_array('Blog.Blog', $Controller->helpers)) {
				$Controller->helpers[] = 'Blog.Blog';
			}
		}
	}

	/**
	 * OptionalLinkConfig モデルを準備する
	 * 
	 */
	private function setUpModel() {
		if (ClassRegistry::isKeySet('OptionalLink.OptionalLinkConfig')) {
			$this->OptionalLinkConfigModel = ClassRegistry::getObject('OptionalLink.OptionalLinkConfig');
		} else {
			$this->OptionalLinkConfigModel = ClassRegistry::init('OptionalLink.OptionalLinkConfig');
		}
	}

	/**
	 * startup
	 * 
	 * @param CakeEvent $event
	 */
	public function startup(CakeEvent $event) {
		if (!BcUtil::isAdminSystem()) {
			return;
		}

		$Controller = $event->subject();
		if (!in_array($Controller->request->params['controller'], array('blog_posts', 'blog_contents'))) {
			return;
		}

		$this->setUpModel();
		// ブログ記事編集画面でタグの追加を行うと Undefined が発生するため判定
		if (!empty($Controller->BlogContent->id)) {
			$this->optionalLinkConfigs	 = $this->OptionalLinkConfigModel->find('first', array(
				'conditions' => array(
					'OptionalLinkConfig.blog_content_id' => $Controller->BlogContent->id
				),
				'recursive'	 => -1
			));
			$this->OptionalLinkModel	 = ClassRegistry::init('OptionalLink.OptionalLink');
		}
	}

	/**
	 * blogBlogStartup
	 * 
	 * @param CakeEvent $event
	 */
	public function blogBlogStartup(CakeEvent $event) {
		if (BcUtil::isAdminSystem()) {
			return;
		}

		$Controller					 = $event->subject();
		$this->setUpModel();
		$this->optionalLinkConfigs	 = $this->OptionalLinkConfigModel->find('first', array(
			'conditions' => array(
				'OptionalLinkConfig.blog_content_id' => $Controller->BlogContent->id
			),
			'recursive'	 => -1
		));
	}

	/**
	 * blogBlogBeforeRender
	 * 
	 * @param CakeEvent $event
	 */
	public function blogBlogBeforeRender(CakeEvent $event) {
		$Controller = $event->subject();
		if (Hash::get($Controller->blogContent, 'BlogContent')) {
			$Controller->set('OptionalLinkConfig', $this->optionalLinkConfigs);
		}
		// プレビューの際は編集欄の内容を送る
		if ($Controller->preview) {
			if (Hash::get($Controller->data, 'OptionalLink')) {
				$Controller->viewVars['post']['OptionalLink'] = $Controller->data['OptionalLink'];
			}
		}

		if ($this->isRedirect($Controller)) {
			$this->redirectOptionalLinkUrl($Controller);
		}
	}

	/**
	 * ブログ記事詳細へのアクセス時、オプショナルリンクの値でリダイレクトするか判定する
	 * - 記事プレビューは非対応
	 * 
	 * @param Opject $Controller
	 * @return boolean
	 */
	private function isRedirect($Controller) {
		if (!Hash::get($Controller->viewVars, 'single')) {
			return false;
		}

		// プレビューの際はオプショナルリンク設定を取得しないため対応しない
		if (!$this->optionalLinkConfigs) {
			return false;
		}
		if (!$this->optionalLinkConfigs['OptionalLinkConfig']['status']) {
			return false;
		}

		if (Hash::get($Controller->viewVars, 'post.OptionalLink.status')) {
			return true;
		}

		return false;
	}

	/**
	 * ブログ記事詳細へのアクセス時、オプショナルリンクの値でリダイレクトさせて、記事詳細画面を表示しないようにする
	 * - ViewEventHelperの処理と似ているが、動作の流れが異なるため共通メソッド化はしない
	 * 
	 * @param Opject $Controller
	 */
	private function redirectOptionalLinkUrl($Controller) {
		$optionalLinkData['OptionalLink'] = $Controller->viewVars['post']['OptionalLink'];

		switch ($optionalLinkData['OptionalLink']['status']) {
			case '1': // URLの場合
				if ($optionalLinkData['OptionalLink']['nolink']) {
					$Controller->notFound();
				}

				$link = $optionalLinkData['OptionalLink']['name'];
				if ($link) {
					// /files〜 の場合はドメインを付与して絶対指定扱いにする
					$regexFiles = '/^\/files\/.+/';
					if (preg_match($regexFiles, $link)) {
						// /lib/Baser/basics.php
						$link = topLevelUrl(false) . $link;
						//$link = Configure::read('BcEnv.siteUrl') . $link;
					}
					$Controller->redirect($link);
				} else {
					$this->log('URL指定のURLが未入力の記事に対してアクセスがありました。記事ID: ' . $optionalLinkData['OptionalLink']['blog_post_id'], LOG_OPTIONAL_LINK);
					$Controller->notFound();
				}

				break;

			case '2': // ファイルの場合
				$optionalLink = $optionalLinkData['OptionalLink'];
				if ($optionalLink['file']) {
					App::uses('BcUploadHelper', 'View/Helper');
					$View			 = new View();
					$View->BcUpload	 = new BcUploadHelper($View);
					$fileLink		 = $View->BcUpload->uploadImage('OptionalLink.file', $optionalLink['file'], array('imgsize' => '', 'output' => 'url'));
					if ($fileLink) {
						$optionalLink['name'] = $fileLink;
					}
				} else {
					$this->log('ファイル指定のファイルが未入力の記事に対してアクセスがありました。記事ID: ' . $optionalLinkData['OptionalLink']['blog_post_id'], LOG_OPTIONAL_LINK);
					$Controller->notFound();
				}
				$optionalLinkData['OptionalLink'] = $optionalLink;

				$link = $optionalLinkData['OptionalLink']['name'];
				if ($link) {
					App::uses('OptionalLinkHelper', 'OptionalLink.View/Helper');
					$View				 = new View();
					$View->OptionalLink	 = new OptionalLinkHelper($View);
					// ファイルの公開期間をチェックする
					if ($View->OptionalLink->allowPublishFile($optionalLinkData)) {
						// /files〜 の場合はドメインを付与して絶対指定扱いにする
						$regexFiles = '/^\/files\/.+/';
						if (preg_match($regexFiles, $link)) {
							$link = $View->BcBaser->getUri($link);
						}
						$Controller->redirect($link);
					} else {
						$this->log('ファイル指定の公開期間が終了している記事に対してアクセスがありました。記事ID: ' . $optionalLinkData['OptionalLink']['blog_post_id'], LOG_OPTIONAL_LINK);
						$Controller->notFound();
					}
				}
				break;

			default:
				break;
		}
	}

	/**
	 * blogBlogPostsBeforeRender
	 * 
	 * @param CakeEvent $event
	 */
	public function blogBlogPostsBeforeRender(CakeEvent $event) {
		if (!BcUtil::isAdminSystem()) {
			return;
		}

		// オプショナルリンク設定データがない場合は何もせず通常動作にする
		if (!$this->optionalLinkConfigs) {
			return;
		}

		$Controller = $event->subject();
		if (!in_array($Controller->request->params['action'], array('admin_edit', 'admin_add'))) {
			return;
		}

		if ($Controller->request->params['action'] == 'admin_add') {
			$defalut									 = $this->OptionalLinkModel->getDefaultValue();
			$Controller->request->data['OptionalLink']	 = $defalut['OptionalLink'];
		} else {
			if (!Hash::get($Controller->request->data, 'OptionalLink.id')) {
				$defalut									 = $this->OptionalLinkModel->getDefaultValue();
				$Controller->request->data['OptionalLink']	 = $defalut['OptionalLink'];
			}
		}

		$Controller->request->data['OptionalLinkConfig'] = $this->optionalLinkConfigs['OptionalLinkConfig'];
	}
}
