import * as BgE from '../../BgE';
import EditorComponent from './EditorComponent';
import TypeEditorDialog from '../editorDialog/TypeEditorDialog';

export interface IFileUploaderResponse {
	error: boolean;
	data: IUploadFileInfo[];
}

/**
 * アップロードしたファイルの情報
 */
export interface IUploadFileInfo {
	/**
	 * ファイルID
	 */
	fileid: string;

	/**
	 * タイムスタンプ
	 */
	filetime: string;

	/**
	 * ファイル名
	 */
	name: string;

	/**
	 * オリジナルファイル容量
	 */
	original: number;

	/**
	 * ファイル容量
	 */
	size: number;

	/**
	 * サムネイル画像のファイル容量
	 */
	thumb: number;

	/**
	 * ファイルのURL
	 */
	url: string;
}

class UploaderCore {
	private _name: string;
	private _url: string;

	constructor(name: string, url: string) {
		this._name = name;
		this._url = url;
	}

	public send(file: File) {
		const fd = new FormData();
		fd.append(this._name, file, file.name);
		const xhr = new XMLHttpRequest();
		// xhr.addEventListener('progress', this._fileUploadProgress.bind(this), false);
		xhr.addEventListener('load', this._fileUploaded.bind(this), false);
		xhr.addEventListener('error', this._fileUploadError.bind(this), false);
		xhr.open('POST', this._url, true);
		xhr.send(fd);
	}

	// private _fileUploadProgress (e: XMLHttpRequestProgressEvent) {
	// 	console.log(e);
	// }

	private _fileUploaded(e: ProgressEvent) {
		if (!e.target) {
			return;
		}
		const resultData: IFileUploaderResponse = JSON.parse((e.target as XMLHttpRequest).responseText);
		if (resultData.error) {
			// アップロード失敗
			BgE.componentObserver.notify('bge-file-upload-error', 'Server Error');
		} else {
			// アップロード成功
			BgE.componentObserver.notify('bge-file-upload-complete', resultData.data);
		}
	}

	private _fileUploadError(e: ProgressEvent) {
		BgE.componentObserver.notify('bge-file-upload-error', 'XHR Error');
	}
}

/**
 * ファイルアップロード要素
 */
export default class FileUploader extends EditorComponent {
	/**
	 * アップロードをリクエストするAPIのURL
	 */
	public uploadURL: string | null;

	/**
	 * コンテナ
	 */
	public $container: JQuery;

	/**
	 * ドロップエリア
	 */
	public $drop: JQuery;

	/**
	 * コンストラクタ
	 *
	 * @param el コンポーネントの要素
	 * @param module コンポーネントが使用されるタイプモジュール
	 *
	 */
	constructor(el: HTMLElement, editorDialog: TypeEditorDialog) {
		super(el, editorDialog);

		this.$container = $('<div class="bge-file-uploader-container" />');
		this.$drop = $('<div class="bge-file-uploader-drop-area" />');

		this.$el.wrap(this.$drop);
		this.$drop.wrap(this.$container);

		this.$el.on('change', this._change.bind(this));
		this.$drop.on('drop', this._drop.bind(this));
		this.uploadURL = BgE.config.api ? BgE.config.api.fileUpload : null;

		this.editorDialog.$el
			.on('dragenter', e => {
				this.editorDialog.$el.addClass('bge-state-drag');
				e.preventDefault();
			})
			.on('dragover', e => {
				e.preventDefault();
			})
			.on('drop', this._drop.bind(this))
			.on('dragend mouseleave mouseenter', e => {
				this.editorDialog.$el.removeClass('bge-state-drag');
				e.stopPropagation();
			});
	}

	protected fileValidation(file: File) {
		return true;
	}

	/**
	 * アップロードする
	 *
	 * 複数のファイルに対応可能だが、
	 * 受け取る側のシステムが未対応のため、ファイルの数だけリクエストして
	 * ファイルの数だけレスポンスをさばく必要がある
	 *
	 */
	private _upload(files: FileList) {
		if (this.uploadURL) {
			const name = this.$el.attr('name') || `${this.$el.data('post-name')}`;
			const uploader = new UploaderCore(name, this.uploadURL);

			for (const file of Array.from(files)) {
				if (this.fileValidation(file)) {
					uploader.send(file);
				}
			}
		} else {
			// eslint-disable-next-line no-console
			console.warn('アップロードAPIのパスが不明です。');
		}
	}

	private _change(e: JQueryEventObject) {
		const input = e.originalEvent.target as HTMLInputElement;
		const files = input.files;
		if (files) {
			this._upload(files);
		}
	}

	private _drop(e: JQueryEventObject) {
		const event = e.originalEvent as DragEvent;
		if (event.dataTransfer) {
			const files = event.dataTransfer.files;
			this._upload(files);
		}
		this.editorDialog.$el.removeClass('bge-state-drag');
		e.preventDefault();
	}
}
