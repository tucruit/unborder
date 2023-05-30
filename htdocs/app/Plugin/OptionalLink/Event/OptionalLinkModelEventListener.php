<?php

/**
 * [ModelEventListener] OptionalLink
 *
 * @copyright		Copyright, Catchup, Inc.
 * @link			https://catchup.co.jp
 * @package			OptionalLink
 */
class OptionalLinkModelEventListener extends BcModelEventListener {

	/**
	 * 登録イベント
	 *
	 * @var array
	 */
	public $events = array(
		'Blog.BlogPost.beforeFind',
		'Blog.BlogPost.beforeValidate',
		'Blog.BlogPost.beforeSave',
		'Blog.BlogPost.beforeDelete',
		'Blog.BlogPost.afterSave',
		'Blog.BlogPost.afterDelete',
		'Blog.BlogContent.beforeFind',
		'Blog.BlogContent.afterDelete',
		'SearchIndex.afterSave',
		'Blog.BlogPost.afterFind',
	);

	/**
	 * オプショナルリンク設定モデル
	 *
	 * @var Object
	 */
	private $OptionalLinkConfig = null;

	/**
	 * ブログ記事多重保存の判定
	 *
	 * @var boolean
	 */
	private $throwBlogPost = false;

	/**
	 * Construct
	 *
	 */
	public function __construct() {
		parent::__construct();
		if (ClassRegistry::isKeySet('OptionalLink.OptionalLinkConfig')) {
			$this->OptionalLinkConfig = ClassRegistry::getObject('OptionalLink.OptionalLinkConfig');
		} else {
			$this->OptionalLinkConfig = ClassRegistry::init('OptionalLink.OptionalLinkConfig');
		}
	}

	/**
	 * blogBlogPostBeforeFind
	 *
	 * @param CakeEvent $event
	 */
	public function blogBlogPostBeforeFind(CakeEvent $event) {
		$Model		 = $event->subject();
		// ブログ記事取得の際にオプショナルリンク情報も併せて取得する
		$association = array(
			'OptionalLink' => array(
				'className'	 => 'OptionalLink.OptionalLink',
				'foreignKey' => 'blog_post_id'
			)
		);
		$Model->bindModel(array('hasOne' => $association));
	}

	/**
	 * blogBlogContentBeforeFind
	 *
	 * @param CakeEvent $event
	 * @return array
	 */
	public function blogBlogContentBeforeFind(CakeEvent $event) {
		$Model		 = $event->subject();
		// ブログ設定取得の際にオプショナルリンク設定情報も併せて取得する
		$association = array(
			'OptionalLinkConfig' => array(
				'className'	 => 'OptionalLink.OptionalLinkConfig',
				'foreignKey' => 'blog_content_id'
			)
		);
		$Model->bindModel(array('hasOne' => $association));
	}

	/**
	 * blogBlogPostBeforeValidate
	 *
	 * @param CakeEvent $event
	 * @return boolean
	 */
	public function blogBlogPostBeforeValidate(CakeEvent $event) {
		$Model			 = $event->subject();
		$OptionalLink	 = $this->getOptionalLink($Model);
		// ブログ記事保存の手前で OptionalLink モデルのデータに対して validation を行う
		// TODO saveAll() ではbeforeValidateが効かない？
		$OptionalLink->set($Model->data);
		$result = $OptionalLink->validates();
		$Model->data['OptionalLink'] = $OptionalLink->data['OptionalLink'];
		return $result;
	}

	/**
	 * blogBlogPostBeforeSave
	 * - ブログ記事を削除する場合、関連付くオプショナルリンクのデータを削除するが、
	 *   同一ファイルに対して複数の記事設定がなされているかどうかをチェックし、
	 *   対象ファイルの実体を削除して良いかどうかをチェックしている
	 * - ブログ記事が、URL設定 or ファイルリンクの場合、検索コンテンツに登録しない
	 *
	 * @param CakeEvent $event
	 */
	public function blogBlogPostBeforeSave(CakeEvent $event) {
		$Model = $event->subject();

		if (Hash::get($Model->data, 'OptionalLink.status')) {
			$Model->searchIndexSaving = false;
		} else {
			return true; // オプショナルリンクが有効でないときは何もしない
		}

		$OptionalLink = $this->getOptionalLink($Model);
		if (Hash::get($Model->data, 'OptionalLink.id')) {
			if ($OptionalLink->isFileDelete($Model->data)) {
				$OptionalLink->fileDelete = true;
			} else {
				$OptionalLink->fileDelete = false;
			}
			if ($OptionalLink->hasDuplicateFile($Model->data)) {
				$OptionalLink->hasDuplicateFileDate = true;
			} else {
				$OptionalLink->hasDuplicateFileDate = false;
			}
		}

		if ($this->isInputDataStatusNoLink($Model->data)) {
			$Model->data['OptionalLink']['status'] = '1';
			$Model->data['OptionalLink']['nolink'] = true;
		} else {
			// 保存時の指定がリンクなし指定ではない場合、データとしてリンクなしをtrueにしないため
			$Model->data['OptionalLink']['nolink'] = false;
		}

		if ($OptionalLink->existsLimitedFile($Model->data)) {
			$OptionalLink->Behaviors->load('BcUpload', array('saveDir' => $OptionalLink->actsAs['BcUpload']['saveDir'] . DS . 'limited'));
		}

		return true;
	}

	/**
	 * blogBlogPostAfterSave
	 *
	 * @param CakeEvent $event
	 */
	public function blogBlogPostAfterSave(CakeEvent $event) {
		$Model			 = $event->subject();
		$OptionalLink	 = $this->getOptionalLink($Model);
		// OptionalLinkのデータがない場合は save 処理を実施しない
		if (!isset($Model->data['OptionalLink']) || empty($Model->data['OptionalLink'])) {
			return;
		}

		// ブログ自体がOptionalLinkが無効な場合は処理を中断する
		$blogContentId = $Model->data['BlogPost']['blog_content_id'];

		$optLinkConfigEntity  = $this->OptionalLinkConfig->find('first', [
			'conditions' => ['OptionalLinkConfig.blog_content_id' => $blogContentId],
			'recursive'	 => -1
		]);

		$optLinkConfigStatus = Hash::get($optLinkConfigEntity, 'OptionalLinkConfig.status');
		if(!$optLinkConfigStatus){
			return;
		}


		$saveData = $this->generateSaveData($Model, $Model->id);
		// 2周目では保存処理に渡らないようにしている
		if (!$this->throwBlogPost) {
			if ($OptionalLink->fileDelete && $OptionalLink->hasDuplicateFileDate) {
				$saveData['OptionalLink']['file'] = '';
				$OptionalLink->Behaviors->disable('BcUpload');
			}
			$OptionalLink->clear();
			if (!$OptionalLink->save($saveData)) {
				$this->log(sprintf('ID：%s のオプショナルリンクの保存に失敗しました。', $Model->data['OptionalLink']['id']));
			}

			// ブログ記事コピー後、オプショナルリンクのファイルをコピーする
			$params = Router::getParams();
			if ($params['action'] == 'admin_ajax_copy') {
				$saveData['OptionalLink']['id'] = $OptionalLink->id;
				$OptionalLink->copyFile($saveData);
			}
		}

		// ブログ記事コピー保存時、アイキャッチが入っていると処理が2重に行われるため、1周目で処理通過を判定し、
		// 2周目では保存処理に渡らないようにしている
		$this->throwBlogPost = true;
	}

	/**
	 * blogBlogPostBeforeDelete
	 * - ブログ記事削除前に、そのブログ記事が持っているオプショナルリンクデータがファイルを持っていて、
	 *   ファイルが複数記事に設定されている場合は実ファイルを削除させない
	 * - 同一ファイル名のファイルが複数記事に存在する場合、BcUpload の削除処理（ファイルの削除）を実行させない
	 * - ファイルに公開期間指定がある場合、削除前に保存場所のパスを limited を考慮して書換える
	 * 　 → BcUploadBehavior が、BlogPost側の beforeDelete のタイミングで、BcUploadBehaviorを持つ全てのモデルに対してファイル削除を行うため
	 *
	 * @param CakeEvent $event
	 */
	public function blogBlogPostBeforeDelete(CakeEvent $event) {
		$Model			 = $event->subject();
		$OptionalLink	 = $this->getOptionalLink($Model);
		if (Hash::get($Model->data, 'OptionalLink.id')) {
			// BlogPost::ajax_delete 利用時は、このタイミングで対象ファイルの保存先パスを書き換える
			if (Hash::get($Model->data, 'OptionalLink.publish_begin') || Hash::get($Model->data, 'OptionalLink.publish_end')) {
				if (strpos($Model->Behaviors->BcUpload->savePath['OptionalLink'], DS . 'limited' . DS) === false) {
					$Model->Behaviors->BcUpload->savePath['OptionalLink'] .= 'limited' . DS;
				}
			} else {
				// BlogPost::ajax_batch 利用時、1つ前の記事がlimited保存で、次の記事がlimited保存ではない場合、元に戻しておく必要があるため
				if (strpos($Model->Behaviors->BcUpload->savePath['OptionalLink'], DS . 'limited' . DS) !== false) {
					$Model->Behaviors->BcUpload->savePath['OptionalLink'] = str_replace(DS . 'limited' . DS, DS, $Model->Behaviors->BcUpload->savePath['OptionalLink']);
				}
			}

			if ($OptionalLink->hasDuplicateFile($Model->data)) {
				// ビヘイビアにモデルのコールバックを処理させない
				// unload は OptionalLink モデル側の beforeDelete 処理に影響するため使えない
				// $OptionalLink->Behaviors->unload('BcUpload');
				$OptionalLink->Behaviors->disable('BcUpload');
			}
		}
	}

	/**
	 * blogBlogPostAfterDelete
	 *
	 * @param CakeEvent $event
	 */
	public function blogBlogPostAfterDelete(CakeEvent $event) {
		$Model			 = $event->subject();
		$OptionalLink	 = $this->getOptionalLink($Model);
		// ブログ記事削除時、そのブログ記事が持つOptionalLinkを削除する
		$data			 = $OptionalLink->find('first', array(
			'conditions' => array('OptionalLink.blog_post_id' => $Model->id),
			'recursive'	 => -1
		));
		if ($data) {
			if (!$OptionalLink->delete($data['OptionalLink']['id'])) {
				$this->log('ID:' . $data['OptionalLink']['id'] . 'のOptionalLinkの削除に失敗しました。');
			}
		}
	}

	/**
	 * blogBlogContentAfterDelete
	 *
	 * @param CakeEvent $event
	 */
	public function blogBlogContentAfterDelete(CakeEvent $event) {
		$Model	 = $event->subject();
		// ブログ削除時、そのブログが持つOptionalLink設定を削除する
		$data	 = $this->OptionalLinkConfig->find('first', array(
			'conditions' => array('OptionalLinkConfig.blog_content_id' => $Model->id),
			'recursive'	 => -1
		));
		if ($data) {
			if (!$this->OptionalLinkConfig->delete($data['OptionalLinkConfig']['id'])) {
				$this->log('ID:' . $data['OptionalLinkConfig']['id'] . 'のOptionalLink設定の削除に失敗しました。');
			}
		}
	}

	/**
	 * 保存するデータの生成
	 *
	 * @param Object $Model
	 * @param int $contentId
	 * @return array
	 */
	private function generateSaveData($Model, $contentId = '') {
		$params		 = Router::getParams();
		$data		 = array();
		$modelId	 = $oldModelId	 = null;
		if ($Model->alias == 'BlogPost') {
			$modelId = $contentId;
			$blogContentId = $Model->data['BlogPost']['blog_content_id'];
			if (!empty($params['pass'][1])) {
				$oldModelId = $params['pass'][1];
			}
		}
		$OptionalLink = $this->getOptionalLink($Model);
		if ($contentId) {
			$data = $OptionalLink->find('first', array(
				'conditions' => array('OptionalLink.blog_post_id' => $contentId),
				'recursive'	 => -1
			));
		}

		if (isConsole()) {
			$data['OptionalLink']					 = $Model->data['OptionalLink'];
			$data['OptionalLink']['blog_post_id']	 = $contentId;
		} else {
			switch ($params['action']) {
				case 'admin_add':
					// 追加時
					$data['OptionalLink']					 = $Model->data['OptionalLink'];
					$data['OptionalLink']['blog_post_id']	 = $contentId;
					break;

				case 'admin_edit':
					// 編集時
					$data['OptionalLink']					 = $Model->data['OptionalLink'];
					$data['OptionalLink']['blog_post_id']	 = $contentId;
					break;

				case 'admin_ajax_copy':
					// Ajaxコピー処理時に実行
					// データを手動で調整した場合等、既存データ内に同一の blog_post_id がある場合はそのデータを返す
					if ($data) {
						return $data;
					}
					// ブログコピー保存時にエラーがなければ保存処理を実行
					if (empty($Model->validationErrors)) {
						$_data = array();
						if ($oldModelId) {
							$_data = $OptionalLink->find('first', array(
								'conditions' => array('OptionalLink.blog_post_id' => $oldModelId),
								'recursive'	 => -1
							));
						}
						// もしオプショナルリンク設定の初期データ作成を行ってない事を考慮して判定している
						if ($_data) {
							// コピー元データがある時
							$_data['OptionalLink']['id']			 = null;
							$data['OptionalLink']					 = $_data['OptionalLink'];
							$data['OptionalLink']['blog_post_id']	 = $contentId;
						} else {
							// コピー元データがない時
							$data['OptionalLink']['blog_post_id']	 = $modelId;
							$data['OptionalLink']['blog_content_id'] = $blogContentId;
						}
					}
					break;

				default:
					break;
			}
		}

		return $data;
	}

	/**
	 * OptionalLink モデルが、BlogPost モデルに関連づいていない場合、モデルオブジェクトを取得し直す
	 *
	 * @param Model $Model
	 * @return type Object
	 */
	public function getOptionalLink(Model $Model) {
		if (isset($Model->OptionalLink)) {
			return $Model->OptionalLink;
		} else {
			if (ClassRegistry::isKeySet('OptionalLink.OptionalLink')) {
				return ClassRegistry::getObject('OptionalLink.OptionalLink');
			} else {
				return ClassRegistry::init('OptionalLink.OptionalLink');
			}
		}
	}

	/**
	 * 検索インデックス再構築時にoptionalLinkのステータスを加味させる
	 *
	 * @param CakeEvent $event
	 * @return boolean
	 */
	public function searchIndexAfterSave(CakeEvent $event) {
		$Model	 = $event->subject();
		$data	 = $Model->data;
		$modelId = $Model->id;

		if ($data['SearchIndex']['model'] != 'BlogPost' || empty($modelId)) {
			return true;
		}

		$optLinkModel	 = ClassRegistry::init('OptionalLink.OptionalLink');
		$optLinkData	 = $optLinkModel->find('first', array(
			'conditions' => array(
				'OptionalLink.blog_post_id' => $data['SearchIndex']['model_id']
			),
			'recursive'	 => -1,
			'callbacks'	 => false,
		));

		if ($optLinkData) {
			if ($optLinkData['OptionalLink']['nolink'] || $optLinkData['OptionalLink']['status']) {
				$Model->delete($modelId);
			}
		}

		return true;
	}

	/**
	 * 後方互換性用: 記事編集画面から入力されたオプショナルリンクがリンクナシ指定か判定する
	 *
	 * @param array $data
	 * @return boolean
	 * @deprecated 4.0.0 since 3.0.5 後方互換用。DB構造変更時削除
	 */
	private function isInputDataStatusNoLink($data) {
		if (empty($data['OptionalLink']['status'])) {
			return false;
		}
		if ($data['OptionalLink']['status'] == '3') {
			return true;
		}
		return false;
	}

	/**
	 * 後方互換性用: 記事編集画面に呼び出されたオプショナルリンクがリンクナシ指定か判定する
	 *
	 * @param array $data
	 * @return boolean
	 * @deprecated 4.0.0 since 3.0.5 後方互換用。DB構造変更時削除
	 */
	private function isOutputDataStatusNoLink($data) {
		if (empty($data['OptionalLink']['status'])) {
			return false;
		}
		if ($data['OptionalLink']['status'] == '1') {
			if ($data['OptionalLink']['nolink']) {
				return true;
			}
		}
		return false;
	}

	/**
	 * blogBlogPostAfterFind
	 * 後方互換性用: 記事編集画面に呼び出されたオプショナルリンクがリンクナシの場合、ステータスをリンクナシが選択されているようにする
	 *
	 * @param CakeEvent $event
	 * @return array
	 * @deprecated 4.0.0 since 3.0.5 後方互換用。DB構造変更時削除
	 */
	public function blogBlogPostAfterFind(CakeEvent $event) {
		if (BcUtil::isAdminSystem()) {
			$params = Router::getParams();
			if ($params['action'] !== 'admin_edit') {
				return;
			}
			if (!empty($event->data[0][0]['OptionalLink']['id'])) {
				if ($this->isOutputDataStatusNoLink($event->data[0][0])) {
					$event->data[0][0]['OptionalLink']['status'] = '3';
				}
			}
		}
		return;
	}

}
