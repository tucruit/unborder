<?php
class GtmController extends AppController {
	public $components = array('Cookie', 'BcAuth', 'BcAuthConfigure');
	// site_config テーブルに保存
	public $uses = array("SiteConfig");
	private $configName = null;

	public function __construct($request = null, $response = null) {
		parent::__construct($request, $response);
		$this->configName = Configure::read('Gtm.keyName');
	}

	public function admin_index(){
		$this->pageTitle = "Google Tag Manager コンテナID設定";

		if (empty($this->request->data)) {
			$data = $this->SiteConfig->findByName($this->configName);
			$data = array(
				'Gtm' => array(
					'key' => (empty($data['SiteConfig']['value'])) ? "" : $data['SiteConfig']['value']
				)
			);
		} else {
			// 更新
			if ($this->SiteConfig->findByName($this->configName)) {
				// サニタイズ
				$db = $this->SiteConfig->getDataSource();
				$value = $db->value($this->request->data['Gtm']['key'], 'string');
				// 更新実行
				$this->SiteConfig->updateAll(
					array('value' => $value),
					array('SiteConfig.name' => $this->configName)
				);
			// 追加
			} else {
				$data = array(
					'name' => $this->configName,
					'value' => $this->request->data['Gtm']['key']
				);
				$this->SiteConfig->save($data, false);
			}
			$data = array(
				'Gtm' => array(
					'key' => $this->request->data['Gtm']['key']
				)
			);
		}
		$this->request->data = $data;
	}
}
