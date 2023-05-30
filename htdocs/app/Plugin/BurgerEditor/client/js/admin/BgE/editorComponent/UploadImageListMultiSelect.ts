import * as BgE from '../../BgE';
import TypeEditorDialog from '../editorDialog/TypeEditorDialog';
import UploadImageList from './UploadImageList';

/**
 * アップロード画像リスト要素
 */
export default class UploadImageListMultiSelect extends UploadImageList {
	/**
	 * コンストラクタ
	 *
	 * @param el コンポーネントの要素
	 * @param module コンポーネントが使用されるタイプモジュール
	 *
	 */
	constructor(el: HTMLElement, editorDialog: TypeEditorDialog) {
		super(el, editorDialog);
	}

	/**
	 * 初期化が終わったあとに処理を実行
	 */
	public async afterInit() {
		await super.afterInit();
		// 選択されている画像のあるページを最初に移動
		this._changePageTab(1);
		this.editorDialog.$el.find('.bg-upload-file').removeClass('selected');
	}

	protected _fileClick(e: JQuery.MouseEventBase) {
		const $this = $(e.currentTarget);
		// selectedクラス切り替え
		const src = $this.attr('data-org-src') || '';
		const isEmpty = $this.attr('data-bge-empty') === '1';
		this.editorDialog.$el.find('.bg-upload-file').removeClass('selected');
		$this.addClass('selected');
		BgE.componentObserver.notify('bge-file-select', { path: src, isEmpty });
		return false;
	}

	protected _fileDbClick(e: JQuery.MouseEventBase) {
		this._fileClick(e);
		const $this = $(e.currentTarget);
		const src = $this.attr('data-org-src') || '';
		const isEmpty = $this.attr('data-bge-empty') === '1';
		BgE.componentObserver.notify('bge-multi-field-add', { path: src, isEmpty });
		return false;
	}

	/**
	 * ファイルを選択する
	 */
	protected _select(path: string, fileSize: number, isEmpty: boolean) {
		const $editorDialog = this.editorDialog.$el;
		$editorDialog.find('[name="bge-empty"]').val(isEmpty ? '1' : '0');
	}
}
