<?php
/**
 * テーマヘルパー
 *
 */
class ThemeHelper extends AppHelper {
	/**
	 * ヘルパー
	 *
	 * @var array
	 */
	public $helpers = array(
		'BcHtml',
		'BcBaser',
		'Blog',
		'CuCustomField.CuCustomField',
		'InstantPage.InstantPage',
	);

	/**
	 * og:description 用の説明文を取得する
	 *
	 * @return string og:description 用の説明文
	 */
	public function getOgpDescription() {
		$description = '';
		// サイト基本設定の「サイト基本説明文」を使用する
		// コンテンツの説明文が記載されていたらそちらを優先で使用する
		if(!empty($this->request->params['Content']['description'])) {
			$description = $this->request->params['Content']['description'];
		}
		// ブログ詳細の際、概要が存在する場合は概要を利用
		if($this->BcBaser->isBlogSingle()) {
			$overView = $this->getBlogPostOverview();
			if(!empty($overView)) {
				$description = $overView;
			}
		}
		if(empty($description)) {
			$siteConfig = ClassRegistry::init('SiteConfig');
			$siteConfigData = $siteConfig->find('first', array(
				'conditions' => array('SiteConfig.name' => 'description')
			));
			$description = $siteConfigData['SiteConfig']['value'];
		}
		return h(preg_replace('[\n|\r|\r\n|\t]', '', strip_tags($description)));
	}

	/**
	 * meta タグ用のページ説明文を取得する
	 *
	 * @return string meta タグ用の説明文
	 */
	public function getMetaDescription() {
		$description = '';
		if ($this->BcBaser->isHome()) {
			// トップページの時
			// サイト基本設定の「サイト基本説明文」を使用する。
			// トップページの説明文が記載されていたらそちらを優先で使用する。
			$siteConfig = ClassRegistry::init('SiteConfig');
			$siteConfigData = $siteConfig->find('first', array(
				'conditions' => array('SiteConfig.name' => 'description')
			));
			$description = $siteConfigData['SiteConfig']['value'];
			if (!empty($this->request->params['Content']['description'])) {
				$description = $this->request->params['Content']['description'];
			}
		} else {
			// トップページ以外の時
			// 基本的に空の状態とし、説明文が記載されていたらそちらを優先で使用する。
			if(!empty($this->request->params['Content']['description'])) {
				$description = $this->request->params['Content']['description'];
			}
			// ブログ詳細の際、概要が存在する場合は概要を利用
			if($this->BcBaser->isBlogSingle()) {
				$overView = $this->getBlogPostOverview();
				if(!empty($overView)) {
					$description = $overView;
				}
			}
		}
		return h(preg_replace('[\n|\r|\r\n|\t]', '', strip_tags($description)));
	}

	/*
	 * ブログ記事詳細にて概要を取得する
	 */
	public function getBlogPostOverview() {
		if($this->BcBaser->isBlogSingle()) {
			$entity_id = (int) $this->request->params['Content']['entity_id'];
			// ブログIDと記事NOより、記事データを取得
			$posts = $this->BcBaser->getBlogPosts($entity_id, 1, array('no' => $this->request->params['pass'][0]));
			if(!empty($posts[0]['BlogPost']['content'])) {
				return strip_tags($posts[0]['BlogPost']['content']);
			}
		}
		return '';
	}

	/**
	 * ブログカテゴリ、記事データを取得する
	 *
	 * @param int $blogContentId
	 * @return array
	 */
	public function getBlogCategories($blogContentId, $recursive = -1) {
		if (ClassRegistry::isKeySet('Blog.BlogCategory')) {
			$BlogCategoryModel = ClassRegistry::getObject('Blog.BlogCategory');
		} else {
			$BlogCategoryModel = ClassRegistry::init('Blog.BlogCategory');
		}
		$data = $BlogCategoryModel->find('all', array(
			'conditions' => array(
				'BlogCategory.blog_content_id' => $blogContentId,
			),
			'recursive' => $recursive,
			'order' => array(
				'BlogCategory.lft' => 'ASC',
			),
		));
		return $data;
	}

	/**
	* プラグインの存在確認
	*
	* @param string $name plugin name
	* @return string
	*/
	public function inPlugin($name) {
		$enablePlugins = Configure::read('BcStatus.enablePlugins');
		return in_array($name, $enablePlugins);
	}

	/**
	* カテゴリIDから、親カテゴリを取得する
	*
	* @param int categoryId
	* @return $parentCategory 親カテゴリ
	*/
	public function getParentCategory($categoryId) {
		$BlogCategory = ClassRegistry::init('Blog.BlogCategory');
		return $BlogCategory->getParentNode($categoryId);
	}
	/**
	* サイトIDからコンテンツを取得する。
	*
	* @param int id = site_id
	* @return $content
	*/
	public function getSiteContent($id = null) {
		if (!$id) {
			$id = $this->request->params['Site']['id'];
		}
		if (!$id) {
			return false;
		}
		if (ClassRegistry::isKeySet('Content')) {
			$ContentModel = ClassRegistry::getObject('Content');
		} else {
			$ContentModel = ClassRegistry::init('Content');
		}
		$siteRoot = $ContentModel->getSiteRoot($id);
		if ($siteRoot && !empty($siteRoot['Content'])) {
			return $siteRoot['Content'];
		}
		return [];
	}

	/**
	* 一覧表示時にカスタムフィールドの画像があれば、そのURLを取得し、なければeyecatchのURLを返す
	*
	* @param array post
	* @return $content
	*/
	public function getCfEycatchUrl($post, $name = 'mv', $imgsize = 'thumb', $noimage = '/img/admin/noimage.png') {
		$eyeCatch = $this->Blog->getEyeCatch($post,['imgsize' => $imgsize, 'output' => 'url', 'noimage' => $noimage]);
		$cf = !empty($post['CuCustomFieldValue']) ? $post['CuCustomFieldValue'] : [];
		if (!empty($cf[$name]) && $cf[$name]) {
			$mv = $this->CuCustomField->get($post, $name, ['output' => 'url']);
			// CuCustomFieldのバグ fileのパスが間違っている問題に対応
			if (strpos($mv, '/files/cu_custom_field/blog/') === false) {
				$mv = str_replace('/files', '/files/cu_custom_field/blog/'. $post['BlogPost']['blog_content_id']. '/blog_posts', $mv);
			}
		} else {
			$mv = $eyeCatch;
		}
		return $mv;
	}
	/**
	* サイトデータを返す
	*
	* @param array post
	* @return $content
	*/
	public function getSiteData($id = null) {
		if (ClassRegistry::isKeySet('Site')) {
			$SiteModel = ClassRegistry::getObject('Site');
		} else {
			$SiteModel = ClassRegistry::init('Site');
		}
		if ($id) {
			return $SiteModel->findById($id);
		} else {
			return $SiteModel->find('first');
		}

	}


	/**
	* InstantPageUserを取得する
	*
	* @param array post
	* @return $content
	*/
	public function getInstantPageUser($id = null) {
		return $this->InstantPage->getInstantPageUser($id);
	}

	/**
	* ページ作成者を取得
	*
	* @param array post
	* @return $content
	*/
	public function getUser($id = null) {
		return $this->InstantPage->getInstantPageUser($id);
	}

}
