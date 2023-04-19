import * as BgE from '../../BgE';
import EditorComponent from './EditorComponent';
import TypeEditorDialog from '../editorDialog/TypeEditorDialog';

/**
 * アップロード画像リスト要素
 */
export default class MultiFieldSelector extends EditorComponent {
	private _path: string | null = null;
	private _isEmpty = true;

	/**
	 * コンストラクタ
	 *
	 * @param el コンポーネントの要素
	 * @param module コンポーネントが使用されるタイプモジュール
	 *
	 */
	constructor(el: HTMLElement, editorDialog: TypeEditorDialog) {
		super(el, editorDialog);
		this.$el.prop('disabled', true);
		this.$el.on('click', this._onClick.bind(this));
		BgE.componentObserver.on('bge-file-select', this._onSelect, this);
	}

	private _onClick(e: JQueryEventObject) {
		// selectedクラス切り替え
		if (this._path) {
			BgE.componentObserver.notify('bge-multi-field-add', {
				path: this._path,
				isEmpty: this._isEmpty,
			});
		}
		return false;
	}

	private _onSelect({ path, isEmpty }: { path: string; isEmpty: boolean }) {
		this._path = path;
		this._isEmpty = isEmpty;
		if (path && !isEmpty) {
			this.$el.prop('disabled', false);
		} else {
			this.$el.prop('disabled', true);
		}
	}
}
