<?php

/**
 * Class InstantPagePaymentsController
 * 画像アップロード用コントローラー
 *
 * @package instant-page
 */

App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');

 
class FileUploaderController extends  BcPluginAppController {
 
// nameプロパティにコントローラー名を定義
public $name = 'Fileup1s';

	public function admin_index2() {
sleep(5);
		$path1 = __DIR__ . "./../webroot/";
	        if (move_uploaded_file($this->params['form']['foo_file']['tmp_name'], $path1)){
			echo "移動に成功しました。";
		}else{
			echo "移動に失敗しました。";
		}
		if (is_uploaded_file($this->params['form']['foo_file']['tmp_name'])) {
		   echo "ファイル ". $this->params['form']['foo_file']['tmp_name'] ." のアップロードに成功しました。\n";
		} else {
		   echo "おそらく何らかの攻撃を受けました。";
		   echo "ファイル名 '". $this->params['form']['foo_file']['tmp_name'] . "'.";
		}
	}


	public function admin_index() {
	}
}

--index1----------------------
<form action="index2" enctype="multipart/form-data" method="post">
	<input name="foo_file" type="file" value="">
	<input type="submit" value="送信">
	
</form>
--index2----------------------
<h1>index2</h1>

/*
$img_path=$path1 . "img/image1.jpg";

view内で使うのであれば、
$this->set("img_path",$img_path);

*/