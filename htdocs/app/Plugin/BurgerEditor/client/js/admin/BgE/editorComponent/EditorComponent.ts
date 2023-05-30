import TypeEditorDialog from '../editorDialog/TypeEditorDialog';

/**
 * エディタコンポーネントクラス
 *
 * タイプ編集ダイアログ内の特殊な役割をする要素
 * コンポーネント間はオブザーバを経由してデータや非同期処理をやりとりする
 *
 * EditorComponentのサブクラスは
 * Addon/に設置してあるタイプのinput.php内のHTMLで
 * data-bge-class属性を設定している場合、
 * そのクラス名のコンポーネントがその要素に対してインスタンスを生成する
 * Typeprototype._createEditorComponents 内の処理でインスタンス化される
 *
 */
abstract class EditorComponent {
	/**
	 * コンポーネントの要素
	 */
	public $el: JQuery;

	/**
	 * 編集ダイアログ
	 */
	public editorDialog: TypeEditorDialog;

	/**
	 * HTML要素
	 */
	private _node: HTMLElement;

	/**
	 * コンストラクタ
	 *
	 * @param node コンポーネントの要素
	 * @param module コンポーネントが使用されるタイプモジュール
	 *
	 */
	constructor(node: HTMLElement, editorDialog: TypeEditorDialog) {
		this._node = node;
		this.$el = $(node);
		this.editorDialog = editorDialog;
	}

	/**
	 * コンストラクタが呼ばれた後に処理する
	 *
	 * override前提
	 *
	 */
	public afterInit(): void {
		// Void
	}
}

export default EditorComponent;
