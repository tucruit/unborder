<?php
/**
 * [InstantPage] InstantPageTemplateCategory管理
 */
class InstantPageTemplateCategory extends AppModel {

	public $useTable = 'instant_page_template_categories';


	/**
	 * 画像保存パス
	 *
	 * @var string
	 * @access public
	 */
	public $imageDir = 'instant_page_template_category';

	/**
	 * 画像のデフォルト幅（px）
	 *
	 * @var string
	 * @access public
	 */
	public $widthSize = 500;

	/**
	 * 画像のデフォルト高さ（px）
	 *
	 * @var string
	 * @access public
	 */
	public $heightSize = 500;

	/**
	 * 画像の最大枚数
	 *
	 * @var string
	 * @access public
	 */
	public $imageCount = 2;

	/**
	 * 画像を保存する。
	 *
	 * @params array $data
	 * @params init $width
	 * @params init $height
	 * @return bool
	 * @access public
	 */
	public function savePostImg($data, $width = null, $height = null){

		//現在のデータを取得する。（画像に変更がなかった場合の処理）
		if (!empty($data[$this->name]['id'])) {
			$ex_contentData = $this->find('first', array('conditions' => array(
				$this->name.'.id' => $data[$this->name]['id']
			)));
		}

		//画像の幅指定
		if (!empty($width)) {
			$limitWidth = $width;
		} else {
			$limitWidth = $this->widthSize; //デフォルト
		}
		if (!empty($height)) {
			$limitHeight = $height;
		} else {
			$limitHeight = $this->heightSize; //デフォルト
		}

		//ファイル送信の確認と保存処理
		for ($i = 1; $i <= $this->imageCount; $i++) {
			if (empty($data[$this->name]['del_image_'.$i]) || $data[$this->name]['del_image_'.$i] == 0) { //削除指定がない場合にのみアップ作業
				if (!empty($data[$this->name]['image_'.$i]['tmp_name'])) {
					//現在のファイル名と、そこから拡張子を確認する。
					$img = $this->_copyTmpImage($data[$this->name]['image_'.$i], 'ex_content_'.$i.'_' , $limitWidth, $limitHeight);
					$data[$this->name]['image_'.$i] = $img;

					//ファイル送信なしの場合
				} else {
					//旧画像名をそのままセットする。
					if (!empty($ex_contentData[$this->name]['image_'.$i])) {
						$data[$this->name]['image_'.$i] = $ex_contentData[$this->name]['image_'.$i];
					} else {
						$data[$this->name]['image_'.$i] = "";
					}
				}

				//既存のファイルの削除指示がある場合。
			} else {
				$this->_deletePostImage('image_'.$i, $data);
				$data[$this->name]['image_'.$i] = ''; //フィールドも空をセット
			}
		}
		return $data;
	}

	/**
	 * 画像を削除する。
	 *
	 * @params string $imgKey
	 * @params array $data
	 * @return bool
	 * @access public
	 */
	public function deleteSaveImage($imageName){
		//削除処理
		if (!empty($imageName)) {
			$delFileName = WWW_ROOT . 'img' . DS . $this->imageDir . DS . $imageName;
			if (file_exists($delFileName)) {
				@unlink($delFileName);
				return true;
			}else{
				return false;
			}
		}
	}

	/**
	 * 一時ファイルから公開フォルダ上へコピーして
	 * 新しいファイル名を返す。
	 *
	 * @params array $data
	 * @params string $prefix
	 * @params int $width
	 * @params int $height
	 * @return string
	 * @access protected
	 */
	protected function _copyTmpImage($data, $prefix, $width, $height) {
		//コピー元
		$fileName = $data['name'];
		$ext = pathinfo($fileName, PATHINFO_EXTENSION);
		//新しいファイル名を生成
		$fileNamePrefix = $prefix;
		$fileNameNo = date("YmdHis");
		$newFileName =  $fileNamePrefix.$fileNameNo;
		$imgPath = WWW_ROOT . 'img' . DS . $this->imageDir . DS . $newFileName . '.' . $ext;
		//一時ファイルから動かす。
		//サムネイル生成
		$Imageresizer = new Imageresizer();
		$Imageresizer->resize($data['tmp_name'], $imgPath, $width, $height);
		//一時ファイルを消し去ってしまう。
		return $newFileName . '.' . $ext;
	}

	/**
	 * 画像を削除する。
	 *
	 * @params string $imgKey
	 * @params array $data
	 * @return bool
	 * @access protected
	 */
	protected function _deletePostImage($imgKey, $data){
		//現在のデータを確認する。
		$ex_contentData = $this->find('first', array(
			'conditions' => array(
				$this->name.'.id' => $data[$this->name]['id']
			),
			'field' => array(
				"$this->name.$imgKey" //メモリ節約の為必要なフィールドのみ取得
			)));
		//削除処理
		if (!empty($ex_contentData[$this->name]["$imgKey"])) {
			$delFileName = WWW_ROOT . 'img' . DS . $this->imageDir . DS . $ex_contentData[$this->name]["$imgKey"];
			if (file_exists($delFileName)) {
				@unlink($delFileName);
				return true;
			}else{
				return false;
			}
		}
	}


}
