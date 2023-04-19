<?php
/**
 * BurgerEditor <baserCMS plugin>
 *
 * @copyright		Copyright 2013 -, D-ZERO Co.,LTD.
 * @link			https://www.d-zero.co.jp/
 * @package			burger_editor
 * @since			Baser v 3.0.0
 * @license			https://market.basercms.net/files/baser_market_license.pdf
 */
class BurgerEditorHelper extends AppHelper {

	public static $configJSON = '';	// bgeconfig.jsonのパス
	public static $addonDir = array();	// Addonフォルダパス
	public static $imageFileBaseDir = '';	// 画像フォルダパス
	public static $imageFileBaseURL = '';	// 画像フォルダURL
	public static $imageFileList = array();	// 画像ファイル一覧
	public static $otherFileBaseDir = '';	// 画像フォルダパス
	public static $otherFileBaseURL = '';	// 画像フォルダURL
	public static $otherFileList = array();	// ファイル一覧
	public static $staticPanelDir = '';		// 静的ブロックパネルフォルダパス

	public static $imageFileMaxId = 0;	// 画像ファイル最大ID
	public static $otherFileMaxId = 0;	// その他ファイル最大ID

	public static $bgeConfig = array();	// ブロッククラス設定オプション

	public static $useType = array();
	public static $useBlock = array();
/**
 * ヘルパー
 *
 * @var array
 * @access public
 */
	public $helpers = array('Html', 'BcTime', 'BcBaser', 'BcForm', 'BcUpload', 'BurgerEditor.BurgerEditor');

/**
 * コンストラクタ
 *
 * @return void
 * @access public
 */
	public function __construct(View $View, $settings = array()) {
		parent::__construct($View, $settings);
		if (BcUtil::isAdminSystem()) {
			self::setSelfValue();
		}
	}

	public static function setSelfValue(){

		self::$configJSON = dirname(dirname(dirname(__FILE__))).DS.'bgeconfig.json';
		self::$addonDir = BurgerEditorUtil::getAddonPath();
		self::$imageFileBaseDir = realpath(WWW_ROOT) . DS . 'files' . DS . 'bgeditor' . DS . 'img' .DS;
		self::$otherFileBaseDir = realpath(WWW_ROOT) . DS . 'files' . DS . 'bgeditor' . DS . 'other' .DS;

		// webrootのindex.phpを別名に変えた場合、BC_BASE_URLに、変更したファイル名が入って来てしまうため変換している
		// スマートURLが有効かどうかによってBC_BASE_URLの値が変化するため判別
		$baseUrl = str_replace('//', '/', dirname(BC_BASE_URL) . '/');
		if (Configure::read('App.baseUrl') == '') {
			$baseUrl = Router::url('/');
		}
		self::$imageFileBaseURL = $baseUrl . 'files/bgeditor/img/';
		self::$otherFileBaseURL = $baseUrl . 'files/bgeditor/other/';

		// 静的ファイル設置ディレクトリ
		$staticDirName = Inflector::underscore(preg_replace('/Helper$/', '', __CLASS__));
		self::$staticPanelDir = WWW_ROOT . $staticDirName . DS . $staticDirName . DS . 'panel' . DS;

		// フォルダがない場合はinit処理を実行する
		if (!file_exists(self::$imageFileBaseDir) || !file_exists(self::$otherFileBaseDir)) {
			include dirname(dirname(dirname(__FILE__))).DS.'Config'.DS.'init.php';
		}

		// 設定値により、ユーザ別にファイル場所を設置
		if (!Configure::read("Bge.fileShare")) {
			$user = BcUtil::loginUser();
			$userId = $user['id'];
			self::$imageFileBaseDir .=  $userId . DS;
			self::$otherFileBaseDir .= $userId . DS;
			self::$imageFileBaseURL .= $userId .'/';
			self::$otherFileBaseURL .= $userId .'/';
			if (!file_exists(self::$imageFileBaseDir)) {
				mkdir (self::$imageFileBaseDir);
				chmod(self::$imageFileBaseDir, 0777);
			}
			if (!file_exists(self::$otherFileBaseDir)) {
				mkdir (self::$otherFileBaseDir);
				chmod(self::$otherFileBaseDir, 0777);
			}
		}

		// ブロックclass設定ファイル取得
		if(file_exists(self::$addonDir[0]."block".DS.'option.php')) {
			include self::$addonDir[0]."block".DS.'option.php';
			self::$bgeConfig['blockClassOption'] = $bgBlockConfig;
		}

		// bgeconfig.jsonの読み込み
		if (file_exists(self::$configJSON)) {
			$configJSONString = file_get_contents(self::$configJSON);
			$configJSONData = json_decode($configJSONString, TRUE);
			if (!empty(self::$bgeConfig['blockClassOption'])) {
				self::$bgeConfig['blockClassOption'] = Hash::merge(self::$bgeConfig['blockClassOption'], $configJSONData["bg-block-config"]);
			}
			self::$bgeConfig['ckeditorConfig'] = $configJSONData["ckeditor-config"];
			if (!empty($configJSONData["flag"])) {
				self::$bgeConfig['flag'] = $configJSONData["flag"];
			}
		}

		self::$bgeConfig['api'] = array(
			"imgList" => Router::url(array('admin' => true, 'plugin' => 'burger_editor', 'controller' => 'burger_editor', 'action' => 'img_list')),
			"imgUpload" => Router::url(array('admin' => true, 'plugin' => 'burger_editor', 'controller' => 'burger_editor', 'action' => 'img_upload')),
			"imgDelete" => Router::url(array('admin' => true, 'plugin' => 'burger_editor', 'controller' => 'burger_editor', 'action' => 'img_delete')),
			"fileList" => Router::url(array('admin' => true, 'plugin' => 'burger_editor', 'controller' => 'burger_editor', 'action' => 'file_list')),
			"fileUpload" => Router::url(array('admin' => true, 'plugin' => 'burger_editor', 'controller' => 'burger_editor', 'action' => 'file_upload')),
			"fileDelete" => Router::url(array('admin' => true, 'plugin' => 'burger_editor', 'controller' => 'burger_editor', 'action' => 'file_delete')),
			// "getFilename" => Router::url(array('admin' => true, 'plugin' => 'burger_editor', 'controller' => 'burger_editor', 'action' => 'get_filename')), // 未使用
		);

		self::$bgeConfig['utility'] = array(
			"googleMapsApiKey" => BurgerEditorUtil::getGoogleMapApiKey(),
			"cssList" => self::getCSSList(),
		);

		self::$bgeConfig['cmsVersion'] = self::getVersionOfSystem();
		self::$bgeConfig['types'] = self::typeVersionList();

	}

	public static function getImageList() {
		$dir = new Folder(self::$imageFileBaseDir);
		$tmpList = array();
		$files = $dir->find();
		foreach($files as $file) {
			if ($file == ".DS_Store") continue;
			if (preg_match('/(__midium|__small|__org)\.[a-z0-9]+$/i', $file)) {
				continue;
			}

			$path = $dir->pwd();
			if (substr($path, -1) != DS) {
				$path = $path . DS;
			}
			$fileKey = filemtime($path . $file);
			if (preg_match('/^(\d+)__/', $file, $matches) && isset($matches[1])) {
				$fileKey = intval($matches[1]) * 100000 + 2000000000;
			}
			while(1) {
				if (!isset($tmpList[$fileKey])) break;
				$fileKey++;
			}
			$tmpList[$fileKey] = $path . $file;

			// ファイルID取得
			$fileId = self::getFileId($file);
			if (self::$imageFileMaxId < $fileId) self::$imageFileMaxId = $fileId;
		}
		krsort($tmpList);
		self::$imageFileList = array_values($tmpList);
		return self::$imageFileList;
	}

	public static function getFileList() {
		$dir = new Folder(self::$otherFileBaseDir);
		$tmpList = array();
		$files = $dir->find();
		foreach($files as $file) {
			$path = $dir->pwd();
			if (substr($path, -1) != DS) {
				$path = $path . DS;
			}
			$fileKey = filemtime($path . $file);
			while(1) {
				if (!isset($tmpList[$fileKey])) break;
				$fileKey++;
			}
			$tmpList[$fileKey] = $path . $file;

			// ファイルID取得
			$fileId = self::getFileId($file);
			if (self::$otherFileMaxId < $fileId) self::$otherFileMaxId = $fileId;
		}
		krsort($tmpList);
		self::$otherFileList = array_values($tmpList);
		return self::$otherFileList;
	}

	/**
	 *
	 */
	public static function getCSSList () {
		$cssList = array();
		$req = new CakeRequest(null, false);
		$webroot = $req->webroot;
		if ($webroot != '/') {
			$webroot = Configure::read('BcEnv.siteUrl');
		}

		// テーマCSSフォルダ内のckeditor.cssを読む このCSSの中にcommon.cssなど必要なCSSをインポートする
		if (file_exists(WWW_ROOT . 'css' . DS . 'ckeditor.css')) {
			$cssList[] = $webroot. 'css/ckeditor.css';
		}

		$version = self::getMajorVersionOfSystem ();
		if ($version < 4) {
			// baserCMS3系までの対応
			$theme = Configure::read('BcSite.theme');
		} else {
			// baserCMS4系対応
			// コンテンツが所属しているテーマを判定
			$theme = self::getThemeByContent();
		}
		// テーマが優先
		if (file_exists(WWW_ROOT . 'theme' . DS . $theme . DS . 'css' . DS . 'bge_style.css')) {
			$path = WWW_ROOT . 'theme' . DS . $theme . DS . 'css' . DS . 'bge_style.css';
			$cssList[] = $webroot. 'theme/' . $theme . '/css/bge_style.css' . BurgerEditorUtil::getSuffix($path);
		// テーマになくてwebroot/cssにあれば
		} elseif (file_exists(WWW_ROOT . 'css' . DS . 'bge_style.css')) {
			$path = WWW_ROOT . 'css' . DS . 'bge_style.css';
			$cssList[] = $webroot . 'css/bge_style.css' . BurgerEditorUtil::getSuffix($path);
		// themeになく、webroot/cssにもない場合、プラグイン標準のファイルを読み込む
		} else {
			$path = WWW_ROOT . 'app' . DS . 'Plugin' . DS . 'BurgerEditor' . DS . 'webroot' . DS . 'css' . DS . 'bge_style.css';
			$cssList[] = '/burger_editor/css/bge_style.css' . BurgerEditorUtil::getSuffix($path);
		}
		return $cssList;
	}

	/**
	 * baserCMSのバージョンを取得する
	 *
	 * /lib/Baser/VERSION.txt に記述されているバージョン
	 *
	 * @return string version
	 */
	public static function getVersionOfSystem () {
		// /lib/Baser/VERSION.txt までのパスを取得
		$path = BASER . 'VERSION.txt';

		App::uses('File', 'Utility');
		$versionFile = new File($path);
		$versionData = $versionFile->read();
		$aryVersionData = explode("\n", $versionData);
		if (!empty($aryVersionData[0])) {
			// 例: 3.0.11-dev
			return trim($aryVersionData[0]);
		} else {
			return null;
		}
	}

	/**
	 * baserCMSのメジャーバージョンを整数で取得する
	 *
	 * @return int major version
	 */
	public static function getMajorVersionOfSystem () {
		$majorVersion = self::getVersionOfSystem();
		return intval($majorVersion);
	}

	/**
	 *  タイプの読み込み
	 *
	 * @param String $typeName タイプ名
	 */
	public static function type($typeName){

		// バージョン設定
		$version = "0.0.0";
		$typePath = BurgerEditorUtil::getTypePath($typeName);
		if(!$typePath) {
			return false;
		}

		if (file_exists($typePath . 'version.php')) {
			// バージョンを設定しているファイルを読み込んで
			// $version変数を上書きする
			include $typePath . 'version.php';
		}

		echo '<div data-bgt="'.h($typeName).'" data-bgt-ver="' . h($version) . '" class="bgt-container bgt-'.h($typeName).'-container">';
		include $typePath . 'value.php';
		echo '</div>';
		if (!in_array($typeName, self::$useType)) self::$useType[] = $typeName;
	}

	/**
	 *  タイプのリスト
	 *
	 * @param String $typeName タイプ名
	 */
	public static function typeVersionList() {

		$path = self::$addonDir;
		foreach($path as $addonDir) {
			if(!is_dir($addonDir . 'type')) {
				continue;
			}
			$blockList = array();
			if ($dh = opendir($addonDir . 'type' . DS)) {
				while(($typeName = readdir($dh)) !== false) {
					if ($typeName == '.' || $typeName == '..' || !is_dir($addonDir . 'type' . DS . $typeName)) continue;
					$version = "0.0.0";
					$tmpl = '';
					$blockList[$typeName] = array();

					if (file_exists($addonDir . 'type' . DS . $typeName . DS . 'version.php')) {
						// バージョンを設定しているファイルを読み込んで $version 変数を上書きする
						include $addonDir . 'type' . DS . $typeName . DS . 'version.php';
					}
					$blockList[$typeName]['version'] = $version;

					if (file_exists($addonDir . 'type' . DS . $typeName . DS . 'value.php')) {
						ob_start();
						self::type($typeName);
						$tmpl = ob_get_contents();
						ob_end_clean();
					}
					$blockList[$typeName]['tmpl'] = $tmpl;
				}
				closedir($dh);
			}
		}
		return $blockList;
	}

	/**
	 *  ブロックの読み込み
	 *
	 * @param String $typeName タイプ名
	 */
	public function defaultBlock($blockPathList){
		foreach($blockPathList as $block){
			$blockName = basename($block);
			if (!in_array($blockName, self::$useBlock)) self::$useBlock[] = $blockName;
			echo '<div data-bgb="'.h($blockName).'" class="bgb-'.h($blockName).'">';
			include $block.'index.php';
			echo '</div>'."\n\n";

			// ブロックのパネル画像の静的ファイルを生成していて、オリジナルより古い場合は削除する
			if (file_exists(self::$staticPanelDir . $blockName . '.svg')){
				if (filemtime(self::$staticPanelDir . $blockName . '.svg') <
					filemtime($block.'panel.svg')
					) {
					unlink(self::$staticPanelDir . $blockName . '.svg');
				}
			}
			elseif (file_exists(self::$staticPanelDir . $blockName . '.png')){
				if (filemtime(self::$staticPanelDir . $blockName . '.png') <
					filemtime($block.'panel.png')
					) {
					unlink(self::$staticPanelDir . $blockName . '.png');
				}
			}
		}
	}

	public function inputArea(){
		if (!self::$useType) trigger_error ("ブロックの読み込みが完了していません。");
		foreach(self::$useType as $type){
			echo '<div class="Type'.h($type).'">';
			include BurgerEditorUtil::getTypePath($type) . 'input.php';
			echo '</div>'."\n\n";
		}
	}

	public function panelArea(){
		if (!self::$useType) trigger_error ("ブロックの読み込みが完了していません。");

		$addonDir = self::$addonDir;
		$bgCategory = array();
		$bgCategoryTmp = array();
		foreach($addonDir as $path) {
			// ブロックカテゴリファイルの取得（bgeconfig.jsonがあった場合は上書きマージする）
			$categoryPath = $path . "block" . DS . 'category.php';
			if(file_exists($categoryPath)) {
				include $categoryPath;
				$bgCategoryTmp = Hash::merge($bgCategoryTmp, $bgCategory);
			}
		}
		$bgCategory = $bgCategoryTmp;

		if (file_exists(self::$configJSON)) {
			$configJSONString = file_get_contents(self::$configJSON);
			$configJSONData = json_decode($configJSONString, TRUE);
			$bgCategory = Hash::merge($bgCategory, $configJSONData["bg-category"]);
		}

		// start output
		echo '<div class="bg-block-selection">';
		echo '<div class="bg-blocks">';
		echo '<dl>';

		foreach ($bgCategory as $categoryName => $blockList) {
			echo '<dt>'.h($categoryName).'</dt>';
			echo '<dd>';
			echo '<ul>'."\n";
			foreach($blockList as $blockName => $block){
				if ($block === null) {
					continue;
				}
				if (in_array($blockName, self::$useBlock)) {
					echo '<li data-bge-block="'.h($blockName).'">';
						// svg優先でロード
						$blockPath = BurgerEditorUtil::getBlockPath($blockName);
						if (file_exists($blockPath.'panel.svg')) {
							$imgSrc = file_get_contents($blockPath.'panel.svg');
							echo '<figure>';
							echo '<div>' . $imgSrc . '</div>';
							echo '<figcaption>'.$block.'</figcaption>';
							echo '</figure>'."\n";
						} elseif (file_exists($blockPath.'panel.png')){
							$imgSrc = $this->assetUrl(array('admin' => false, 'controller'=>'burger_editor', 'action'=> 'panel', $blockName . '.png'));
							echo '<figure>';
							echo '<div style="background-image: url('.$imgSrc.');" role="image"></div>';
							echo '<figcaption>'.$block.'</figcaption>';
							echo '</figure>'."\n";
						} else {
							echo h($block) . '（画像無し）';
						}
					echo '</li>'."\n";
				}
			}
			echo '</ul>';
			echo '</dd>';
		}

		echo '</dl>';
		echo '</div>';
		echo '</div>';

	}

	/**
	 * 初期処理ファイル読み込み
	 */
	public function initArea(){
		if (!self::$useType) trigger_error ("ブロックの読み込みが完了していません。");

		foreach(self::$useType as $type){
			$typeInitPath = BurgerEditorUtil::getTypePath($type) . 'init.php';
			if (file_exists($typeInitPath)){
				echo '<div class="Init'.h($type).'">';
				include $typeInitPath;
				echo '</div>'."\n\n";
			}
		}

	}


	/**
	 * BurgerEditor 出力
	 */
	public function editor($fieldName, $options = array()){
		$fieldAry = explode('.', $fieldName);
		$model  = isset($fieldAry[0]) ? $fieldAry[0] : '';
		$column = isset($fieldAry[1]) ? $fieldAry[1] : '';

		$inputId = Inflector::camelize(implode('_', $fieldAry));
		$draftId = Inflector::camelize(implode('_', array($model, $options['editorDraftField'])));

		$bcFormHelper = new BcFormHelper($this->_View);
		echo $bcFormHelper->hidden($fieldName);

		// データ領域はここで出力
		$editorHtml = '';

		$editorHtml .= '<div id="ValueMigrationMessage"></div>';

		// 下書き機能を利用するかチェック
		if (!empty($options['editorDraftField'])) {

			$draftVal = (empty($this->_View->data[$model][$options['editorDraftField']])) ? '' : $this->_View->data[$model][$options['editorDraftField']];

			if ($draftId) {

				$editorHtml .= '<div class="draft-btn clearfix"' . ((empty($options['editorUseDraft'])) ? 'style="display:none"' : ''). '>';
					$editorHtml .= '<div class="draft-tab-btn">';
						$editorHtml .= '<a id="CbeHonkouBtn" class="on">本稿モード</a>';
						$editorHtml .= '<a id="CbeSoukouBtn">下書きモード</a>';
					$editorHtml .= '</div>';
					$editorHtml .= '<div class="draft-copy-btn">';
						$editorHtml .= '<a id="CbeHonkouCopyBtn">本稿を下書きにコピー</a>';
						$editorHtml .= '<a id="CbeSoukouCopyBtn">下書きを本稿にコピー</a>';
					$editorHtml .= '</div>';
				$editorHtml .= '</div>';

				echo $bcFormHelper->hidden($model. '.' .$options['editorDraftField']);

				$editorHtml .= '<div id="DraftArea" class="bge-view-value bge_content bge-contents" hidden></div>';

			}
		}

		self::$bgeConfig['utility']['mainFieldId'] = $inputId;
		self::$bgeConfig['utility']['draftFieldId'] = $draftId;
		self::$bgeConfig['setting'] = Configure::read('Bge');

		$editorHtml .= '<script id="bge-config" type="application/json">';
		$editorHtml .= json_encode(self::$bgeConfig);
		$editorHtml .= '</script>';

		$editorHtml .= '<div id="ValueArea" class="bge-view-value bge_content bge-contents"></div>';

		$is_page = ($this->request->params['controller'] == 'pages') ? true : false;

		$editorHtml .= $this->requestAction('/admin/burger_editor/burger_editor/editor',
			array(
				'return',
				'layout' => false,
				'is_page' => $is_page
			)
		);
		// load読み込み
		foreach(self::$useType as $type){
			$typeLoadPath = BurgerEditorUtil::getTypePath($type) . 'load.php';
			if (file_exists($typeLoadPath)){
				include $typeLoadPath;
			}
		}

		$this->BcBaser->css(array(
			'admin/ckeditor/editor',
			'BurgerEditor.admin/burger_editor'
		), array('inline' => false));

		$version = self::getMajorVersionOfSystem();
		if ($version < 4) {
			$ckeditorPath = 'admin/ckeditor/ckeditor';
			$ckeditorJQueryAdaptersPath = 'admin/ckeditor/adapters/jquery';
		} else {
			$ckeditorPath = 'admin/vendors/ckeditor/ckeditor';
			$ckeditorJQueryAdaptersPath = 'admin/vendors/ckeditor/adapters/jquery';
		}

		$this->BcBaser->js(array(
			$ckeditorPath,
			$ckeditorJQueryAdaptersPath,
			'BurgerEditor.admin/burger_editor',
		), array('inline' => false));

		// ユーザ(サイト制作者)定義CSSの自動読込
		$cssList = array();
		$cssList[] = "BurgerEditor.bge_style_default";
		$cssList[] = self::getCSSList();
		$this->BcBaser->css($cssList, array('inline' => false));
		$editorStartTitle = '<h2 style="text-align:left;">コンテンツ編集エリア</h2>';
		echo $editorStartTitle;
		echo $editorHtml;
		// error出力はエディタ側にて関与しない
		// echo $bcFormHelper->error($fieldName);
	}

	/**
	 * ファイル名からIDを取得する
	 *
	 * @param string $fileName ファイル名
	 * @return mixed (int|null)
	 */
	static protected function getFileId($fileName){
		preg_match("/^(\d+)__/", $fileName, $matches);
		if (isset($matches[1])) return $matches[1];
		return null;
	}


	/**
	 * 記事に埋め込まれた画像を表示する
	 *
	 * @param array $post blogpost
	 * @param array $options option
	 * @return	string	imgタグ
	 */
	public function postImage($post, $options = array()) {

		$imgUrl = $this->getPostImage($post, $options);

		if(empty($imgUrl)){
			return null;
		}

		if(isset($options['number'])){
			unset($options['number']);
		}


		echo $this->Html->image($imgUrl, $options);
	}

	/**
	 * 記事に埋め込まれた画像のパスを取得する(ブログにのみ対応)
	 *
	 * @param array $post blogpost
	 * @param array $options option
	 * @return string filepath
	 */
	public function getPostImage($post, $options = array()) {

		if(!isset($post['BlogPost']['detail']) || empty($post['BlogPost']['detail'])){
			return null;
		}

		if(isset($options['number']) && !is_int($options['number'])){
			return null;
		}


		$detail = $post['BlogPost']['detail'];
		$number = (isset($options['number']))? $options['number']:0;
		$images = array();

		preg_match_all('/<img.*src\s*=\s*[\"|\'](.*?)[\"|\'].*>/i', $detail, $images);

		if(!isset($images[1]) || count($images[1]) == 0){
			return null;
		}

		if(isset($number) && !isset($images[1][$number])){
			return null;
		}


		return $images[1][$number];

	}


	/**
	 * 記事が所属するsiteIdから、どのテーマファイルがロードされているかを取得する
	 *
	 * @return string theme
	 */
	private static function getThemeByContent(){

		$params = Router::getParams();

		if(!in_array($params['controller'], array('pages', 'blog_posts'))) return;
		if(!in_array($params['action'], array('admin_add', 'admin_edit'))) return;

		$siteId = 0;

		//固定ページの場合はsite_idを取得
		if($params['controller'] == 'pages' && empty($params['Content'])){
			$Content = ClassRegistry::init('Content');
			$entityId = $params['pass'][0];
			$conditions = array('entity_id' => $entityId, 'type' => 'Page');
			$entity = $Content->find('first', array('conditions' => $conditions, 'recursive' => -1));
			$siteId = $entity['Content']['site_id'];
		}else{
			//後々Pageの方でもCotent属性が得られるようになったらこちらの処理に自動で結合される
			$siteId = $params['Content']['site_id'];
		}

		// メインサイト
		if($siteId == 0) return Configure::read('BcSite.theme');

		// サブサイト
		$Sites = ClassRegistry::init('Sites');
		$entity = $Sites->findById($siteId, 'theme');

		if(!empty($entity['Sites']['theme'])){
			return $entity['Sites']['theme'];
		}else{
			return Configure::read('BcSite.theme');
		}
	}


}
