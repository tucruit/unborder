<?php
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');

/**
 * Class InstantPagePaymentsController
 * 画像アップロード用コントローラー
 *
 * @package instant-page
 */
class InstantPageFileUploaderController extends  BcPluginAppController {


	/**
	 * コントローラー名
	 *
	 * @var string
	 */
	public $name = 'InstantPageFileUploader';




	/**
	 * コンポーネント
	 *
	 * @var array
	 */
	public $components = [
		'BcAuth',
		'Cookie',
		'BcAuthConfigure',
		'BcContents'
	];



	/**
	 * アップロードされたファイルの一覧
	 *
	 * @return void
	 */
	public function admin_index() {
		//-----------------------
		// 確認処理
		//-----------------------
		$thisUserId = $this->BcAuth->user('id'); //ログイン中のユーザーID

		//-----------------------
		// このユーザーに紐づく登録データの取得
		//-----------------------
		$imageDatas = $this->InstantPageFileUploader->find('all', ['conditions' => [
			'InstantPageFileUploader.user_id' => $thisUserId
		]]);

		//-----------------------
		// 表示処理
		//-----------------------
		$this->set('imageDatas', $imageDatas);
		$this->set('thisUserId', $thisUserId);
		$this->set('thisUserFileCount', count($imageDatas)); //このユーザーの保有画像数
		$this->pageTitle = '背景画像の一覧';
	}




	/**
	 * ファイルをアップする画面
	 *
	 * @return void
	 */
	public function admin_add()
	{
		//-----------------------
		// 確認処理
		//-----------------------
		$thisUserId = $this->BcAuth->user('id'); //ログイン中のユーザーID
		$thisUserFileCount = $this->InstantPageFileUploader->find('count', ['conditions' => [
			'InstantPageFileUploader.user_id' => $thisUserId
		]]); //このユーザーの保有画像数

		//-----------------------
		// 保存ボタン押下後
		//-----------------------
		if(!empty($this->request->data)){ //確認する
			//バリデーション処理
			// ※ 入力チェックなどは、Model で作れます。

			//画像のアップロード処理
			$insertData = $this->InstantPageFileUploader->savePostImg($this->request->data);
			//データの保存実行
			if($this->InstantPageFileUploader->save($insertData)){
				$message = '背景画像を登録しました。';
				$this->setMessage($message, false);
				$this->redirect(array('action' => 'admin_index'));
			} else {
				$message = '入力エラーです。内容を修正してください。';
				$this->setMessage($message, true);
				$this->redirect(array('action' => 'admin_index'));
			}
		}

		//-----------------------
		// 表示処理
		//-----------------------
		$this->set('thisUserId', $thisUserId);
		$this->set('thisUserFileCount', $thisUserFileCount); //このユーザーの保有画像数
		$this->pageTitle = '背景画像の登録';
	}




	/**
	 * ファイルを削除する
	 *
	 * @param $id int ファイルのid
	 * @return void
	 */
	public function admin_delete($id = null)
	{
		//-----------------------
		// 確認処理
		//-----------------------
		$thisUserId = $this->BcAuth->user('id'); //ログイン中のユーザーID
		if(empty($id)){
			$this->setMessage('idが不正です。', true);
			$this->redirect(array('action' => 'admin_index'));
		}

		//-----------------------
		// 対象データの確認
		//-----------------------
		$imageData = $this->InstantPageFileUploader->findById($id);
		if(empty($imageData)){
			$this->setMessage('その画像は登録されていないか、既に削除されています。', true);
			$this->redirect(array('action' => 'admin_index'));
			exit;
		}
		//所有者の確認
		if($thisUserId != $imageData['InstantPageFileUploader']['user_id']){
			$this->setMessage('この画像に対する操作権限を確認できません。', true);
			$this->redirect(array('action' => 'admin_index'));
			exit;
		}

		//-----------------------
		// 削除実行
		//-----------------------
		//物理削除
		if($this->InstantPageFileUploader->deleteSaveImage($imageData['InstantPageFileUploader']['image_1'])){
			if($this->InstantPageFileUploader->delete($id)){
				$this->setMessage('画像ファイルを削除しました', false);
				$this->redirect(array('action' => 'admin_index'));
				exit;
			} else {
				$this->setMessage('画像データをDBから削除できませんでした。', true);
				$this->redirect(array('action' => 'admin_index'));
				exit;
			}
		} else {
			$this->setMessage('画像ファイルを削除できませんでした。', true);
			$this->redirect(array('action' => 'admin_index'));
			exit;
		}
	}

}
