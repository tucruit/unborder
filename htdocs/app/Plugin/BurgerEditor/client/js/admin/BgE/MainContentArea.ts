import ContentArea from './ContentArea';
import DraftContentArea from './DraftContentArea';

/**
 * MainContentAreaクラス
 */
export default class MainContentArea extends ContentArea {
	/**
	 * コンストラクタ
	 *
	 * @param node HTML要素
	 * @param storageNode コンテンツの内容を格納するinput要素
	 */
	constructor(node: HTMLElement | null, storageNode: HTMLInputElement) {
		super(node, storageNode);
		this.show();
	}

	/**
	 * 内容をコピーする
	 *
	 */
	public copyTo(contentArea: DraftContentArea) {
		super.copyTo(contentArea);
	}
}
