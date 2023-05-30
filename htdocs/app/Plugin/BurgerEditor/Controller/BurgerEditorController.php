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
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
App::uses('AppHelper', 'View/Helper');
App::uses('BurgerEditorHelper', 'BurgerEditor.View/Helper');
App::uses('Imageresizer', 'Vendor');

class BurgerEditorController extends AppController {

	public $isUse = false;
	public $uses = array('BurgerEditor.BurgerEditor');
	public $helpers = array('BurgerEditor.BurgerEditor', 'BcUpload');
	public $components = array('Cookie', 'BcAuth', 'BcAuthConfigure');

	protected $imgExts = array('gif', 'jpg', 'jpeg', 'jpe', 'jfif', 'png');	// 許可画像拡張子

	protected $imageMaxWidth = 1920; // アップロード可能な最大横幅
	protected $imageMaxHeight = 1080; // アップロード可能な最大縦幅
	protected $imageDataMaxsize = 10485760; // (1024 * 1024 * 10)アップロード可能な最大サイズ10MB

	public $imgSizeWidthMax     = 1920;	// BurgerEditorが許可する最大サイズ
	public $imgSizeWidthDefault = 1000;	//
	public $imgSizeWidthSmall   = 640;	//


	public function __construct($request = null, $response = null) {
		parent::__construct($request, $response);
		// 画像サイズ設定
		$uploadImageSize = Configure::read('Bge.uploadImageSize');
		if ($uploadImageSize) {
			$this->imgSizeWidthMax     = $uploadImageSize['imgSizeWidthMax'];
			$this->imgSizeWidthDefault = $uploadImageSize['imgSizeWidthDefault'];
			$this->imgSizeWidthSmall   = $uploadImageSize['imgSizeWidthSmall'];
		}

		//　データサイズ
		if (Configure::read('Bge.uploadImageDataSize')) {
			$this->imageDataMaxsize   = Configure::read('Bge.uploadImageDataSize');
		}
	}

	public function beforeFilter() {
		if ($this->request->action === 'panel') {
			$this->request['requested'] = 1;
		}
		parent::beforeFilter();
		/* 認証設定 */
		$this->BcAuth->allow(
			'dl',
			'smartphone_dl',
			'mobile_dl',
			'panel'
		);
		BurgerEditorHelper::setSelfValue();
		$this->set("addonDir", BurgerEditorUtil::getAddonPath());
	}

	// エディタ出力
	public function admin_editor(){
		$this->layout = false;
		$this->set('inputId', $this->params['inputId']);
		if ($this->params['draftId']) $this->set('draftId', $this->params['draftId']);
	}

	// アップロード画像一覧取得
	public function admin_img_list(){
		$searchWord = empty($_GET["q"]) ? null : $_GET['q']; // 検索ワード
		$savePath = BurgerEditorHelper::$imageFileBaseDir;
		clearViewCache();

		$fileList = $this->getFormatedImageList($searchWord);
		$result = array('error'=>false, 'data'=> $fileList);
		Configure::write('debug', 0);
		$this->RequestHandler->setContent('json');
		$this->RequestHandler->respondAs('application/json; charset=UTF-8');
		header("Content-Type: application/json; charset=utf-8");
		echo json_encode($result);
		exit();
	}

	// 画像ファイルアップロード
	public function admin_img_upload(){
//		$this->layout = 'json';
		BurgerEditorHelper::getImageList(); // 一覧情報取得・更新
		$savePath = BurgerEditorHelper::$imageFileBaseDir;
		clearViewCache();

		$hasError = false;
		if (!$_FILES) $hasError = 'ファイルがアップロードされていません';
		if (!is_writeable($savePath)) $hasError = 'アップロードフォルダに書き込めません';
		foreach($_FILES as $name => $fileData){
			if (!$name) {
				$hasError = 'アップロードに失敗しました';
			} else {
				if ($fileData["error"] == UPLOAD_ERR_INI_SIZE) {
					$hasError = 'ファイル容量が大きすぎます';
				}
				$fileExt = $this->getExtension($fileData["name"]);
				if (!in_array(strtolower($fileExt), $this->imgExts)) {
					$hasError = "画像形式のファイルをアップロードしてください";
				}
				if ($fileData["error"] == UPLOAD_ERR_PARTIAL) {
					$hasError = "ファイルが正しくアップロードされませんでした";
				}
			}

			// 画像データサイズ制限
			if (!$hasError && $fileData['size'] > $this->imageDataMaxsize) {
				$viewSize = (($this->imageDataMaxsize / 1024) / 1024) . 'MB';
				$hasError = "データサイズは{$viewSize}以下のファイルをアップロードしてください";
			}
//			// 画像大きさサイズ制限 - リサイズ機能がついたため廃止
//			if (!$hasError) {
//				list($width, $height) = getimagesize($fileData['tmp_name']);
//				if ($width > $this->imageMaxWidth || $height > $this->imageMaxHeight) {
//					$hasError = "データサイズは横幅{$this->imageMaxWidth}px以下、縦幅{$this->imageMaxHeight}px以下のファイルをアップロードしてください";
//				}
//			}

			if ($hasError) break;
		}

		// 何かしらエラー
		if ($hasError){
			$result = array('error'=>$hasError, 'data'=>$hasError);
		} else {

			// 保存
			$uploaddir = BurgerEditorHelper::$imageFileBaseDir;

			$saveFiles = array();
			foreach($_FILES as $name => $fileData){
				BurgerEditorHelper::$imageFileMaxId++;
				$basename = $fileData["name"];
				$filename = (BurgerEditorHelper::$imageFileMaxId) . "__" . $this->b64e($this->getFileNameNoExtension($basename));
				// 拡張子
				$baseExt = $this->getExtension($basename);
				if ($baseExt) {
					$filename .= "." . $baseExt;
				}
				move_uploaded_file($fileData["tmp_name"], $uploaddir.$filename);
				//回転
				$this->BurgerEditor->rotateImage($uploaddir.$filename);

				// 基本ファイル名 - 拡張子なし
				$baseFile = $this->getFileNameNoExtension($filename);

				// リサイズ除外拡張子判定
				if (in_array(strtolower($baseExt), Configure::read('Bge.noResizeExtension'))) {
					// 元サイズ - リサイズせずコピー
					$thumbFilename = $baseFile.'__org.'.$baseExt;
					copy($uploaddir.$filename, $uploaddir.$thumbFilename);
					$saveFiles[] = $thumbFilename;

					// サムネイル作成 - リサイズせずコピー
					$thumbFilename = $baseFile.'__small.'.$baseExt;
					copy($uploaddir.$filename, $uploaddir.$thumbFilename);
					$saveFiles[] = $thumbFilename;

				} else {
					// リサイズクラス生成
					$imageResizer = new Imageresizer();

					// 圧縮レベルの取得
					$quarity = Configure::read('Bge.uploadImageQuality');

					// 元サイズ - $this->imgSizeWidthMaxを超える場合は$this->imgSizeWidthMaxにリサイズ
					$thumbFilename = $baseFile.'__org.'.$baseExt;
					$imageResizer->resize($uploaddir.$filename, $uploaddir.$thumbFilename, $this->imgSizeWidthMax, null, false, $quarity);
					$saveFiles[] = $thumbFilename;

					// サムネイル作成
					$thumbFilename = $baseFile.'__small.'.$baseExt;
					$imageResizer->resize($uploaddir.$filename, $uploaddir.$thumbFilename, $this->imgSizeWidthSmall, null, false, $quarity);
					$saveFiles[] = $thumbFilename;

					// 標準サイズ
					$thumbFilename = $baseFile.'.'.$baseExt;
					$imageResizer->resize($uploaddir.$filename, $uploaddir.$thumbFilename, $this->imgSizeWidthDefault, null, false, $quarity);
					$saveFiles[] = $thumbFilename;

					unset($imageResizer);
				}

			}

			/*** BurgerEditor.afterImageSave ***/
			$this->dispatchEvent('afterImageSave', array(
				'data' => array(
					'files' => $saveFiles,
					'uploaddir' => $uploaddir,
				),
			));

			// ファイル読み直し
			BurgerEditorHelper::getImageList();
			$fileList = $this->getFormatedImageList();

			$result = array('error'=>$hasError, 'data'=> $fileList);
		}

		Configure::write('debug', 0);
		$this->RequestHandler->setContent('json');
		$this->RequestHandler->respondAs('application/json; charset=UTF-8');
		echo json_encode($result);
		exit();

	}

	// アップロードファイル削除
	public function admin_img_delete(){
		$filename = $this->mb_basename($this->data['file']);
		$res = 0;
		if (file_exists(BurgerEditorHelper::$imageFileBaseDir.$filename)){
			$res = unlink(BurgerEditorHelper::$imageFileBaseDir.$filename);

			// サイズ別に生成したファイルがあれば削除
			$baseFile = $this->getFileNameNoExtension($filename);
			$baseExt  = $this->getExtension($filename);
			if (file_exists(BurgerEditorHelper::$imageFileBaseDir.$baseFile.'__org.'.$baseExt)){
				unlink(BurgerEditorHelper::$imageFileBaseDir.$baseFile.'__org.'.$baseExt);
			}
			if (file_exists(BurgerEditorHelper::$imageFileBaseDir.$baseFile.'__small.'.$baseExt)){
				unlink(BurgerEditorHelper::$imageFileBaseDir.$baseFile.'__small.'.$baseExt);
			}
		}
		echo intval($res);
		exit;
	}


	// アップロードファイル一覧取得
	public function admin_file_list(){
		$searchWord = empty($_GET["q"]) ? null : $_GET['q']; // 検索ワード
		$savePath = BurgerEditorHelper::$otherFileBaseDir;
		clearViewCache();
		$fileList = $this->getFormatedOtherList($searchWord);
		$result = array('error'=>false, 'data'=> $fileList);
		Configure::write('debug', 0);
		$this->RequestHandler->setContent('json');
		$this->RequestHandler->respondAs('application/json; charset=UTF-8');
		header("Content-Type: application/json; charset=utf-8");
		echo json_encode($result);
		exit();
	}


	// ファイルアップロード
	public function admin_file_upload(){
		clearViewCache();
		BurgerEditorHelper::getFileList(); // ファイル一覧取得・データ更新

		$hasError = false;
		if (!$_FILES) $hasError = 'ファイルがアップロードされていません';
		foreach($_FILES as $name => $fileData){
			if (!$name) {
				$hasError = 'アップロードに失敗しました';
			} else {
				if ($fileData["error"] == UPLOAD_ERR_INI_SIZE) {
					$hasError = 'ファイル容量が大きすぎます';
				}
				// ファイル名チェック
//				if (!$this->checkFileName($fileData["name"])) {
//					$hasError = "ファイル名は半角英数記号でアップロードしてください";
//				}
				if ($fileData["error"] == UPLOAD_ERR_PARTIAL) {
					$hasError = "ファイルが正しくアップロードされませんでした";
				}
			}
			if ($hasError) break;
		}

		// 何かしらエラー
		if ($hasError){
			$result = array('error'=>$hasError, 'data'=>$hasError);
		} else {

			// 保存
			$uploaddir = BurgerEditorHelper::$otherFileBaseDir;
			foreach($_FILES as $name => $fileData){
				BurgerEditorHelper::$otherFileMaxId++;
				$basename = $fileData["name"];
				$filename = (BurgerEditorHelper::$otherFileMaxId) . "__" . $this->b64e($this->getFileNameNoExtension($basename));
				$ext = $this->getExtension($basename);
				if ($ext) {
					$filename .= "." . $ext;
				}
				move_uploaded_file($fileData["tmp_name"], $uploaddir.$filename);
			}

			// ファイル読み直し
			BurgerEditorHelper::getFileList();
			$fileList = $this->getFormatedOtherList();
			$result = array('error'=>$hasError, 'data'=> $fileList);
		}

		Configure::write('debug', 0);
		$this->RequestHandler->setContent('json');
		$this->RequestHandler->respondAs('application/json; charset=UTF-8');
		echo json_encode($result);
		exit();

	}


	// アップロードファイル削除
	public function admin_file_delete(){
		$filename = $this->mb_basename($this->data['file']);
		if (file_exists(BurgerEditorHelper::$otherFileBaseDir.$filename)){
			unlink(BurgerEditorHelper::$otherFileBaseDir.$filename);
		}
		echo '1';
		exit;
	}

	// base64encodeされたファイル名をdecodeして変換
	public function admin_get_filename($encodedFileName) {
		// no__から始まるファイル名のみ
		Configure::write('debug', 0);
		$this->RequestHandler->setContent('json');
		$this->RequestHandler->respondAs('application/json; charset=UTF-8');

		$fileId = preg_match("/^(\d+)__/", $encodedFileName, $maches);
		if (isset($maches[1])) {
			$fileId = (isset($maches[1])) ? $maches[1] : '';
			$basename = $this->getFileNameNoExtension(preg_replace("/^\d+__/", "", $encodedFileName));
			$ext = '';
			if ($this->getExtension($encodedFileName)) {
				$ext = "." . $this->getExtension($encodedFileName);
			}
			$filename = $fileId . '.' . $this->b64d($basename) . $ext;
			echo json_encode(array('filename' => $filename));

		} else {
			echo json_encode(array('filename' => $encodedFileName));
		}
		exit;
	}


	/**
	 * ファイルダウンロードAction
	 *
	 */
	public function dl(){
		$this->layout = false;
		$this->autoRender = false;
		$params = func_get_args();
		// path階層は2階層まで許可
		if (empty($params) || count($params) > 2) {
			$this->notfound();
		}
		// ユーザIDを利用する場合1階層目は数値のみ許可
		if (count($params) === 2) {
			$paramsPath = (int)$params[0] . DS . $params[1];
		} else {
			$paramsPath = $params[0];
		}

		$filePath = WWW_ROOT . 'files' . DS . 'bgeditor' . DS . 'other' .DS . $paramsPath;
		$filename = preg_replace("/^\d+__/", "", $this->mb_basename($filePath));
		$basename = $this->getFileNameNoExtension($filename);
		if ($filename != $this->mb_basename($filePath)) {
			$filename = $this->b64d($basename) . "." . $this->getExtension($filePath);
		}

		$mimeType = $this->response->getMimeType($this->getExtension($filePath));
		if ($mimeType === false) {
			$mimeType = 'application/octet-stream';
		} elseif (is_array($mimeType)) {
			$mimeType = $mimeType[0];
		}

		if (!file_exists($filePath) || is_dir($filePath)){
			$this->notfound();
		}

		/**
		 * RFC6266 のヘッダエンコードに従いUTF-8で出力した、IE9以降及び一般的なブラウザのファイル名に対応
		 * http://tools.ietf.org/html/rfc6266
		 */
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.0') === false) {
			header("Content-Disposition: inline; filename*=UTF-8''".rawurlencode($filename));

		/**
		 * IE8以下のRFC6266に準拠していないIEについてはSJISでファイル名を吐く
		 */
		} else {
			header("Content-Disposition: inline; filename=".mb_convert_encoding($filename, 'SJIS-win', 'UTF-8'));
		}
		header("Content-Type: " . $mimeType);
		header('Content-Length: ' . filesize($filePath));
		readfile($filePath);
		exit;
	}
	/**
	 * UA prefix対応
	 */
	public function smartphone_dl(){
		// 現時点では3階層までの対応
		$params = func_get_args();
		if (count($params) == 2) {
			$this->setAction('dl', $params[0], $params[1]);
		} elseif (count($params) == 3) {
			$this->setAction('dl', $params[0], $params[1], $params[2]);
		} else {
			$this->setAction('dl', $params[0]);
		}
	}
	public function mobile_dl(){
		// 現時点では3階層までの対応
		$params = func_get_args();
		if (count($params) == 2) {
			$this->setAction('dl', $params[0], $params[1]);
		} elseif (count($params) == 3) {
			$this->setAction('dl', $params[0], $params[1], $params[2]);
		} else {
			$this->setAction('dl', $params[0]);
		}
	}



	/**
	 * JSON変換用画像ファイルリストを取得する
	 *
	 * @return array
	 */
	protected function getFormatedImageList($searchWord = null){
		$fileList = array();
		$imageFileList = BurgerEditorHelper::getImageList();
		foreach ($imageFileList as $filePath) {
			$fileId = preg_match("/^(\d+)__/", $this->mb_basename($filePath), $maches);
			$fileId = (isset($maches[1])) ? $maches[1] : '';
			$filename = preg_replace("/^\d+__/", "", $this->mb_basename($filePath));
			$basename = $this->getFileNameNoExtension($filename);
			$ext = $this->getExtension($filePath);
			$orgImage = 0;
			$smallImage = 0;
			if ($filename != $this->mb_basename($filePath)) {
				$filename = $this->b64d($basename) . "." . $ext;

				if (file_exists(BurgerEditorHelper::$imageFileBaseDir . $fileId . '__' . $basename. '__org.'.$ext)) {
					$orgImage = h(BurgerEditorHelper::$imageFileBaseURL.$fileId . '__' . $basename. '__org.'.$ext);
				}
				if (file_exists(BurgerEditorHelper::$imageFileBaseDir . $fileId . '__' . $basename. '__small.'.$ext)) {
					$smallImage = h(BurgerEditorHelper::$imageFileBaseURL.$fileId . '__' . $basename. '__small.'.$ext);
				}
			}
			if (file_exists($filePath)) {
				if ($searchWord === null || (strpos($fileId, $searchWord) !== false || strpos($filename, $searchWord) !== false)) {
					$fileList[] = array(
						'url' => h(BurgerEditorHelper::$imageFileBaseURL.$this->mb_basename($filePath)),
						'fileid' => $fileId,
						'name' => mb_convert_encoding($filename, 'UTF-8', 'UTF-8'),  // 文字化けファイルがアップロードされた場合JSONが変換できないためUTF-8として読み込める文字に変換
						'filetime' => date('Y/m/d H:i', filemtime($filePath)),
						'size' => filesize($filePath),
						'original' => $orgImage,
						'thumb' => $smallImage,
					);
				}
			}
		}

		// No(fileid)の降順に並べ替える
		$fileList = Hash::sort($fileList, '{n}.fileid', 'DESC');

		// 画像なしを先頭へ追加
		array_unshift(
			$fileList,
			array(
				"url" => $this->webroot .'files/bgeditor/bg-noimage.gif',
				'fileid' => '',
				'name' => '画像無し',
				'filetime' => '',
				'size' => 0,
				'original' => 0,
				'thumb' => 0,
			)
		);

		return $fileList;
	}

	/**
	 * JSON変換用ファイルリストを取得する
	 *
	 * @return array
	 */
	protected function getFormatedOtherList($searchWord = null){
		$fileList = array();
		$user = BcUtil::loginUser();
		$otherFileList = BurgerEditorHelper::getFileList();
		foreach ($otherFileList as $filePath) {
			$fileId = preg_match("/^(\d+)__/", $this->mb_basename($filePath), $maches);
			$fileId = (isset($maches[1])) ? $maches[1] : '';
			$filename = preg_replace("/^\d+__/", "", $this->mb_basename($filePath));
			$basename = $this->getFileNameNoExtension($filename);
			if ($filename != $this->mb_basename($filePath)) {
				$filename = $this->b64d($basename) . "." . $this->getExtension($filePath);
			}

			// ファイルパスのディレクトリを取得する
			$fileNameAry = str_replace(BurgerEditorHelper::$otherFileBaseDir, '', $filePath);
			// 設定値により、ユーザ別にファイル場所を設置している場合
			if (!Configure::read("Bge.fileShare")) {
				$fileNameAry = $user['id'] . DS . $fileNameAry;
			}
			$urlAry = array('plugin'=>'burger_editor', 'admin' => false, 'controller' => 'burger_editor', 'action' => 'dl') + explode(DS, $fileNameAry);

			if (file_exists($filePath)) {
				if ($searchWord === null || (strpos($fileId, $searchWord) !== false || strpos($filename, $searchWord) !== false)) {
					$fileList[] = array(
						'url' => h(Router::url($urlAry)),
						'fileid' => $fileId,
						'name' => mb_convert_encoding($filename, 'UTF-8', 'UTF-8'), // 文字化けファイルがアップロードされた場合JSONが変換できないためUTF-8として読み込める文字に変換
						'filetime' => date('Y/m/d H:i', filemtime($filePath)),
						'size' => filesize($filePath)
					);
				}
			}
		}

		// No(fileid)の降順に並べ替える
		$fileList = Hash::sort($fileList, '{n}.fileid', 'DESC');

		return $fileList;
	}

	// 速度向上のため、静的ファイルを書き込み
	protected function makeStaticPanel($imageFile) {
		// 公開ディレクトリが書き込み可能だったら静的ファイルを書き込む
		if (is_writable(WWW_ROOT)) {
			$pathInfo = pathinfo($this->request->url);
			$basedir = WWW_ROOT;
			foreach(explode('/', $pathInfo['dirname']) as $dir) {
				$basedir .= DS . $dir;
				if (!file_exists($basedir)) mkdir($basedir);
			}
			file_put_contents($basedir . DS . $pathInfo['basename'], file_get_contents($imageFile));
		}
	}

	/**
	 * 拡張子取得
	 *
	 * @param type $filename
	 * @return boolean
	 */
	protected function getExtension($filename){
		$nameAry = explode(".", $filename);
		if (!is_array($nameAry)) return false;
		return array_pop($nameAry);
	}

	protected function checkFileName($filename) {
		return preg_match("/^[a-zA-Z0-9\._-]+$/", $filename);
	}

	protected function getFileNameNoExtension($filename){
		$nameAry = explode(".", $filename);
		if (!is_array($nameAry)) return false;
		array_pop($nameAry);

		return implode('.', $nameAry);
	}

	/**
	 * マルチバイト対応 basename
	 *
	 */
	protected function mb_basename($str, $suffix=null){
		$tmp = preg_split('/[\/\\\\]/', $str);
		$res = end($tmp);
		if(strlen($suffix)){
			$suffix = preg_quote($suffix);
			$res = preg_replace("/({$suffix})$/u", "", $res);
		}
		return $res;
	}

	/**
	 * baserCMS標準のbase64UrlsafeEncodeが連続ドットのファイル名を禁止した特定サーバで
	 * 動作しないため独自定義
	 *
	 * @param string $str
	 * @return string
	 */
	protected function b64e($str) {
		$str = base64_encode($str);
		$ret = str_replace("..", "-D-", str_replace(array('+', '/', '='), array('_', '-', '.'), $str));

		//末尾のドットをエンコード
		if(mb_substr($ret, -1) === "."){
			return str_replace(".", "-d-", $ret);
		}else{
			return $ret;
		}
	}
	/**
	 * baserCMS標準のbase64UrlsafeEncodeが連続ドットのファイル名を禁止した特定サーバで
	 * 動作しないため独自定義のdecode版
	 * @param string $str
	 * @return string
	 */
	protected function b64d($str) {
		$str = str_replace("-d-", ".", $str);
		$str = str_replace(array('_','-', '.'), array('+', '/', '='), str_replace("-D-", "..", $str));
		return base64_decode($str);
	}
}

