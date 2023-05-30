import * as BgE from '../../BgE';
import EditorComponent from './EditorComponent';
import TypeEditorDialog from '../editorDialog/TypeEditorDialog';

/**
 * ファイルアップロードメッセンジャークラス
 */
export default class FileUploadMessenger extends EditorComponent {
	/**
	 * コンストラクタ
	 *
	 * @param el コンポーネントの要素
	 * @param module コンポーネントが使用されるタイプモジュール
	 *
	 */
	constructor(el: HTMLElement, editorDialog: TypeEditorDialog) {
		super(el, editorDialog);
		BgE.componentObserver.on('bge-file-upload-complete', this._uploadComplete, this);
		BgE.componentObserver.on('bge-file-upload-error', this._uploadError, this);
		BgE.componentObserver.on('bge-file-delete-success', this._deleteSuccess, this);
		BgE.componentObserver.on('bge-file-delete-error', this._deleteError, this);
	}

	/**
	 * アップロード完了のメッセージを表示する
	 *
	 * オブザーバから通知を受けて発火
	 *
	 */
	private _uploadComplete() {
		const $err = this.$el;
		$err.html('<p style="color:green;">アップロードが完了しました</p>')
			.delay(500)
			.slideDown(() => {
				$err.delay(2000).slideUp(() => {
					$err.empty();
				});
			});
	}

	/**
	 * アップロードエラーのメッセージを表示する
	 *
	 * オブザーバから通知を受けて発火
	 *
	 * @param err エラーメッセージ
	 *
	 */
	private _uploadError(err: string) {
		const $err: JQuery = this.$el;
		$err.html(`<p style="color:red;">アップロードに失敗しました（${err}）</p>`)
			.delay(500)
			.slideDown(() => {
				$err.delay(2000).slideUp(() => {
					$err.empty();
				});
			});
	}

	/**
	 * ファイル削除完了のメッセージを表示する
	 *
	 * オブザーバから通知を受けて発火
	 *
	 */
	private _deleteSuccess() {
		const $err = this.$el;
		$err.html('<p style="color:green;">ファイルの削除が完了しました</p>')
			.delay(500)
			.slideDown(() => {
				$err.delay(2000).slideUp(() => {
					$err.empty();
				});
			});
	}

	/**
	 * ファイル削除エラーのメッセージを表示する
	 *
	 * オブザーバから通知を受けて発火
	 *
	 * @param err エラーメッセージ
	 *
	 */
	private _deleteError(err: string) {
		const $err: JQuery = this.$el;
		$err.html(`<p style="color:red;">ファイルの削除に失敗しました（${err}）</p>`)
			.delay(500)
			.slideDown(() => {
				$err.delay(2000).slideUp(() => {
					$err.empty();
				});
			});
	}
}
