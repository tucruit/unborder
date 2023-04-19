import ContentArea from './ContentArea';
import MainContentArea from './MainContentArea';

/**
 * DraftContentAreaクラス
 */
export default class DraftContentArea extends ContentArea {
	/**
	 * コンストラクタ
	 *
	 * @param node HTML要素
	 * @param storageNode コンテンツの内容を格納するinput要素
	 */
	constructor(node: HTMLElement | null, storageNode: HTMLInputElement) {
		super(node, storageNode);
		this.hide();
	}

	/**
	 * 内容をコピーする
	 *
	 */
	public copyTo(contentArea: MainContentArea) {
		super.copyTo(contentArea);
	}
}
