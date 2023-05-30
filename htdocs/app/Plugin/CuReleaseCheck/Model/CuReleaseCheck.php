<?php
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
App::uses('BcPluginAppModel', 'Model');

class CuReleaseCheck extends BcPluginAppModel {
	public $useTable = false;
	private $addonDir;
	private $testObjectList = [];
	private $initObjectList = [];
	private $checkFileDir = 'Check';
	private $initFileDir = 'Init';

	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
		// Addonフォルダのパスをプロパティにセットする
		$this->addonDir = dirname(dirname(__FILE__)) . DS . 'Addon';
		
		
		// テストリスト収集
		$dirObj = new Folder($this->addonDir . DS . $this->checkFileDir);
		$files = $dirObj->find('.*');
		// テストクラスのインスタンス化
		foreach ($files as $file) {
			include $this->addonDir . DS . $this->checkFileDir . DS . $file;
			$clsName = preg_replace("/.php$/is", "", $file);
			try {
				if (!class_exists($clsName)) continue;
				$fileObj = new $clsName;
				if (in_array('CuReleaseCheckTestInterface', class_implements($fileObj))) {
					$this->testObjectList[$clsName] = $fileObj;
				}
			} catch (Exception $ex) {
				// 不要なクラス呼び出し
				var_dump($ex);
			}
		}
		
		// 初期化リスト収集
		$dirObj = new Folder($this->addonDir . DS . $this->initFileDir);
		$files = $dirObj->find('.*');
		foreach ($files as $file) {
			include $this->addonDir . DS . $this->initFileDir . DS . $file;
			$clsName = preg_replace("/.php$/is", "", $file);
			try {
				if (!class_exists($clsName)) continue;
				$fileObj = new $clsName;
				if (in_array('CuReleaseCheckInitInterface', class_implements($fileObj))) {
					$this->initObjectList[$clsName] = $fileObj;
				}
			} catch (Exception $ex) {
				// 不要なクラス呼び出し
				var_dump($ex);
			}
		}
		
	}
	
	// テストオブジェクトを取得
	public function getTests(){
		return $this->testObjectList;
	}
	
	// テストオブジェクトを取得
	public function getinits(){
		return $this->initObjectList;
	}
	
	
/**
 * 呼び出し可能テストかチェックする
 * @param type $name チェックメソッド
 */
	public function isCallable($name) {
		$methodName = "check" . Inflector::camelize($name);
		return !!method_exists($this, $methodName);
	}
	
	
	
/**
 * 検証を実行する
 * 
 * @param string $name 検証名
 * @return array('success' => bool, 'message' => string)
 */
	public function doCheck($name) {
		$methodName = "check" . Inflector::camelize($name);
		
		if (!$this->isCallable($name)) {
			$message = '検証が定義されていません。<br />';
			$message .= "Model/CuRleaseCheck::" . $methodName . '()を確認してください。';
			return $this->makeResult(false, $message);
		}
		
		$result = $this->$methodName();
		return $result;
	}
	
/**
 * 処理を実行する
 * 
 * @param string $name 検証名
 * @return array('success' => bool, 'message' => string)
 */
	public function doInit($name) {
		$methodName = "check" . Inflector::camelize($name);
		
		if (!$this->isCallable($name)) {
			$message = '検証が定義されていません。<br />';
			$message .= "Model/CuRleaseCheck::" . $methodName . '()を確認してください。';
			return $this->makeResult(false, $message);
		}
		
		$result = $this->$methodName();
		return $result;
	}
	
	
	
/**
 * 下記検証結果用のデータを作成する
 * 
 * @param bool $success 成功：true, 失敗:false
 * @param string メッセージ
 * @return array('success' => bool, 'message' => string|array)
 */
	public function makeResult($success, $message){
		return array(
			'success' => $success,
			'message' => $message,
		);
	}
}
