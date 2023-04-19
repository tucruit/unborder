<?php
/**
 * [BANNER] バナーエリア管理
 *
 * @copyright		Copyright 2014 - 2018, D-ZERO Co.,LTD.
 * @link			http://www.d-zero.co.jp/
 * @package			Banner
 * @license			MIT
 */
App::uses('Banner.BannerAppModel', 'Model');
class BannerArea extends BannerAppModel {
	/**
	 * モデル名
	 * 
	 * @var string
	 */
	public $name = 'BannerArea';

	/**
	 * actsAs
	 * 
	 * @var array
	 */
	public $actsAs = array('BcCache');

	/**
	 * hasMany
	 *
	 * @var array
	 */
	public $hasMany = array(
		'BannerFile' => array(
			'className' => 'Banner.BannerFile',
			'foreignKey' => 'banner_area_id',
			'order' => 'BannerFile.sort ASC',
		),
	);

	/**
	 * バリデーション
	 *
	 * @var array
	 */
	public $validate = array(
		'name' => array(
			'notBlank' => array(
				'rule'		=> array('notBlank'),
				'message'	=> '必須入力です。'
			),
			'duplicate' => array(
				'rule'		=>	array('duplicate', 'name'),
				'message'	=> '既に登録のあるエリア名です。'
			)
		),
		'width' => array(
			'numeric' => array(
				'rule'			=> array('numeric'),
				'message'		=> '数値でご入力ください。',
				'allowEmpty'	=> true
			)
		),
		'height' => array(
			'numeric' => array(
				'rule'			=> array('numeric'),
				'message'		=> '数値でご入力ください。',
				'allowEmpty'	=> true
			)
		)
	);

	/**
	 * construct
	 */
	public function __construct() {
		// バリデーション設定の複製
		$breakpointMax = Configure::read('Banner.breakpointMax');
		for ($i = 1; $i <= $breakpointMax; $i++) {
			$this->validate = array_merge($this->validate, [
				'breakpoint' . $i . '_width' => $this->validate['width'],
				'breakpoint' . $i . '_height' => $this->validate['height'],
			]);
		};

		parent::__construct();
	}

	/**
	 * コピーする
	 * 
	 * @param int $id
	 * @return array or false
	 */
	public function copy($id) {
		if ($id) {
			$data = $this->find('first', array('conditions' => array('BannerArea.id' => $id)));
		}
		$oldData = $data;

		// EVENT beforeCopy
		$event = $this->dispatchEvent('beforeCopy', [
			'data'	 => $data,
			'id'	 => $id,
		]);
		if ($event !== false) {
			$data = $event->result === true ? $event->data['data'] : $event->result;
		}

		$data['BannerArea']['name'] .= '_copy';
		$data['BannerArea']['name'] = $this->makeNonDuplicateName($data['BannerArea']['name']);

		unset($data['BannerArea']['id']);
		unset($data['BannerArea']['created']);
		unset($data['BannerArea']['modified']);

		$this->create($data);
		$result = $this->save();
		if ($result) {
			// バナーエリアのコピーに成功したら、属しているバナーも複製する
			if ($data['BannerFile']) {
				foreach ($data['BannerFile'] as $banner) {
					$this->BannerFile->copy($result['BannerArea']['id'], $banner['id']);
					clearDataCache();
				}
			}

			// EVENT afterCopy
			$event = $this->dispatchEvent('afterCopy', array(
				'id'		 => $result['BannerArea']['id'],
				'data'		 => $result,
				'oldId'		 => $id,
				'oldData'	 => $oldData,
			));

			return $result;
		} else {
			return false;
		}
	}

	/**
	 * 重複するバナーエリア名をチェックし、重複しない名称を作る
	 * 
	 * @param string $name
	 * @return string
	 */
	public function makeNonDuplicateName($name, $nonDuplicate = false) {
		if ($nonDuplicate) {
			return $name;
		}

		$data = $this->find('first', array(
			'conditions' => array('BannerArea.name' => $name),
			'recursive'	 => -1,
			'callbacks'	 => false,
		));
		if ($data) {
			$name .= $data['BannerArea']['name'] . '_copy';
			$name = $this->makeNonDuplicateName($name);
		} else {
			$name = $this->makeNonDuplicateName($name, true);
		}

		return $name;
	}

}
