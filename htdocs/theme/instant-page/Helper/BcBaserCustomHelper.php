<?php
/**
 * BcSampleHelper
 *
 * テーマで利用したヘルパー（表示用関数）を記載したい場合にはここに記載します。
 * クラス名は任意です。Helperフォルダに配置したヘルパーが利用できます。
 *
 * 利用例：<?php $this->BcSample->show() ?>
 */
class BcBaserCustomHelper extends BcBaserHelper {
	/**
	 * meta タグ用のページ説明文を取得する
	 *
	 * @return string meta タグ用の説明文
	 */
	public function getDescription_new()
	{
		$description = $this->_View->get('description');

		if (!empty($description)) {
			return $description;
		}

		// if ($this->isHome()) {

			if (!empty($this->request->params['Site']['description'])) {
				return $this->request->params['Site']['description'];
			}

			if (!empty($this->siteConfig['description'])) {
				return $this->siteConfig['description'];
			}

		// }

		return ''; 
	}
	public function blogWidgetAreaID($blogName)
	{
		$widgetAreaID = 0;
		switch ($blogName) {
			case 'staff_blog':
				$widgetAreaID = 3;
			break;
			default:
				$widgetAreaID = 2;
		}

		return $widgetAreaID; 
	}
}
