import * as BgE from '../../BgE';
import EditorComponent from './EditorComponent';
import TypeEditorDialog from '../editorDialog/TypeEditorDialog';

/**
 * 検索ボタン
 */
export default class UploadFileSearchButton extends EditorComponent {
	/**
	 * コンストラクタ
	 *
	 * @param el コンポーネントの要素
	 * @param module コンポーネントが使用されるタイプモジュール
	 *
	 */
	constructor(el: HTMLElement, editorDialog: TypeEditorDialog) {
		super(el, editorDialog);
		this.$el.on('click', this._search.bind(this));
	}

	/**
	 * 検索する
	 *
	 * 検索開始をオブザーバへ通知する
	 *
	 * @param e イベントオブジェクト
	 *
	 */
	private _search(e: JQueryEventObject) {
		BgE.componentObserver.notify('bge-file-search', null);
	}
}
