<?php

class BurgerCustomizeExistTest implements CuReleaseCheckTestInterface {
	protected $result = false;
	protected $message = 'テストが実行されていません';
	
	public function title() {
		return 'バーガーエディタのsetting_customize.phpが利用されているかのテスト';
	}
	
	public function test() {
		
		$errorMessage = [];
		$filename = 'setting_customize.php';
		$defaultfilename = 'setting_customize.php.default';
		
		// プラグインのリストを取り出す
		$Plugins = ClassRegistry::init('Plugins');
		$PluginList = $Plugins->find('list', [
			'fields' => ['name']
		]);
		$bgeDefaultSettingPath = 'Plugin' . DS . 'BurgerEditor' . DS . 'Config' . DS . $defaultfilename;
		$bgeSettingPath = 'Plugin' . DS . 'BurgerEditor' . DS . 'Config' . DS . $filename;
		
		if(in_array('BurgerEditor', $PluginList)){
			//パターン1 app/PluginにBGEが存在する
	        if (file_exists(APP . $bgeDefaultSettingPath)){
	            if (!file_exists(APP . $bgeSettingPath)){
	                $errorMessage[] = 'app/Pluginフォルダ内にバーガーエディタが存在しますが、setting_customize.phpが使用されていません。';
	            }
			}
			//パターン2 lib/Baser/PluginにBGEが存在する
	        if (file_exists(BASER . $bgeDefaultSettingPath)){
	            if (!file_exists(BASER . $bgeSettingPath)){
	                $errorMessage[] = 'lib/Baser/Pluginフォルダ内にバーガーエディタが存在しますが、setting_customize.phpが使用されていません。';
	            }
			}
			//パターン3 theme/{何かのテーマ}/PluginにBGEが存在する
			if (file_exists(BASER_THEMES . Configure::read('BcSite.theme') . DS . $bgeDefaultSettingPath)){
	            if (!file_exists(BASER_THEMES . Configure::read('BcSite.theme') . DS . $bgeSettingPath)){
	                $errorMessage[] = 'theme/' . Configure::read('BcSite.theme') . 'theme/' . Configure::read('BcSite.theme') . '/Pluginフォルダ内にバーガーエディタが存在しますが、setting_customize.phpが使用されていません。';
	            }
			}
		}
		
		if ($errorMessage) {
			$this->result = false;
			$this->message = $errorMessage;
		} elseif (!in_array('BurgerEditor', $PluginList)) {
			$this->result = true;
			$this->message = 'BurgerEditorがインストールされていないため' . $this->title() . 'を完了しました。';
		} else {
			$this->result = true;
			$this->message = $this->title() . 'が正しく完了しました。';
		}
	}
	
	public function getResult() {
		return $this->result;
	}

	public function getMessage() {
		return $this->message;
	}
}