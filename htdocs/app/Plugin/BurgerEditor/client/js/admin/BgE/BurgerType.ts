import * as BgE from '../BgE';
import BurgerTypeModule from './BurgerTypeModule';
import FrozenPatty from '@burger-editor/frozen-patty';
import combinateSoundMarks from 'jaco/fn/combinateSoundMarks';

const elMap = new WeakMap<Element, BurgerType>();
const btMap = new WeakMap<BurgerType, Element>();

/**
 * BurgerTypeクラス
 */
export default class BurgerType {
	/**
	 * 要素からBurgerTypeインスタンスを返す
	 */
	public static getInstance(el: Element) {
		return elMap.get(el);
	}

	/**
	 * コンテンツ（HTML）をBurgerEditorのタイプで利用できるJSONに変換する
	 *
	 */
	public static contentExport(target: Element): BgE.IBurgerTypeContentData {
		const data = FrozenPatty(target.outerHTML, {
			attr: 'bge',
			typeConvert: true,
			valueFilter: <T>(value: T): T => {
				if (typeof value === 'string') {
					// TODO:
					// @ts-ignore
					return BurgerType.ioFilter(value);
				}
				return value;
			},
		}).toJSON();
		// console.log(data);
		return data;
	}

	/**
	 * フィルター
	 *
	 * データの入力・出力の際に、不正な文字列があれば変換する
	 */
	public static ioFilter(datum: string): string {
		// 濁点半濁点問題
		datum = combinateSoundMarks(datum);

		// WysiwygのHTMLコンテンツ内で
		// data-bgb/data-bgtで開始する属性を削除する
		// "<"で開始するHTML文字列であることを確認する。
		if (/^</.test(datum.trim())) {
			const d = document.createElement('div');
			d.innerHTML = datum;
			const elements = d.querySelectorAll('*');
			for (const element of Array.from(elements)) {
				const attrs = element.attributes;
				for (let i = 0, l = attrs.length; i < l; i++) {
					const attr = attrs.item(i);
					if (attr && /data-bg(?:b|t)(?:-.+)?/i.test(attr.name)) {
						element.removeAttribute(attr.name);
					}
				}
			}
			datum = d.innerHTML;
		}
		return datum;
	}

	public static datumToElement(
		name: keyof BgE.IBurgerTypeContentData,
		datum: BgE.IBurgerTypeContentDatum,
		el: Element,
	) {
		return FrozenPatty.setValue(
			el,
			`${name}`,
			datum,
			'bge',
			<T>(value: T): T => {
				if (typeof value === 'string') {
					// TODO:
					// @ts-ignore
					return BurgerType.ioFilter(value);
				}
				return value;
			},
		);
	}

	/**
	 * タイプ名
	 */
	public name: string;

	/**
	 * モジュール（機能）セット
	 */
	public module: BurgerTypeModule | void;

	/**
	 *
	 */
	private _version: string;

	/**
	 *
	 */
	private _isOld = false;

	/**
	 * コンストラクタ
	 *
	 * @param el 内包するDOM要素
	 * @param value タイプに設定する値（コンテンツ）
	 *
	 */
	constructor(html: Element | string, data?: BgE.IBurgerTypeContentData) {
		let el: Element;
		if (typeof html === 'string') {
			el = $(html).get(0);
		} else {
			el = html;
		}

		// set maps
		elMap.set(el, this);
		btMap.set(this, el);

		// detect name
		let name = el.getAttribute('data-bgt');
		if (!name) {
			name = 'unknown';
			el.setAttribute('data-bgt', name);
		}
		this.name = name;

		// detect version
		let version = el.getAttribute('data-bgt-ver');
		if (!version) {
			version = '0.0.0';
			el.setAttribute('data-bgt-ver', version);
		}
		this._version = version;
		const originVersion =
			BgE.config.types && BgE.config.types[this.name] ? BgE.config.types[this.name].version : '0.0.0';
		this._isOld = BgE.versionCheck.lt(this._version, originVersion);

		// set module
		this.module = BgE.modules[$.camelCase(`-${this.name}`)];

		// set data
		if (data) {
			this.import(data);
		}

		// bind event
		this._bind();
	}

	/**
	 * @deprecated
	 */
	public get $el() {
		// eslint-disable-next-line no-console
		console.warn('BurgerType.prototype.$el is deprecated.');
		return $(this.el);
	}

	/**
	 *
	 */
	public get el() {
		return btMap.get(this)!;
	}

	/**
	 *
	 */
	public get version() {
		return this._version;
	}

	/**
	 *
	 */
	public get isOld() {
		return this._isOld;
	}

	/**
	 * コンテンツの現在の値をハッシュで返す
	 *
	 * @return コンテンツの現在の値
	 *
	 */
	public export() {
		return BurgerType.contentExport(this.el);
	}

	/**
	 * 値をタイプ内のHTMLへ反映する
	 * ダイアログからの編集決定があった場合のみ `isChagned = true`となる。
	 *
	 * @param values 値
	 * @param fromDialogChanges ダイアログからの編集決定からの呼び出し
	 */
	public async import(values: BgE.IBurgerTypeContentData, fromDialogChanges = false) {
		// console.log(values);
		// beforeChangeハンドラを発火
		if (this.module && fromDialogChanges) {
			await this.module.beforeChange(values, this);
		}
		const newHTML = FrozenPatty(this.el.innerHTML, {
			attr: 'bge',
			typeConvert: true,
			valueFilter: <T>(value: T): T => {
				if (typeof value === 'string') {
					// TODO:
					// @ts-ignore
					return BurgerType.ioFilter(value);
				}
				return value;
			},
		})
			.merge(values)
			.toHTML();
		this.el.innerHTML = newHTML;
		// マイグレーション
		if (this._isOld && this.module) {
			await this.module.migrateElement(values, this);
		}
		// changeハンドラを発火
		if (this.module && fromDialogChanges) {
			await this.module.change(values, this);
		}
		this._bind();
	}

	/**
	 * 値の妥当性チェック
	 *
	 * チェックする内容
	 * - yuga.jsと衝突するため、HTMLのクラス名に"btn" "allbtn"を入力させない
	 *
	 */
	public validate(values: BgE.IBurgerTypeContentData) {
		const errors: string[] = [];
		for (const name of Object.keys(values)) {
			const value = values[name];
			// yuga.jsと衝突するため、HTMLのクラス名に"btn" "allbtn"を入力させない
			if ($(`<div>${value}</div>`).find('.btn, .allbtn').length) {
				errors.push(
					'値の中にクラス名"btn"もしくは"allbtn"を含むHTMLがあります。\nクラス名"btn"・"allbtn"は使用できません。',
				);
			}
		}
		return errors.join('\n\n');
	}

	public async upgrade() {
		if (!this._isOld) {
			return;
		}
		const newTmpl = BgE.config.types && BgE.config.types[this.name].tmpl;
		if (!newTmpl) {
			return;
		}
		const newEl = $(newTmpl).get(0);
		const v = newEl.getAttribute('data-bgt-ver')!;
		const typeNameCameled = $.camelCase(`-${this.name}`);
		const typeModule = BgE.modules[typeNameCameled];
		const currentData = typeModule ? typeModule.migrate(this) : this.export();
		const newTmplData = BurgerType.contentExport(newEl);
		const data = Object.assign({}, newTmplData, currentData);
		this.el.innerHTML = newEl.innerHTML;
		this.el.setAttribute('data-bgt-ver', v);
		await this.import(data);
		this._version = v;
		this._isOld = false;
	}

	/**
	 * タイプが無効になっているかメッセージを返す
	 *
	 * 有効の場合は空文字列を返す
	 */
	public isDisable() {
		if (this.module) {
			return this.module.isDisable(this);
		}
		return '';
	}

	/**
	 * イベントの定義
	 *
	 * 新規もしくはinnerHTMLが刷新されたとき呼ばれる
	 */
	private _bind() {
		const $this = $(this.el);

		// タイプをクリックして編集
		$this.off('click');
		$this.on('click', e => {
			this._openEditor();
			return false;
		});
	}

	/**
	 * タイプの編集モードを開く
	 */
	private _openEditor() {
		// 編集画面を表示
		BgE.typeEditorDialog.open(this);
	}
}
