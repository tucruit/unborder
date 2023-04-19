<?php

/**
 * [Helper] オプショナルリンク
 *
 * @copyright		Copyright, Catchup, Inc.
 * @link			https://catchup.co.jp
 * @package			OptionalLink
 */
class OptionalLinkHelper extends AppHelper {

	/**
	 * ヘルパー
	 *
	 * @var array
	 */
	public $helpers = array('BcBaser', 'Blog', 'BcUpload');

	/**
	 * アップロードファイルの保存URL
	 *
	 * @var string
	 */
	public $savedUrl = '';

	/**
	 * アップロードファイルの保存パス
	 *
	 * @var string
	 */
	public $savePath = '';

	/**
	 * constructer
	 *
	 * @param View $View
	 * @param array $settings
	 */
	public function __construct(View $View, $settings = array()) {
		parent::__construct($View, $settings);
		$this->savedUrl	 = baseUrl() . 'files' . DS . 'optionallink' . DS;
		$this->savePath	 = OptionalLinkUtil::getSavePath() . DS;
	}

	/**
	 * 公開状態を取得する
	 *
	 * @param array $data
	 * @return boolean 公開状態
	 */
	public function allowPublish($data) {
		if (isset($data['OptionalLink'])) {
			$data = $data['OptionalLink'];
		} elseif (isset($data['OptionalLinkConfig'])) {
			$data = $data['OptionalLinkConfig'];
		}
		$allowPublish = (int) $data['status'];
		return $allowPublish;
	}

	/**
	 * ファイルの公開状態を取得する
	 *
	 * @param array リンク状態のデータ
	 * @return boolean 公開状態
	 */
	public function allowPublishFile($data) {
		if (isset($data['OptionalLink'])) {
			$data = $data['OptionalLink'];
		}
		$allowPublish = true;
		// 期限を設定している場合に条件に該当しない場合は強制的に非公開とする
		if (($data['publish_begin'] != 0 && $data['publish_begin'] >= date('Y-m-d H:i:s')) ||
			($data['publish_end'] != 0 && $data['publish_end'] <= date('Y-m-d H:i:s'))) {
			$allowPublish = false;
		}
		return $allowPublish;
	}

	/**
	 * オプショナルリンクの有効を判定する
	 *
	 * @param array $post
	 * @return boolean
	 */
	public function isStatus($post = array()) {
		if (!empty($post['OptionalLinkConfig']['status'])) {
			if ($post['OptionalLinkConfig']['status']) {
				return true;
			}
		}
		return false;
	}

	/**
	 * リンク文字列をチェックして判定する
	 *
	 * @param array $post
	 * @return string
	 */
	public function judgeLinkKinds($post = array()) {
		$str = '';
		if ($post['OptionalLink']['nolink']) {
			$str = 'nolink';
		} elseif ($post['OptionalLink']['status']) {
			switch ($post['OptionalLink']['status']) {
				case '1':
					if (!empty($post['OptionalLink']['name'])) {
						if ($post['OptionalLink']['blank']) {
							$str = 'external';
						}
						$content = trim(strip_tags($post['OptionalLink']['name']));
						// URLを分解する
						$links	 = parse_url($content);
						$path	 = pathinfo($content);

						if (!empty($path['extension'])) {
							$str = OptionalLinkUtil::getUrlExtension($path['extension']);
						}
						if ($str) {
							return $str;
						}

						if (!empty($links['host'])) {
							if ($_SERVER['HTTP_HOST'] != $links['host']) {
								$str = 'external';
							}
						}
					}
					break;

				case '2':
					if (!empty($post['OptionalLink']['file'])) {
						$content = trim(strip_tags($post['OptionalLink']['file']));
						// URLを分解する
						$links	 = parse_url($content);
						$path	 = pathinfo($content);

						if (!empty($path['extension'])) {
							$str = OptionalLinkUtil::getUrlExtension($path['extension']);
						}
						if ($str) {
							return $str;
						}
					}
					break;

				default:
					break;
			}
		}
		return $str;
	}

	/**
	 * オプショナルリンクの設定を反映したブログ記事リンクを出力する
	 * - posts ビューで記事を取得する箇所で利用できる
	 * - 利用方法：$this->OptionalLink->getPostTitle($post)
	 *
	 * @param array $post
	 * @param array $options
	 * @return string
	 */
	public function getPostTitle($post = array(), $options = array()) {
		$_options	 = array(
			'link' => true,
		);
		$options	 = Hash::merge($_options, $options);
		$url		 = '';
		$this->Blog->setContent($post['BlogPost']['blog_content_id']);

		if ($options['link']) {
			if (isset($post['OptionalLink']) && $post['OptionalLink']['status'] >= 1) {

				switch ($post['OptionalLink']['status']) {
					case '1':
						$url = $this->getPostUrl($post);
						if ($post['OptionalLink']['blank']) {
							$options['target'] = '_blank';
							$options['rel'] = 'noopener noreferrer';
						}
						if ($post['OptionalLink']['nolink']) {
							return $post['BlogPost']['name'];
						}
						break;

					case '2':
						// サムネイル側へのリンクになるため、imgsize => large を指定する
						$fileLink	 = $this->BcUpload->uploadImage('OptionalLink.file', $post['OptionalLink']['file'], array('imgsize' => 'large'));
						$result		 = preg_match('/.+<?\shref=[\'|"](.*?)[\'|"].*/', $fileLink, $match);
						if ($result) {
							$post['OptionalLink']['name'] = $match[1];
							$url = $post['OptionalLink']['name'];
							$options['target'] = '_blank';
							$options['rel'] = 'noopener noreferrer';
						}
						break;

					default:
						break;
				}
			} else {
				$url = $this->getPostUrl($post);
			}
			unset($options['link']);
			return $this->BcBaser->getLink($post['BlogPost']['name'], $url, $options);
		} else {
			return $post['BlogPost']['name'];
		}
	}

	/**
	 * ファイルが保存されているURLを取得する
	 *
	 * @param string $fileName
	 * @return string
	 */
	public function getFileUrl($fileName) {
		if ($fileName) {
			return $this->savedUrl . $fileName;
		} else {
			return '';
		}
	}

	/**
	 * ファイルリンクタグを出力する
	 *
	 * @param array $uploaderFile
	 * @param array $options
	 * @return string リンクタグ
	 */
	public function file($uploaderFile, $options = array()) {
		$_options	 = array(
			'alt'	 => $uploaderFile['file'],
			'target' => '_blank',
			'rel' => 'noopener noreferrer',
		);
		$options	 = Hash::merge($_options, $options);

		if (isset($uploaderFile['OptionalLink'])) {
			$uploaderFile = $uploaderFile['OptionalLink'];
		}

		if (!empty($uploaderFile['file'])) {
			$imgUrl = $this->getFileUrl($uploaderFile['file']);
			//$pathInfo = pathinfo($uploaderFile['file']);
			if (!empty($uploaderFile['publish_begin']) || !empty($uploaderFile['publish_end'])) {
				$savePath = $this->savePath . 'limited' . DS . $uploaderFile['file'];
			} else {
				$savePath = $this->savePath . $uploaderFile['file'];
			}
			if (file_exists($savePath)) {
				$out = $this->BcBaser->getLink('≫ファイル', $imgUrl, $options);
				return $out;
			}
		}
		return '';
	}

	/**
	 * $postからURLを返す
	 *
	 * @param array $post
	 * @return string リンクURL
	 */
	public function getPostUrl($post) {
		$url	 = $this->Blog->getPostLinkUrl($post);
		$target	 = '';
		if (Hash::get($post, 'OptionalLink.status')) {
			if ($post['OptionalLink']['status'] == "1") {
				if ($post['OptionalLink']['nolink']) { // リンクなしの場合
					$url = null;
				} elseif ($post['OptionalLink']['name']) { // URLの場合
					$url = $post['OptionalLink']['name'];
				}
			} elseif ($post['OptionalLink']['status'] == "2") { // ファイルの場合
				if ((!$post['OptionalLink']['publish_begin'] || strtotime($post['OptionalLink']['publish_begin']) < time()) &&
					(!$post['OptionalLink']['publish_end'] || strtotime($post['OptionalLink']['publish_end']) > time())
				) {
					$url	 = $this->BcBaser->getUri("/files/optionallink/" . $post['OptionalLink']['file']);
				} else {
					$url = null;
				}
			}
		}

		return $url;
	}

	/**
	 * $postからリンクターゲットの文字列を返す
	 *
	 * @param array $post
	 * @return string ターゲット(target="_blank")
	 */
	public function getPostTarget($post) {
		$url	 = $this->Blog->getPostLinkUrl($post);
		$target	 = '';
		if (Hash::get($post, 'OptionalLink.status')) {
			if ($post['OptionalLink']['status'] == "1") {
				if ($post['OptionalLink']['nolink']) {
					$url = null;
				} else {
					if ($post['OptionalLink']['blank'])
						$target	 = 'target="_blank" rel="noopener noreferrer"';
					if ($post['OptionalLink']['name'])
						$url	 = $post['OptionalLink']['name'];
				}
			} elseif ($post['OptionalLink']['status'] == "2") {
				if ((!$post['OptionalLink']['publish_begin'] || strtotime($post['OptionalLink']['publish_begin']) < time()) &&
					(!$post['OptionalLink']['publish_end'] || strtotime($post['OptionalLink']['publish_end']) > time())
				) {
					$target	 = 'target="_blank" rel="noopener noreferrer"';
					$url	 = $post['OptionalLink']['file'] ? $this->BcBaser->getUri("/files/optionallink/" . $post['OptionalLink']['file']) : null;
				} else {
					$url = null;
				}
			}
		}

		return $target;
	}

	/**
	 * 記事URLが有効であることを判定する
	 *
	 * @param array $post
	 * @return boolean
	 */
	public function isEnabledLink($post) {
		// 記事がオプショナルリンクを持たないのでブログ記事の通常リンク
		if (!isset($post['OptionalLink'])) {
			return true;
		}
		// オプショナルリンクが無効なのでブログ記事の通常リンク
		if (!$post['OptionalLink']['status']) {
			return true;
		}
		// リンクなし指定時
		if ($post['OptionalLink']['status'] == '1') {
			if (Hash::get($post, 'OptionalLink.nolink')) {
				return false;
			}
		}
		// ファイル指定時
		if ($post['OptionalLink']['status'] == '2') {
			if (Hash::get($post, 'OptionalLink.file')) {
				if (!$this->allowPublishFile($post)) {
					return false;
				}
			}
		}
		return true;
	}

	/**
	 * 前の記事へのリンクがあるかチェックする
	 *
	 * @param array $post ブログ記事
	 * @param bool $skip link無しの時、一つ飛ばしにするかどうか default => true
	 * @return bool
	 */
	public function hasPrevLink($post, $skip = true) {
		$prevPost = $this->Blog->getPrevPost($post);
		if ($prevPost) {
			if ($this->getPostUrl($prevPost) == null) {
				$prevPost = $skip ? $this->getPrevPost($prevPost, $skip) : false;
				return $prevPost ? true : false;
			}
			return true;
		}
		return false;
	}

	/**
	 * 前の記事へのリンクを出力する
	 *
	 * @param array $post ブログ記事
	 * @param string $title タイトル
	 * @param array $htmlAttributes HTML属性
	 *	※ HTML属性は、HtmlHelper::link() 参照
	 * @param bool $skip link無しの時、一つ飛ばしにするかどうか default => true
	 * @return void
	 */
	public function prevLink($post, $title = '', $htmlAttributes = [], $skip = true) {
		$prevPost = $this->getPrevPost($post, $skip);
		$_htmlAttributes = ['class' => 'prev-link', 'arrow' => '≪ '];
		$htmlAttributes = am($_htmlAttributes, $htmlAttributes);
		$arrow = $htmlAttributes['arrow'];
		unset($htmlAttributes['arrow']);
		if ($prevPost) {
			if (!$title) {
				$title = $arrow . $prevPost['BlogPost']['name'];
			}
			echo $this->Blog->getPostLink($prevPost, $title, $htmlAttributes);
		}
	}

	/**
	 * 前の記事を取得する
	 *
	 * @param array $post ブログ記事
	 * @param bool $skip link無しの時、一つ飛ばしにするかどうか default => true
	 * @return array
	 */
	public function getPrevPost($post, $skip = true) {
		$prevPost = $this->Blog->getPrevPost($post);
		if ($prevPost) {
			if ($this->getPostUrl($prevPost) == null) {
				$prevPost = $skip ? $this->getPrevPost($prevPost, $skip) : $prevPost;
			}
			return $prevPost;
		}
		return false;
}

	/**
	 * 次の記事へのリンクが存在するかチェックする
	 *
	 * @param array $post ブログ記事
	 * @param bool $skip link無しの時、一つ飛ばしにするかどうか default => true
	 * @return bool
	 */
	public function hasNextLink($post, $skip = true) {
		$nextPost = $this->Blog->getNextPost($post);
		if ($nextPost) {
			if ($this->getPostUrl($nextPost) == null) {
				$nextPost = $skip ? $this->getNextPost($nextPost, $skip) : false;
				return $nextPost ? true : false;
			}
			return true;
		}
		return false;
	}

	/**
	 * 次の記事へのリンクを出力する
	 *
	 * @param array $post ブログ記事
	 * @param string $title タイトル
	 * @param array $htmlAttributes HTML属性
	 *	※ HTML属性は、HtmlHelper::link() 参照
	 * @param bool $skip link無しの時、一つ飛ばしにするかどうか default => true
	 * @return void
	 */
	public function nextLink($post, $title = '', $htmlAttributes = [], $skip = true) {
		$nextPost = $this->getNextPost($post, $skip);
		$_htmlAttributes = ['class' => 'next-link', 'arrow' => ' ≫'];
		$htmlAttributes = am($_htmlAttributes, $htmlAttributes);
		$arrow = $htmlAttributes['arrow'];
		unset($htmlAttributes['arrow']);
		if ($nextPost) {
			if (!$title) {
				$title = $nextPost['BlogPost']['name'] . $arrow;
			}
			echo $this->Blog->getPostLink($nextPost, $title, $htmlAttributes);
		}
	}

	/**
	 * 次の記事を取得する
	 *
	 * @param array $post ブログ記事
	 * @param bool $skip link無しの時、一つ飛ばしにするかどうか default => true
	 * @return array
	 */
	public function getNextPost($post, $skip = true) {
		$nextPost = $this->Blog->getNextPost($post);
		if ($nextPost) {
			if ($this->getPostUrl($nextPost) == null) {
				$nextPost = $skip ? $this->getNextPost($nextPost, $skip) : $nextPost;
			}
			return $nextPost;
		}
		return false;
	}

}
