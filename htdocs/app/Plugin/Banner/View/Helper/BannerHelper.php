<?php
/**
 * [BANNER] バナーヘルパー
 *
 * @copyright		Copyright 2014 - 2018, D-ZERO Co.,LTD.
 * @link			http://www.d-zero.co.jp/
 * @package			Banner
 * @license			MIT
 */
class BannerHelper extends AppHelper {
/**
 * アップロードファイルの保存URL
 *
 * @var string
 */
	public $savedUrl = '/files/banners/';

	/**
	 * ファイル保存パス
	 *
	 * @var string
	 */
	public $savePath = null;

/**
 * バナーエリアデータ
 *
 * @var		array
 * @access	public
 */
	var $bannerAreaData = array();

/**
 * ヘルパー
 *
 * @var array
 */
	public $helpers = array('BcBaser', 'BcHtml', 'BcUpload', 'Html');

/**
 * constructer
 *
 * @param View $View
 * @param array $settings
 */
	public function __construct(View $View, $settings = array()) {
		parent::__construct($View, $settings);
		/* アップロードファイルの保存パス */
		$this->savePath = WWW_ROOT . 'files' . DS . 'banners' . DS;
	}

/**
 * ファイルが保存されているURLを取得する
 * webrootメソッドによる変換あり
 *
 * @param	string	$fileName
 * @return	string
 */
	protected function _getFileUrl($fileName) {
		if ($fileName) {
			$Model = ClassRegistry::init('Banner.BannerFile');
			$saveDir = $Model->getSaveDir(false);
			$saveDirInTheme = $Model->getSaveDir(true);
			$saveUrl = false;
			if(file_exists($saveDir . $fileName)) {
				$saveUrl = $this->savedUrl;
			} elseif(file_exists($saveDirInTheme . $fileName)) {
				$siteConfig = Configure::read('BcSite');
				$saveUrl = '/theme/' . $siteConfig['theme'] . '/' . str_replace(DS, '/', $this->savedUrl) . '/';
			}
			if (Configure::read('App.baseUrl')) {
				return $this->webroot($saveUrl . $fileName);
			}else {
				return $this->url($saveUrl . $fileName);
			}
		} else {
			return '';
		}
	}

/**
 * 公開状態を取得する
 *
 * @param array $data データリスト
 * @return boolean 公開状態
 */
	public function allowPublish($data){
		if (ClassRegistry::isKeySet('Banner.BannerFile')) {
			$BannerFile = ClassRegistry::getObject('Banner.BannerFile');
		} else {
			$BannerFile = ClassRegistry::init('Banner.BannerFile');
		}
		return $BannerFile->allowPublish($data);
	}

/**
 * 「別窓で開く」ステータスを判別して表示する
 *
 * @param array $data
 * @param array $options
 * @param array $attributes
 * @return string
 */
	public function judgeTargetBlank($data = array(), $options = array(), $attributes = array()) {
		$_options = array(
			'tag' => 'small',
			'text' => '別窓で開く'
		);
		$options = Hash::merge($_options, $options);

		$str = '';
		if ($data) {
			// TODO ヘルパが自動初期化されないので明示的に初期化
			$this->bcHtml = new BcHtmlHelper(new View());
			$str = $this->bcHtml->tag($options['tag'], $options['text'], $attributes);
		}
		return $str;
	}

/**
 * 公開期間指定があるかどうかを判別して表示する
 *
 * @param array $data
 * @param array $options
 * @param array $attributes
 * @return string
 */
	public function judgePublishTerm($data = array(), $options = array(), $attributes = array()) {
		$_options = array(
			'tag' => 'small',
			'text' => '公開期間指定あり'
		);
		$options = Hash::merge($_options, $options);

		$str = '';
		if ($data) {
			if (!empty($data['BannerFile']['publish_begin']) || !empty($data['BannerFile']['publish_end'])) {
				// TODO ヘルパが自動初期化されないので明示的に初期化
				$this->bcHtml = new BcHtmlHelper(new View());
				$str = $this->bcHtml->tag($options['tag'], $options['text'], $attributes);
			}
		}

		return $str;
	}

/**
 * バナーを表示する
 *
 * @param string $bannerAreaName
 * @param array $options
 * @return string
 */
	public function bannerBlockView($bannerAreaName = '', $options = array()) {
		if (empty($bannerAreaName)) {
			$bannerAreaName = 'デフォルト';
		}
		$linkOptions = array();
		$imgOptions = array();

		// リンク用オプションのデフォルト設定
		$_linkOptions = array();
		if (isset($options['escape'])) {
			$_linkOptions['escape'] = $options['escape'];
			unset($options['escape']);
		}
		$linkOptions = Hash::merge($linkOptions, $_linkOptions);

		// 画像用オプションのデフォルト設定
		$_imgOptions = array();
		if (isset($options['alt'])) {
			$_imgOptions['alt'] = $options['alt'];
			unset($options['alt']);
		}
		if (isset($options['width'])) {
			$_imgOptions['width'] = $options['width'];
			unset($options['width']);
		}
		if (isset($options['height'])) {
			$_imgOptions['height'] = $options['height'];
			unset($options['height']);
		}
		$imgOptions = Hash::merge($imgOptions, $_imgOptions);

		// オプション値のデフォルトを設定
		$_options = array(
			'num'		=> 0,
			'imgsize'	=> 'midium',
			'rel'		=> '',
			'title'		=> '',
			'link'		=> true
		);
		$options = Hash::merge($_options, $options);

		if (ClassRegistry::isKeySet('Banner.BannerArea')) {
			$BannerAreaModel = ClassRegistry::getObject('Banner.BannerArea');
		} else {
			$BannerAreaModel = ClassRegistry::init('Banner.BannerArea');
		}
		if (ClassRegistry::isKeySet('Banner.BannerFile')) {
			$BannerFileModel = ClassRegistry::getObject('Banner.BannerFile');
		} else {
			$BannerFileModel = ClassRegistry::init('Banner.BannerFile');
		}

		$datas = array();
		$this->bannerAreaData = $BannerAreaModel->findByName($bannerAreaName, null, null, -1);
		if ($this->bannerAreaData) {
			$conditions = array(
				'BannerFile.banner_area_id' => $this->bannerAreaData['BannerArea']['id']
			);
			$conditions = array_merge($conditions, $BannerFileModel->getConditionAllowPublish());
			$datas = $BannerFileModel->find('all', array(
				'conditions' => $conditions,
				'recursive' => -1,
				'order' => 'BannerFile.sort',
				'limit' => $options['num']
			));

			foreach ($datas as $key => $data) {
				if ($datas[$key]['BannerFile']['name']) {
					$datas[$key]['BannerFile']['name'] = $this->_getFileUrl($datas[$key]['BannerFile']['name']);
				}
			}
		}

		$datas = $this->mergeBannerBreakpointData($datas);

		return $datas;
	}

/**
 * バナー情報にブレークポイント情報を付与する
 *
 * @param array $datas
 * @return array
 */
	private function mergeBannerBreakpointData($datas) {
		if (ClassRegistry::isKeySet('Banner.BannerBreakpoint')) {
			$BannerBreakpointModel = ClassRegistry::getObject('Banner.BannerBreakpoint');
		} else {
			$BannerBreakpointModel = ClassRegistry::init('Banner.BannerBreakpoint');
		}

		$breakpoints = $BannerBreakpointModel->find('all', array(
			'order' => 'BannerBreakpoint.id asc'
		));
		foreach ($breakpoints as $breakpoint) {
			$fieldName = 'breakpoint' . $breakpoint['BannerBreakpoint']['id'] . '_name';
			foreach ($datas as &$data) {
				if (!empty($breakpoint['BannerBreakpoint']['status'])) {
					$data['BannerFile']['breakpoints'][] = array(
						'breakpoint_name' => $breakpoint['BannerBreakpoint']['name'],
						'name' => $this->_getFileUrl($data['BannerFile'][$fieldName]),
						'media_script' => $breakpoint['BannerBreakpoint']['media_script'],
					);
				}
				unset($data['BannerFile'][$fieldName]);
			}
			unset($data);
		}

		return $datas;
	}

/**
 * 指定されたバナーエリア名を元にバナーを表示する
 *
 * @param string $bannerAreaName
 * @param type $options
 * @return void
 */
	public function showBanner($bannerAreaName = '', $options = array()) {
		if (!$bannerAreaName) {
			$bannerAreaName = 'デフォルト';
		}
		$_options = array(
			'num' => 0,
			'template' => 'banner_block'
		);
		$options = Hash::merge($_options, $options);
		extract($options);

		$bannerDatas = $this->bannerBlockView($bannerAreaName, $options);
		$this->BcBaser->element('Banner.' . $template, array('plugin' => 'banner', 'bannerDatas' => $bannerDatas));
	}
/**
 * 指定されたバナーデータを元にバナーの説明を表示する
 *
 * @param array $bannerData
 * @return void
 */
	public function showDescription($bannerData = NULL){
		if ($bannerData){
			if ($this->bannerAreaData['BannerArea']['description_flg']){
				echo $bannerData['BannerFile']['description'];
			}
		}
	}

/**
 * 指定されたバナーデータを元にバナーの説明を取得する
 *
 * @param array $bannerData
 */
	public function getDescription($bannerData = NULL){
		$string = '';
		if ($bannerData){
			if ($this->bannerAreaData['BannerArea']['description_flg']){
				$string = $bannerData['BannerFile']['description'];
			}
		}
		return $string;
	}

}
