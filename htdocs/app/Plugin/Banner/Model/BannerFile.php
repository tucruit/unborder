<?php
/**
 * [BANNER] バナー管理
 *
 * @copyright		Copyright 2014 - 2018, D-ZERO Co.,LTD.
 * @link			http://www.d-zero.co.jp/
 * @package			Banner
 * @license			MIT
 */
App::uses('Banner.BannerAppModel', 'Model');
class BannerFile extends BannerAppModel {
	/**
	 * モデル名
	 *
	 * @var string
	 */
	public $name = 'BannerFile';

	/**
	 * ビヘイビア
	 *
	 * @var array
	 */
	public $actsAs= array(
		'BcUpload' =>
			array('saveDir' => 'banners',
					'fields'=> array(
						'name' => array(
							'type'			=> 'image',
							'namefield'	=> 'id',
							'nameformat'	=> '%08d',
							'imagecopy'		=> array(
								'banner'	=> array('prefix' => 'banner_', 'width' => '640', 'height' => '480'),
								'thumb'		=> array('prefix' => 'thumb_', 'width' => '120', 'height' => '100'),
							),
							'del_file'		=> false
						),
					)
			));
	/**
	 * belongsTo
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'BannerArea' => array(
			'className'	=> 'Banner.BannerArea',
			'foreignKey' => 'banner_area_id'
			)
		);

	/**
	 * バリデーション
	 *
	 * @var array
	 */
	public $validate = array(
		'name' => array(
			'notEmptyFiles' => array(
				'rule'		=> array('notEmptyFiles'),
				'message'	=> '必須入力です。',
				'required' => true,	
			),
			'checkFileSize' => array(
				'rule'		=> array('checkFileSize'),
				'message'	=> '設定値より大きい縦横サイズの画像です。'
			),
			'extensionFiles' => array(
				'rule'		=> array('extensionFiles', array('gif', 'jpeg', 'png', 'jpg')),
				'message'	=> '画像ファイル(jpeg,png,gif)以外はアップロードできません。',
			),
		)
	);

	/**
	 * construct
	 */
	public function __construct() {
		$breakpointMax = Configure::read('Banner.breakpointMax');
		for ($i = 1; $i <= $breakpointMax; $i++) {
			$fieldName = 'breakpoint' . $i .'_name';
			// ビヘイビアのBcUpload設定の追加
			$this->actsAs['BcUpload']['fields'][$fieldName] = array(
				'type'			=> 'image',
				'namefield'		=> 'id',
				'nameformat'	=> '%08d',
			);

			// バリデーション設定の追加
			$this->validate[$fieldName] = array(
				'checkMediaQueryFileSize' => array(
					'rule'		=> array('checkMediaQueryFileSize', $i),
					'message'	=> '設定値より大きい縦横サイズの画像です。'
				),
				'extensionFiles' => array(
					'rule'		=> array('extensionFiles', array('gif', 'jpeg', 'png', 'jpg')),
					'message'	=> '画像ファイル(jpeg,png,gif)以外はアップロードできません。',
				),
			);
		};

		parent::__construct();
	}

	/**
	 * カスタムバリデーション
	 * ・ファイルがアップされているかをチェックする
	 *
	 * @param array $check
	 * @return boolean
	 */
	public function notEmptyFiles($check = array()) {
		// 編集の際にはファイルアップの有無をチェックしない
		if ($this->id) {
			return true;
		}
		
		if (is_array($check)) {
			if (!$check['name']['name']) {
				return false;
			}
		} else {
			if (!$check) {
				return false;
			}
		}
		
		return true;
	}

	/**
	 * カスタムバリデーション
	 * ・ファイルの縦横サイズを設定値と比較する
	 *
	 * @param array $check
	 * @return boolean
	 */
	public function checkFileSize($check = array()) {
		if (!empty($check['name']['tmp_name'])) {
			$imgInfo = GetImageSize($check['name']['tmp_name']);
			if ($imgInfo) {
				$width = $imgInfo[0];
				$height = $imgInfo[1];
			}
			
			if ( $this->BannerArea->data['BannerArea']['width'] &&
				$width > $this->BannerArea->data['BannerArea']['width'] ) {
				return false;
			}
			if ( $this->BannerArea->data['BannerArea']['height'] &&
				$height > $this->BannerArea->data['BannerArea']['height'] ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * カスタムバリデーション
	 * ・メディアクエリ用ファイルの縦横サイズを設定値と比較する
	 *
	 * @param array $check
	 * @return boolean
	 */
	public function checkMediaQueryFileSize($check = array(), $no) {
		$fieldName = key($check);

		if (!empty($check[$fieldName]['tmp_name'])) {
			$imgInfo = GetImageSize($check[$fieldName]['tmp_name']);
			if ($imgInfo) {
				$width = $imgInfo[0];
				$height = $imgInfo[1];
			}

			$breakpointWidthLimit = $this->BannerArea->data['BannerArea']['breakpoint' . $no .'_width'];
			$breakpointHeightLimit = $this->BannerArea->data['BannerArea']['breakpoint' . $no .'_height'];
			
			if ( $breakpointWidthLimit && $width > $breakpointWidthLimit ) {
				return false;
			}
			if ( $breakpointHeightLimit && $height > $breakpointHeightLimit ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * 画像種類チェック
	 * 
	 * @param type $check
	 * @param type $extensions
	 * @return boolean
	 */
	public static function extensionFiles($check, $extensions = array('gif', 'jpeg', 'png', 'jpg')) {
		if (empty($check[key($check)]['name'])) {
			return true;
		}
		if (is_array($check)) {
			return Validation::extension(array_shift($check), $extensions);
		}
		$extension = strtolower(pathinfo($check, PATHINFO_EXTENSION));
		foreach ($extensions as $value) {
			if ($extension === strtolower($value)) {
				return true;
			}
		}
		return false;
	}

	/**
	 * 初期値を取得する
	 *
	 * @return array
	 */
	public function getDefaultValue() {
		$user = BcUtil::loginUser();
		$data = array(
			'BannerFile' => array(
				'status' => false,
				'user_id' => $user['id'],
			)
		);
		return $data;
	}

	/**
	 * 公開状態を取得する
	 *
	 * @param array $data
	 * @return boolean
	 */
	public function allowPublish($data){
		if (isset($data['BannerFile'])){
			$data = $data['BannerFile'];
		}
		$allowPublish = $data['status'];
		return $allowPublish;
	}

	/**
	 * 公開済の conditions を取得
	 * 
	 * @return array
	 */
	public function getConditionAllowPublish() {
		$conditions[$this->alias.'.status'] = true;
		$conditions[] = array('or'=> array(array($this->alias.'.publish_begin <=' => date('Y-m-d H:i:s')),
										array($this->alias.'.publish_begin' => NULL),
										array($this->alias.'.publish_begin' => '0000-00-00 00:00:00')));
		$conditions[] = array('or'=> array(array($this->alias.'.publish_end >=' => date('Y-m-d H:i:s')),
										array($this->alias.'.publish_end' => NULL),
										array($this->alias.'.publish_end' => '0000-00-00 00:00:00')));
		return $conditions;
	}

	/**
	 * コピーする
	 * 
	 * @param int $id
	 * @return array or false
	 */
	public function copy($bannerAreaId, $id) {
		$data	 = $data	 = $this->find('first', array('conditions' => array('BannerFile.id' => $id)));
		$oldData = $data;

		// EVENT beforeCopy
		$event = $this->dispatchEvent('beforeCopy', [
			'data'	 => $data,
			'id'	 => $id,
		]);
		if ($event !== false) {
			$data = $event->result === true ? $event->data['data'] : $event->result;
		}

		$data['BannerFile']['no']				 = $this->getMax('no', array('BannerFile.banner_area_id' => $bannerAreaId)) + 1;
		$data['BannerFile']['sort']				 = $this->getMax('sort') + 1;
		$data['BannerFile']['banner_area_id']	 = $bannerAreaId;

		unset($data['BannerFile']['id']);
		unset($data['BannerFile']['created']);
		unset($data['BannerFile']['modified']);

		// afterSaveでリネームされてしまうのを避けるため、一旦退避する
		$tmpData			 = [];
		$fileFieldSetting	 = $this->actsAs['BcUpload']['fields'];
		$fileFieldList		 = array_keys($fileFieldSetting);
		foreach ($fileFieldList as $value) {
			if (isset($data[$this->name][$value])) {
				$tmpData[$this->name][$value] = $data[$this->name][$value];
				unset($data[$this->name][$value]);
			}
		}

		$this->create($data);
		$result = $this->save($data, array(
			'validate'	 => false,
			'callbacks'	 => false,
		));

		if ($result) {
			$beforeSaveData	 = Hash::merge($result, $tmpData);
			$this->set($beforeSaveData);
			$saveData		 = $this->renameToBasenameFields(true);
			$afterSaveData	 = $this->save($saveData);

			// EVENT Banner.afterCopy
			$event = $this->dispatchEvent('afterCopy', array(
				'id'		 => $afterSaveData[$this->name]['id'],
				'data'		 => $afterSaveData,
				'oldId'		 => $id,
				'oldData'	 => $oldData,
			));

			return $afterSaveData;
		} else {
			return false;
		}
	}

}
