<?php
/**
 * DBサーバのデフォルト文字コードチェック
 * - DBサーバのデフォルト文字コード設定がutf8となっているかチェックする
 * 
 */
class DbCharacterSetTest implements CuReleaseCheckTestInterface {

	protected $result	 = false;
	protected $message	 = "DBサーバのデフォルト文字コードチェック";

	public function title() {
		return 'DBサーバのデフォルト文字コードチェック（DB設定のサーバのデフォルト文字コードがutf8となっているかチェック）';
	}

	// テスト実行処理
	public function test() {
		$db = ConnectionManager::getDataSource('default');
		if ($db->config['datasource'] !== 'Database/BcMysql') {
			$this->message = 'DB文字コードチェックはMySQL利用時に可能です。';
			return;
		}
		// DBの設定チェック
		$queryDb = "show variables like '%char%';";
		$dbInfoList	 = Hash::extract($db->query($queryDb), '{n}.VARIABLES');
		if (!$dbInfoList) {
			$this->message = 'DB情報が取得できませんでした。';
			return;
		}
		$checkedDbFailureList = array();
		foreach ($dbInfoList as $dbInfo) {
			if ($dbInfo['Variable_name'] !== 'character_set_database') {
				continue;
			}
			$checkedUtf = true;
			if (strrpos($dbInfo['Value'], 'utf8') === false) {
					$checkedUtf = false;
			}

			$cellesResult[] = array(
				$dbInfo['Variable_name'],
				$dbInfo['Value'],
				$this->getCheckedResultString($checkedUtf),
			);

			if (!$checkedUtf) {
				$checkedDbFailureList[] = true;
			}
		}

		App::uses('BcHtmlHelper', 'View/Helper');
		$BcHtml		 = new BcHtmlHelper(new View());
		$headerList	 = array('設定名', '文字コード', 'utf8チェック');
		$tableHtml	 = $BcHtml->tag('table', $BcHtml->tableHeaders($headerList) . $BcHtml->tableCells($cellesResult));

		if ($checkedDbFailureList) {
			$this->message = 'DBサーバのデフォルト文字コードに問題がございます。DBの設定を確認してください。';
			$this->message .= $tableHtml;
		} else {
			$this->message	 = 'DBサーバのデフォルト文字コードが、utf8になっていることを確認しました。';
			$this->result	 = true;
		}
	}

	// データ取得処理
	public function getResult() {
		return $this->result;
	}

	public function getMessage() {
		return $this->message;
	}

	/**
	 * 真偽値によりOK、NGの文字列を取得する
	 * 
	 * @param boolean $result
	 * @return string
	 */
	private function getCheckedResultString($result) {
		if ($result) {
			return 'OK';
		}
		return 'NG';
	}
}
