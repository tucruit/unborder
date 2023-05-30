<?php

/**
 * BurgerEditor <baserCMS plugin>
 *
 * @copyright		Copyright 2013 -, D-ZERO Co.,LTD.
 * @link			https://www.d-zero.co.jp/
 * @package			burger_editor
 * @since			Baser v 3.0.0
 * @license			https://market.basercms.net/files/baser_market_license.pdf
 */
class BurgerEditorModelEventListener extends BcModelEventListener {

	/**
	 * 登録イベント
	 *
	 * @var array
	 */
	public $events = array(
		'Page.beforeValidate',
		'Blog.BlogPost.beforeValidate',
		'afterSave',
		'Permission.afterFind',
	);

	/**
	 * pageBeforeValidate
	 * - BurgerEditorインストール時、DBにMySQLを利用している場合、一部のテーブルのフィールドの型を longtext に変更するため、
	 *   maxByte バリデーションを無効化する
	 *
	 * @param CakeEvent $event
	 * @return boolean
	 */
	public function pageBeforeValidate(CakeEvent $event) {
		$Model = $event->subject();
		$this->unbindMaxByte($Model, array('contents', 'draft'));
		$this->unbindPhpValidSyntax($Model, array('contents', 'draft', 'code'));
		return true;
	}

/**
 * blogBlogPostBeforeValidate
 * - BurgerEditorインストール時、DBにMySQLを利用している場合、一部のテーブルのフィールドの型を longtext に変更するため、
 *   maxByte バリデーションを無効化する
 *
 * @param CakeEvent $event
 * @return boolean
 */
	public function blogBlogPostBeforeValidate(CakeEvent $event)
	{
		$Model = $event->subject();
		$targetColumns = array('content', 'content_draft', 'detail', 'detail_draft');
		$this->unbindMaxByte($Model, $targetColumns);
		return true;
	}

/**
 * maxByte制約除去
 *
 * @param CakeModel $model 対象モデル
 * @param Array(String) $columns 対象カラム
 */
	private function unbindMaxByte($model, $columns) {
		foreach ($columns as $column) {
			// カラム検証
			if (!$this->unbindableMaxByte($model, $column)) {
				continue;
			}
			if (isset($model->validate[$column]) && is_array($model->validate[$column])) {
				foreach ($model->validate[$column] as $key => $validation) {
					if (isset($validation['rule'][0]) && $validation['rule'][0] == "maxByte") {
						unset($model->validate[$column][$key]);
					}
				}
			}
		}
	}

/**
 * phpValidSyntax制約除去
 *
 * @param CakeModel $model 対象モデル
 * @param Array(String) $columns 対象カラム
 */
	private function unbindPhpValidSyntax($model, $columns) {
		foreach ($columns as $column) {
			if (isset($model->validate[$column]) && is_array($model->validate[$column])) {
				foreach ($model->validate[$column] as $key => $validation) {
					if (is_string($validation['rule'])) {
						// 後方互換保つため: バリデーションルールの記述形式が、文字列 or 配列となっているため
						if ($validation['rule'] == "phpValidSyntax") {
							unset($model->validate[$column][$key]);
						}
					} else {
						if (isset($validation['rule'][0]) && $validation['rule'][0] == "phpValidSyntax") {
							unset($model->validate[$column][$key]);
						}
					}
				}
			}
		}
	}

	/**
	 * SQLite PostgreSQL MySQL(longtext|mediumtext)検証
	 * maxByte検証を外すことが可能かチェックする
	 *
	 * @param CakeModel $model
	 * @param String $colmun カラム名
	 * @return boolean チェック結果
	 */
	private function unbindableMaxByte($model, $column) {
		// mysql検証
		if ($model->getDataSource($model->useDbConfig)->config['datasource'] !== "Database/BcMysql") {
			// mysql以外
			return true;

		} else {
			// データ型検証
			$tableName = $model->tablePrefix . $model->useTable;
			$sql = "DESCRIBE {$tableName} {$column};";
			$result = $model->query($sql);
			if (!$result) {
				return false;
			}
			$columnType = strtolower(Hash::get($result, '0.COLUMNS.Type'));
			if ($columnType === 'longtext' || $columnType === 'mediumtext') {
				return true;
			}
		}

		return false;
	}

/**
 * プラグイン保存時に、BurgerEditorが操作されたかチェックし、操作された場合
 * 利用エディタをBurgerからCKEditorに変更する。
 *
 * @param CakeEvent $event
 * @return type
 */
	public function afterSave(CakeEvent $event){
		if (!BcUtil::isAdminSystem()) {
			return;
		}

		$Model = $event->subject();

		if (is_array($Model->data) && !Hash::get($Model->data, 'Plugin')) {
			return;
		}

		if ($Model->data['Plugin']['name'] !== 'BurgerEditor') {
			return;
		}

		$params = Router::getParams();
		if ($params['controller'] !== 'plugins'){
			return;
		}

		if ($params['action'] !== 'admin_ajax_delete'){
			return;
		}

		if (!Hash::get($params, 'pass.0')) {
			return;
		}

		if ($params['pass'][0] !== 'BurgerEditor'){
			return;
		}

		$SiteConfigTable = ClassRegistry::init('SiteConfig');

		$data = $SiteConfigTable->find('first', array(
			'conditions' => array('name' => 'editor'),
			'recursive' => -1
		));

		$data['SiteConfig']['value'] = 'BcCkeditor';

		if (!$SiteConfigTable->save($data, false)) {
			$this->log('BurgerEditorアンインストール時にエディタ設定をデフォルトに設定できませんでした。');
		}
	}

/**
 * ファイル操作系のパーミッションを全て利用可能なように変更
 *
 * @param CakeEvent $event
 */
	public function permissionAfterFind(CakeEvent $event){
		if (Router::getRequest()->params['plugin'] == 'burger_editor') {
			$event->data[0][] = array(
				'Permission' => array(
					'id' => '9999999999',
					'no' => '9999999999',
					'sort' => '',
					'name' => 'BuregeEditor Event',
					'user_group_id' => null,
					'url' => '/admin/burger_editor/*',
					'auth' => true,
					'status' => true,
					'modified' => '2000-01-01 00:00:00',
					'created' => '2000-01-01 00:00:00',
				)
			);
		}

	}
}
