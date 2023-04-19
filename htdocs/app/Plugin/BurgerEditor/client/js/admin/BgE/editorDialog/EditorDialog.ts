// import * as $ from 'jquery';

import * as BgE from '../../BgE';

import Util from '../Util';

export interface IEditorDioalogOption {
	// tslint:disable-next-line:no-any
	[option: string]: any;
	buttons: IEditorDioalogBindingMethodOfButton;
}

export interface IEditorDioalogBindingMethodOfButton {
	[methodName: string]: string | (() => void);
}

/**
 * 編集ダイアログクラス
 *
 */
abstract class EditorDialog {
	/**
	 * 編集ダイアログの要素
	 */
	public $el: JQuery;

	/**
	 * コンストラクタ
	 *
	 *  第二引数の`options`へ渡すハッシュの`buttons`キーは `{ <メソッド名>: <ボタン名> }`のハッシュで渡す
	 *
	 * 例)
	 *
	 * ```javascript
	 * new EditorDialog(el, {
	 * 	buttons: {
	 * 		close: '閉じる',
	 * 		customMethod: '独自ボタン'
	 * 	}
	 * });
	 * ```
	 *
	 * @param el 編集ダイアログ要素
	 * @param options ダイアログ生成の設定 基本的にjQueru UIのオプション `open`,`close`,`create`,`buttons`は特殊な処理を行う
	 *
	 */
	constructor(el: HTMLElement | null, options: IEditorDioalogOption) {
		if (!el) {
			throw new Error('要素の取得に失敗しました。');
		}
		this.$el = $(el);
		const configAndMethods: IEditorDioalogOption = $.extend({}, options);
		configAndMethods.open = this._open.bind(this) as JQueryUI.DialogEvent;
		configAndMethods.close = this._close.bind(this) as JQueryUI.DialogEvent;
		configAndMethods.create = this._create.bind(this) as JQueryUI.DialogEvent;

		configAndMethods.buttons = {};

		for (const methodName of Object.keys(options.buttons)) {
			if (!methodName) {
				continue;
			}
			// ループ内の関数定義のためクロージャで対応
			((_methodName: string, _buttonName: string | (() => void)) => {
				if (!_buttonName) {
					return;
				}
				if (typeof _buttonName !== 'string') {
					return;
				}
				configAndMethods.buttons[_buttonName] = () => {
					// 自信のインスタンスにbuttonsで定義したメソッドが存在していたら登録
					// @ts-ignore
					if (this[_methodName]) {
						// @ts-ignore
						this[_methodName].call(this);
					}
				};
			})(methodName, options.buttons[methodName]);
		}

		this.$el.dialog(configAndMethods as JQueryUI.DialogOptions);
	}

	/**
	 * 編集ダイアログ内の入力内容を空にする
	 */
	public reset() {
		this.$el.find('input, select, textarea').val('');
	}

	/**
	 * 編集ダイアログを開く
	 *
	 * @param args サブクラスのための型定義
	 *
	 */
	// tslint:disable-next-line:no-any
	public open(...args: any[]) {
		// 開く直前に現在のwindowの幅を取得して最適なダイアログ幅を算出する
		this.$el.dialog('option', 'width', Util.getDialogSize(1200, 'width'));
		this.$el.dialog('open');
	}

	/**
	 * 編集ダイアログを閉じる
	 *
	 */
	public close() {
		this.$el.dialog('close');
		BgE.currentContentArea.save();
	}

	/**
	 * 編集ダイアログを生成する
	 *
	 */
	public create() {
		this.$el.dialog('create');
	}

	/**
	 * 編集ダイアログを開いた時の処理
	 *
	 * override前提
	 *
	 */
	protected _open() {
		// void (Abstract)
	}

	/**
	 * 編集ダイアログを閉じた時の処理
	 *
	 * override前提
	 *
	 */
	protected _close() {
		// void (Abstract)
	}

	/**
	 * 編集ダイアログを生成する時の処理
	 *
	 * override前提
	 *
	 */
	protected _create() {
		// void (Abstract)
	}
}

export default EditorDialog;
