/**
 * BurgerEditorElementクラス
 */
abstract class BurgerEditorElement {
	/**
	 * HTML要素
	 *
	 */
	protected _node: HTMLElement;

	/**
	 * 表示状態
	 *
	 */
	private _visible = false;

	/**
	 * コンストラクタ
	 *
	 * @param node HTML要素
	 */
	constructor(node: HTMLElement | null) {
		if (!node) {
			throw new Error('要素の取得に失敗しました。');
		}
		this._node = node;
	}

	/**
	 * HTML要素の取得
	 *
	 */
	public getNode(): HTMLElement {
		return this._node;
	}

	/**
	 * 表示する
	 */
	public show() {
		this._node.hidden = false;
		this._visible = true;
	}

	/**
	 * 隠す
	 */
	public hide() {
		this._node.hidden = true;
		this._visible = false;
	}

	/**
	 * 表示状態
	 */
	public visible() {
		return this._visible;
	}
}

export default BurgerEditorElement;
