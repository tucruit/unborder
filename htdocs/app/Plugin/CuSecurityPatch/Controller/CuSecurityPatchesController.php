<?php
class CuSecurityPatchesController extends AppController{
    public $uses = [
        'CuSecurityPatch.CuSecurityPatche'
    ];

    public $components = [
		'BcAuth',
		'BcAuthConfigure',
	];

    public function admin_index() {
    	//表示した際に最新の状態にアップデート
    	if (Configure::read('CuSecurityPatch.auto')) {
    		$this->CuSecurityPatche->getSecurityPatchInfo();
    	}

        $default = ['named' => [
			'num' => $this->siteConfigs['admin_list_num'],
			'direction' => 'desc',
		]];

        $this->setViewConditions($this->modelClass, ['default' => $default]);

        $this->paginate = [
            'limit' => $this->passedArgs['num'],
			'order' => 'publish_date IS NULL DESC, publish_date DESC',
        ];

        $patches = $this->paginate('CuSecurityPatche');

        if(!$patches){
            $this->CuSecurityPatche->getSecurityPatchInfo();
            $patches = $this->paginate('CuSecurityPatche');
        }
        $this->set([
            'patches' => $patches
        ]);

        $this->pageTitle = '脆弱性セキュリティパッチ適用状況｜一覧';
    }

    public function admin_update(){
        $this->CuSecurityPatche->getSecurityPatchInfo();
        $this->redirect([
            'action' => 'admin_index'
        ]);
    }

	public function admin_add() {
        $this->pageTitle = '脆弱性セキュリティパッチ適用状況｜追加';
        if(!empty($this->request->data)){
            $this->CuSecurityPatche->save($this->request->data);
			$this->BcMessage->setInfo(__d('baser', '追加しました。'));
            $this->redirect([
                'action' => 'admin_index'
            ]);
        } else {
			$this->request->data = [
				'CuSecurityPatche' => [
					'done' => 0,
					'publish_date' => date('Y-m-d'),
				]
			];
		}
		$this->render('form');
	}

    public function admin_edit($id) {
		$data = $this->CuSecurityPatche->findById(intval($id));

		if (empty($data)) {
			$this->notFound();
		}
        if(!empty($this->request->data)){
			$data['CuSecurityPatche']['id'] = $id;
			$data['CuSecurityPatche']['title'] = $this->request->data['CuSecurityPatche']['title'];
			$data['CuSecurityPatche']['publish_date'] = $this->request->data['CuSecurityPatche']['publish_date'];
			$data['CuSecurityPatche']['version'] = $this->request->data['CuSecurityPatche']['version'];
			$data['CuSecurityPatche']['url'] = $this->request->data['CuSecurityPatche']['url'];
			$data['CuSecurityPatche']['done'] = $this->request->data['CuSecurityPatche']['done'];
			$data['CuSecurityPatche']['comment'] = $this->request->data['CuSecurityPatche']['comment'];
            $this->CuSecurityPatche->save($data);
			$this->BcMessage->setInfo(__d('baser', '更新しました。'));
            $this->redirect([
                'action' => 'admin_index'
            ]);
        }

		$this->set('id', $id);
		$this->request->data = $data;
        $this->pageTitle = '脆弱性セキュリティパッチ適用状況｜編集';
		$this->render('form');
    }

	public function admin_delete($id) {

		$data = $this->CuSecurityPatche->findById(intval($id));
		if (empty($data)) {
			$this->notFound();
		}

		$this->CuSecurityPatche->delete($id);
		$this->BcMessage->setInfo(__d('baser', '削除しました。'));
		$this->redirect(['action' => 'admin_index']);
	}


}
