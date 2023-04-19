import * as BgE from '../../BgE';
import TypeEditorDialog from '../editorDialog/TypeEditorDialog';
import UploadFileList from './UploadFileList';

/**
 * アップロード画像リスト要素
 */
export default class UploadImageList extends UploadFileList {
	/**
	 * コンストラクタ
	 *
	 * @param el コンポーネントの要素
	 * @param module コンポーネントが使用されるタイプモジュール
	 *
	 */
	constructor(el: HTMLElement, editorDialog: TypeEditorDialog) {
		super(el, editorDialog);
		this.listURL = BgE.config.api ? BgE.config.api.imgList : null;
	}
}
