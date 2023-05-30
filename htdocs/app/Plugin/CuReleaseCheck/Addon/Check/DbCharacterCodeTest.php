<?php

/**
 * DBの文字コードチェック
 * - テーブルがutf8となっているか、utf8mb4となっているかチェックする
 * 
 */
class DbCharacterCodeTest implements CuReleaseCheckTestInterface {

	protected $result	 = false;
	protected $message	 = "DB文字コードチェック";

	public function title() {
		return 'DB文字コードチェック（テーブルがutf8となっているか、utf8mb4となっているかチェック）';
	}

	// テスト実行処理
	public function test() {
		$db = ConnectionManager::getDataSource('default');
		if ($db->config['datasource'] !== 'Database/BcMysql') {
			$this->message = 'DB文字コードチェックはMySQL利用時に可能です。';
			return;
		}

		$queryString	 = 'SHOW TABLE STATUS FROM `' . $db->config['database'] . '` LIKE "' . $db->config['prefix'] . '%"';
		$tableInfoList	 = Hash::extract($db->query($queryString), '{n}.TABLES');
		if (!$tableInfoList) {
			$this->message = 'テーブル情報が取得できませんでした。';
			return;
		}

		$checkedFailureList = array();
		foreach ($tableInfoList as $tableInfo) {
			$checkedUtf	 = $this->checkCollationUtf8($tableInfo);
			$checkedMb4	 = $this->checkCollationUtf8Mb4($tableInfo);

			$cellesResult[] = array(
				$tableInfo['Name'],
				$tableInfo['Collation'],
				$this->getCheckedResultString($checkedUtf),
				$this->getCheckedResultString($checkedMb4),
			);

			if (!$checkedUtf || !$checkedMb4) {
				$checkedFailureList[] = true;
			}
		}

		App::uses('BcHtmlHelper', 'View/Helper');
		$BcHtml		 = new BcHtmlHelper(new View());
		$headerList	 = array('テーブル名', '文字コード', 'utf8チェック', 'utf8mb4チェック');
		$tableHtml	 = $BcHtml->tag('table', $BcHtml->tableHeaders($headerList) . $BcHtml->tableCells($cellesResult));

		if ($checkedFailureList) {
			$this->message = 'NG箇所を確認してください。';
			$this->message .= $tableHtml;
		} else {
			$this->message	 = 'DB内テーブルの文字コードが、utf8になっていることを確認しました。';
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

	/**
	 * テーブルの文字コードがutf8になっているかチェックする
	 * 
	 * @param array $tableInfo
	 * @return boolean
	 */
	private function checkCollationUtf8($tableInfo) {
		$targetList = array('utf8_', 'utf8mb4_');
		foreach ($targetList as $target) {
			if (strpos($tableInfo['Collation'], $target) === 0) {
				return true;
			}
		}
		return false;
	}

	/**
	 * テーブルの文字コードがutf8mb4になっているかチェックする
	 * 
	 * @param array $tableInfo
	 * @return boolean
	 */
	private function checkCollationUtf8Mb4($tableInfo) {
		if (strpos($tableInfo['Collation'], 'utf8mb4_') === 0) {
			return true;
		}
		return false;
	}

}
