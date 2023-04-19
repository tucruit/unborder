import * as BgE from '../../BgE';
import FileUploader from './FileUploader';
import TypeEditorDialog from '../editorDialog/TypeEditorDialog';

/**
 * 画像アップロード要素
 *
 * リクエスト先のAPIのURLが異なる
 *
 */
export default class ImageUploader extends FileUploader {
	/**
	 * コンストラクタ
	 *
	 * @param el コンポーネントの要素
	 * @param module コンポーネントが使用されるタイプモジュール
	 *
	 */
	constructor(el: HTMLElement, editorDialog: TypeEditorDialog) {
		super(el, editorDialog);
		this.uploadURL = BgE.config.api ? BgE.config.api.imgUpload : null;
	}

	protected fileValidation(file: File) {
		let result: boolean;
		switch (file.type.toLocaleLowerCase()) {
			case 'image/jpeg':
			case 'image/gif':
			case 'image/png': {
				result = true;
				break;
			}
			default: {
				const errorMessage = '画像ファイル以外はアップロードできません';
				BgE.componentObserver.notify('bge-file-upload-error', errorMessage);
				result = false;
			}
		}
		return result;
	}
}
