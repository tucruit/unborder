import * as BgE from '../../BgE';
import EditorComponent from './EditorComponent';
import TypeEditorDialog from '../editorDialog/TypeEditorDialog';

/**
 * ファイル削除要素
 */
export default class UploadFileDeleter extends EditorComponent {
	/**
	 * ファイル削除をリクエストするAPIのURL
	 */
	public deleteURL: string | null;

	/**
	 * 削除するファイルのパス
	 */
	private _deletePath: string | null = null;

	/**
	 * コンストラクタ
	 *
	 * @param el コンポーネントの要素
	 * @param module コンポーネントが使用されるタイプモジュール
	 *
	 */
	constructor(el: HTMLElement, editorDialog: TypeEditorDialog) {
		super(el, editorDialog);
		this.$el.on('click', this._deletes.bind(this));
		this.deleteURL = BgE.config.api ? BgE.config.api.fileDelete : null;
		BgE.componentObserver.on('bge-file-select', this._onSelect, this);
	}

	private _onSelect({ path }: { path: string }) {
		this._deletePath = path || null;
	}

	/**
	 * ファイルを削除する
	 *
	 * 削除結果はオブザーバへ通知される
	 *
	 * TODO: 他の要素に影響を与えているので修正したい $editorArea
	 * TODO: サーバ側のControllerで削除失敗のレスポンスが検知できない
	 *
	 * @param e イベントオブジェクト
	 *
	 */
	private _deletes(e: JQueryEventObject) {
		if (this.deleteURL) {
			const $editorArea = this.$el.parents('#ContentsEditArea');
			const deleteFileUrl = this._deletePath;

			if (!deleteFileUrl) {
				alert('ファイルが選択されていません。');
				return;
			}
			if (
				!confirm(
					'ファイルを削除します。よろしいですか?\n※ 記事にて利用中のファイルがあった場合 表示されなくなります',
				)
			) {
				return;
			}
			// TODO: タイムアウト処理を書く
			$.ajax(this.deleteURL, {
				cache: false,
				type: 'POST',
				data: {
					file: deleteFileUrl,
				},
				success: (res: string) => {
					switch (res) {
						// 成功
						case '1': {
							// TODO: UpdateFileListですべき
							$editorArea.find('div.selected').remove();
							// TODO: コンポーネント化してそこで削除するべき
							$editorArea.find(`[name=bge-path][value="${deleteFileUrl}"]`).val('');
							// 通知
							BgE.componentObserver.notify('bge-file-delete-success', null);
							break;
						}
						// TODO: 成功以外の場合のレスポンスが不明
						default: {
							// 通知
							BgE.componentObserver.notify('bge-file-delete-error', '');
						}
					}
				},
			});
		} else {
			// eslint-disable-next-line no-console
			console.warn('削除APIのパスが不明です。');
		}
	}
}
