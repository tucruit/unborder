<?php

/**
 * [HelperEventListener] OptionalLink
 *
 * @copyright		Copyright, Catchup, Inc.
 * @link			https://catchup.co.jp
 * @package			OptionalLink
 */
class OptionalLinkHelperEventListener extends BcHelperEventListener {

	/**
	 * 登録イベント
	 *
	 * @var array
	 */
	public $events = array(
		'Form.afterCreate',
		'Blog.Blog.beforeGetPostLink',
		'Blog.Blog.afterGetPostLink',
	);

	/**
	 * オプショナルリンク設定
	 * 
	 * @var array
	 */
	private $optionalLinkConfigs = array();

	/**
	 * オプショナルリンク設定のリスト
	 * 
	 * @var array
	 */
	private $optionalLinkConfigList = array();

	/**
	 * OptionalLinkデータ
	 * 
	 * @var array
	 */
	private $optionalLink = array();

	/**
	 * URL書換を機能させるかどうかを判定
	 * - OptionalLink データがあり、かつ設定が有効の際に書換る
	 * 
	 * @var boolean
	 */
	private $isRewrite = false;

	/**
	 * formAfterCreate
	 * - ブログ記事追加・編集画面に編集欄を追加する
	 * 
	 * @param CakeEvent $event
	 */
	public function formAfterCreate(CakeEvent $event) {
		if (!BcUtil::isAdminSystem()) {
			return;
		}

		$View = $event->subject();

		if ($View->request->params['controller'] !== 'blog_posts') {
			return;
		}

		if (!in_array($View->request->params['action'], array('admin_edit', 'admin_add'))) {
			return;
		}

		// ブログ記事追加・編集画面に編集欄を追加する
		if ($event->data['id'] === 'BlogPostForm') {
			if (isset($View->request->data['OptionalLinkConfig'])) {
				if (!empty($View->request->data['OptionalLinkConfig']['status'])) {
					$event->data['out'] = $event->data['out'] . $View->element('OptionalLink.admin/optional_link_form', array('model' => 'BlogPost'));
				}
			}
		}

		return;
	}

	/**
	 * blogBlogBeforeGetPostLink
	 * - リンクのURLとオプションを書き換える
	 * 
	 * @param CakeEvent $event
	 * @return boolean
	 * - true を返したら option の内容を渡す
	 * 
	 */
	public function blogBlogBeforeGetPostLink(CakeEvent $event) {
		// 管理システム側でのアクセスではURL変換を行わない
		if (BcUtil::isAdminSystem()) {
			return;
		}

		$View = $event->subject();

		$this->isRewrite	 = false; // URL書換を機能させるかの判定を初期化
		$this->optionalLink	 = null; // オプショナルリンク値を初期化。記事個別に判定するためリセットする
		// オプショナルリンク値の有無、ステータスにより判定する
		if ($this->hasOptionalLinkStatus(Hash::get($event->data, 'post'))) {
			if ($this->hasOptionalLinkConfigStatus($View, $event)) {
				$this->isRewrite = true;
			}
		}

		if ($this->isRewrite) {
			$this->optionalLink['OptionalLink'] = $event->data['post']['OptionalLink'];
			switch ($this->optionalLink['OptionalLink']['status']) {
				case '1': // ステータスがURLの場合
					$event->data['url'] = $this->optionalLink['OptionalLink']['name'];
					if ($this->optionalLink['OptionalLink']['blank']) {
						$event->data['options']['target'] = '_blank';
					}
					break;

				case '2': // ステータスがファイルの場合
					if ($this->optionalLink['OptionalLink']['file']) {
						$fileLink = $View->BcUpload->uploadImage('OptionalLink.file', $this->optionalLink['OptionalLink']['file'], array('imgsize' => '', 'output' => 'url'));
						if ($fileLink) {
							$event->data['url']					 = $fileLink;
							$event->data['options']['target']	 = '_blank'; // 問答無用でblank
						}
					}
					break;

				default:
					break;
			}
		}

		return $event->data['options'];
	}

	/**
	 * 特定したブログ記事から、その記事のオプショナルリンク値を判定する
	 * - オプショナルリンク値を持つ場合、そのステータス値から、URL書換えの実施を判定する
	 * 
	 * @param array $post
	 * @return boolean
	 */
	private function hasOptionalLinkStatus($post) {
		// 特定したブログ記事のオプショナルリンク値のステータスが未使用 or 存在しない値の場合はURL書換えを行わない
		if (Hash::get($post, 'OptionalLink.status')) {
			return true;
		}
		return false;
	}

	/**
	 * ブログ記事が属するブログが、オプショナルリンク設定値を持ち、かつ、書き換えが有効かどうかを判定する
	 * 
	 * @param Object $View
	 * @param Object $event
	 * @return boolean
	 */
	private function hasOptionalLinkConfigStatus($View, $event) {
		$this->modelInitializer($View, Hash::get($event->data, 'post.BlogPost.blog_content_id'));
		if (Hash::get($this->optionalLinkConfigs, 'OptionalLinkConfig.status')) {
			return true;
		}
		return true;
	}

	/**
	 * モデル登録用メソッド
	 * 
	 * @param View $View
	 */
	private function modelInitializer($View, $contentId = null) {
		if (!$this->optionalLinkConfigList) {
			$this->OptionalLinkConfigModel	 = ClassRegistry::init('OptionalLink.OptionalLinkConfig');
			$this->optionalLinkConfigList	 = $this->OptionalLinkConfigModel->find('all', array(
				'recursive'	 => -1,
				'callbacks'	 => false,
			));
		}

		if ($contentId) {
			$blogContentId = $contentId;
		} else {
			if (Hash::get($View->Blog->blogContent, 'id')) {
				$blogContentId = $View->Blog->blogContent['id'];
			}
		}
		if ($blogContentId) {
			foreach ($this->optionalLinkConfigList as $config) {
				if ($config['OptionalLinkConfig']['blog_content_id'] == $blogContentId) {
					$this->optionalLinkConfigs = $config;
					break;
				}
			}
		}
	}

	/**
	 * blogBlogAfterGetPostLink
	 * 
	 * @param CakeEvent $event
	 * @return type
	 */
	public function blogBlogAfterGetPostLink(CakeEvent $event) {
		// 管理システム側でのアクセスではURL変換を行わない
		if (BcUtil::isAdminSystem()) {
			return $event->data['out'];
		}

		if ($this->isRewrite) {
			$View				 = $event->subject();
			$event->data['out']	 = $this->rewriteUrl($View, $event->data);
		}
		return $event->data['out'];
	}

	/**
	 * 出力されるHTMLを書き換える
	 * 
	 * @param Object $View
	 * @param array $data
	 * @return string
	 */
	private function rewriteUrl($View, $data) {
		$isFileLink	 = false;
		$out		 = $data['out'];

		switch ($this->optionalLink['OptionalLink']['status']) {
			case '1': // URLの場合
				if ($this->optionalLink['OptionalLink']['nolink']) {
					$out = $data['title']; // リンクしない場合は文字列に置換する
				}
				break;

			case '2': // ファイルの場合
				if (Hash::get($this->optionalLink, 'OptionalLink.file')) {
					if ($View->OptionalLink->allowPublishFile($this->optionalLink)) {
						$isFileLink = true;
					}
				}

				if (!$isFileLink) {
					// file にデータがない場合、リンクしない文字列に置換する
					// ファイルの公開期間が終了している場合、リンクしない文字列に置換する
					$out = $data['title'];
				}
				break;

			default:
				break;
		}

		return $out;
	}
}
