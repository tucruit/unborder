<?php
/**
 * [BANNER][ModelEventListener] バナー管理
 *
 * @copyright		Copyright 2014 - 2018, D-ZERO Co.,LTD.
 * @link			http://www.d-zero.co.jp/
 * @package			Banner
 * @license			MIT
 */
class BannerModelEventListener extends BcModelEventListener {
	/**
	 * 登録イベント
	 *
	 * @var array
	 */
	public $events = array(
		'Banner.BannerFile.beforeDelete',
		'Banner.BannerFile.afterDelete',
	);

	/**
	 * バナー画像保存先のパス
	 * 
	 * @var string
	 */
	public $savePath = '';

	/**
	 * 重複のあった画像ファイル名
	 * 
	 * @var string
	 */
	public $oldFileName = '';

	/**
	 * 重複時に生成されたファイル名
	 * 
	 * @var string
	 */
	public $newFile = '';

	/**
	 * bannerBannerFileBeforeDelete
	 * 削除する際の重複画像を一時的に別名に変えて保存する
	 * 
	 * @param CakeEvent $event
	 */
	public function bannerBannerFileBeforeDelete(CakeEvent $event) {
		$Model = $event->subject();

		if (!isset($Model->data[$Model->name])) {
			return true;
		}

		// 自分以外のデータ重複をチェック
		$data = $Model->find('first', array(
			'conditions' => array(
				'BannerFile.name' => $Model->data[$Model->name]['name'],
				array('NOT' => array($Model->name .'.id' => $Model->data[$Model->name]['id'])),
			),
			'recursive' => -1
		));
		// 削除時に、削除指定した画像ファイル名でデータ内に重複が存在する時は、その画像ファイルは削除しない
		if ($data) {
			$this->savePath = WWW_ROOT . 'files' . DS . $Model->actsAs[$Model->plugin .'.BcUpload']['saveDir'] . DS;
			if (file_exists($this->savePath . $Model->data[$Model->name]['name'])) {
				$this->oldFileName = $Model->data[$Model->name]['name'];
				$pathinfo = pathinfo($Model->data[$Model->name]['name']);
				$ext = $pathinfo['extension'];
				$fileName = $pathinfo['filename'];
				$this->newFile = $fileName .'_'. mt_rand() .'.'. $ext;
				rename($this->savePath . $Model->data[$Model->name]['name'], $this->savePath .DS. $this->newFile);
				
				$prefixBanner = $Model->actsAs[$Model->plugin .'.BcUpload']['fields']['name']['imagecopy']['banner']['prefix'];
				rename($this->savePath . $prefixBanner . $Model->data[$Model->name]['name'], $this->savePath .DS. $prefixBanner . $this->newFile);
				
				$prefixThumb = $Model->actsAs[$Model->plugin .'.BcUpload']['fields']['name']['imagecopy']['thumb']['prefix'];
				rename($this->savePath . $prefixThumb . $Model->data[$Model->name]['name'], $this->savePath .DS. $prefixThumb . $this->newFile);
			}
		}
		return true;
	}

	/**
	 * bannerBannerFileAfterDelete
	 * 一時的にファイル名を変更して保存したファイルをもとのファイル名に戻す
	 * 
	 * @param CakeEvent $event
	 */
	public function bannerBannerFileAfterDelete(CakeEvent $event) {
		$Model = $event->subject();
		if ($this->newFile) {
			if (file_exists($this->savePath . $this->newFile)) {
				rename($this->savePath . $this->newFile, $this->savePath .DS. $Model->data[$Model->name]['name']);
				
				$prefixBanner = $Model->actsAs[$Model->plugin .'.BcUpload']['fields']['name']['imagecopy']['banner']['prefix'];
				rename($this->savePath . $prefixBanner . $this->newFile, $this->savePath .DS. $prefixBanner . $Model->data[$Model->name]['name']);
				
				$prefixThumb = $Model->actsAs[$Model->plugin .'.BcUpload']['fields']['name']['imagecopy']['thumb']['prefix'];
				rename($this->savePath . $prefixThumb . $this->newFile, $this->savePath .DS. $prefixThumb . $Model->data[$Model->name]['name']);
			}
		}
	}

}
