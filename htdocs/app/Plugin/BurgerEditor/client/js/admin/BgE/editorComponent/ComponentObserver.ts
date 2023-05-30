import EditorComponent from './EditorComponent';
import { IUploadFileInfo } from './FileUploader';

export interface Actions {
	'bge-file-listup': string;
	'bge-file-select': { path: string; isEmpty: boolean };
	'bge-file-upload-error': string;
	'bge-file-upload-complete': IUploadFileInfo[];
	'bge-file-delete-success': null;
	'bge-file-delete-error': string;
	'bge-multi-field-add': { path: string; isEmpty: boolean };
	'bge-file-search': null;
}

/**
 * アップロード画像リスト要素
 */
export default class ComponentObserver {
	/**
	 * オブザーブ・通知処理を行うオブジェクト
	 *
	 * jQueryのon/off/triggerで代用できるのでそれを使う
	 *
	 */
	private _obj: JQuery;

	/**
	 * コンストラクタ
	 */
	constructor() {
		this._obj = $('body');
	}

	/**
	 * 登録
	 *
	 * @param name イベント名
	 * @param listener 通知時に発火するイベントリスナ
	 * @param context "this"コンテキスト
	 *
	 */
	public on<A extends keyof Actions>(name: A, listener: (payload: Actions[A]) => void, context: EditorComponent) {
		this._obj.on(name, (e, payload: Actions[A]): void => {
			// console.log('on', name, { payload });
			listener.call(context, payload);
		});
	}

	/**
	 * 削除
	 */
	public off() {
		this._obj.off();
	}

	/**
	 * 通知
	 *
	 * @param name イベント名
	 * @param data イベントリスナに渡すデータ
	 *
	 */
	public notify<A extends keyof Actions>(name: A, payload: Actions[A]) {
		// console.log('notify', name, { payload });
		this._obj.trigger(name, [payload]);
	}
}
