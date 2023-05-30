import * as BgE from '../../BgE';
import TypeEditorDialog from '../editorDialog/TypeEditorDialog';
import UploadFileDeleter from './UploadFileDeleter';

/**
 * 画像削除要素
 */
export default class UploadImageDeleter extends UploadFileDeleter {
	/**
	 * コンストラクタ
	 *
	 * @param el コンポーネントの要素
	 * @param module コンポーネントが使用されるタイプモジュール
	 *
	 */
	constructor(el: HTMLElement, editorDialog: TypeEditorDialog) {
		super(el, editorDialog);
		this.deleteURL = BgE.config.api ? BgE.config.api.imgDelete : null;
	}
}
