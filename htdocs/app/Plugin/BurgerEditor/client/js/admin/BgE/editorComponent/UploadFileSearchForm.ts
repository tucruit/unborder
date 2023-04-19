import * as BgE from '../../BgE';
import EditorComponent from './EditorComponent';
import TypeEditorDialog from '../editorDialog/TypeEditorDialog';

/**
 * 検索フォーム
 */
export default class UploadFileSearchForm extends EditorComponent {
	/**
	 * コンストラクタ
	 *
	 * @param el コンポーネントの要素
	 * @param module コンポーネントが使用されるタイプモジュール
	 *
	 */
	constructor(el: HTMLElement, editorDialog: TypeEditorDialog) {
		super(el, editorDialog);
		// オブザーバから検索開始を検知して検索をかける
		BgE.componentObserver.on('bge-file-search', this._search, this);
	}

	/**
	 * 検索キーワードを取得して検索をかける
	 *
	 * 検索キーワードとファイルリストアップをオブザーバに通知する
	 *
	 */
	private _search() {
		BgE.componentObserver.notify('bge-file-listup', `${this.$el.val()}`);
	}
}
