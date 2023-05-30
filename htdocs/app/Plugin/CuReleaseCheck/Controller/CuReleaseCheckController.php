<?php
App::uses('BcPluginAppController', 'Controller');
App::uses('CuReleaseCheckTestInterface', 'CuReleaseCheck.Lib');
App::uses('CuReleaseCheckInitInterface', 'CuReleaseCheck.Lib');

class CuReleaseCheckController extends BcPluginAppController {
	public $components = array('BcAuth', 'Cookie', 'BcAuthConfigure');
	public $pageTitle = 'キャッチアップリリースチェック';
	
	public $subMenuElements = array('cu_release_check');
	
	public $crumbs = array(
		array(
			'name' => 'プラグイン管理', 
			'url' => array('admin' => true, 'plugin' => '', 'controller' => 'plugins', 'action' => 'index')),
		array(
			'name' => 'キャッチアップリリースチェック',
			'url'  => array('admin' => true, 'plugin' => 'cu_release_check', 'controller' => 'cu_release_check', 'action' => 'index'),
		)
	);
	
	function beforeFilter() {
		parent::beforeFilter();
	}

	function admin_index() {
		$this->pageTitle = 'チェックリスト';
		$this->set("testList", $this->CuReleaseCheck->getTests());
	}
	
	
	public function admin_check($name = null){
		// テスト実行
		$testList = $this->CuReleaseCheck->getTests();
		if (empty($name) || empty($testList[$name])) {
			$this->redirect(array('action' => 'index'));
		}
		$targetObj = $testList[$name];
		$targetObj->test();
		
		// 結果
		$message = $targetObj->getMessage();
		if (!is_array($message)) $message = array($message);
		if ($targetObj->getResult()) {
			$this->setMessage(h($targetObj->title())."を実行しました");
		} else {
			$this->setMessage(h($targetObj->title())."を実行しました", true);
		}
		$this->set("targetTest", $targetObj);
		$this->set("result", $targetObj->getResult());
		$this->set("messages", $message);
		
		$this->pageTitle = 'チェック';
		$this->set("testList", $this->CuReleaseCheck->getTests());
		$this->render("index");
	}
	
	public function admin_init() {
		$this->crumbs[] = array(
			'name' => '初期設定リスト',
			'url'  => array('plugin' => 'release_check', 'controller' => 'release_check', 'action' => 'init'),
		);
		$this->pageTitle = '初期設定リスト';
		$this->set("initList", $this->CuReleaseCheck->getinits());
	}
	
	public function admin_exec($name = null) {
		// テスト実行
		$initList = $this->CuReleaseCheck->getinits();
		if (empty($name) || empty($initList[$name])) {
			$this->redirect(array('action' => 'index'));
		}
		$targetObj = $initList[$name];
		$targetObj->exec();
		
		// 結果
		$message = $targetObj->getMessage();
		if (!is_array($message)) $message = array($message);
		if ($targetObj->getResult()) {
			$this->setMessage(h($targetObj->title())."を実行しました");
		} else {
			$this->setMessage(h($targetObj->title())."を実行しました", true);
		}
		$this->set("targetinit", $targetObj);
		$this->set("result", $targetObj->getResult());
		$this->set("messages", $message);
		
		$this->set("initList", $this->CuReleaseCheck->getinits());
		$this->render("init");
	}
	
	// public function admin_all_check() {
	// 	// テスト実行
	// 	$messageList = [];
	// 	$result = true;
		
	// 	$testList = $this->CuReleaseCheck->getTests();
	// 	foreach($testList as $name => $targetObj) {
	// 		$targetObj->test();
	// 		if (!$targetObj->getResult()) {
	// 			$result = false;
				
	// 			$message = $targetObj->getMessage();
	// 			if (!is_array($message)) $message = array($message);
	// 			$messageList = $messageList + $message;
	// 		}
			
	// 	}
	// 	$this->setMessage("全てのテストを実行しました");
		
	// 	$this->set("targetTest", $targetObj);
	// 	$this->set("result", $result);
	// 	$this->set("messages", $messageList);
		
	// 	$this->pageTitle = 'チェック';
	// 	$this->set("testList", $testList);
	// 	$this->render("index");
	// }
	
}
