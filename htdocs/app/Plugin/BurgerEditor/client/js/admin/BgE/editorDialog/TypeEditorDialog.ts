import * as BgE from '../../BgE';
import * as semver from 'semver';
import BurgerType from '../BurgerType';
import EditorDialog from './EditorDialog';
import Util from '../Util';

/**
 * タイプ編集ダイアログ
 */
export default class TypeEditorDialog extends EditorDialog {
	/**
	 * 編集中のタイプ
	 */
	public type?: BurgerType;

	/**
	 * コンストラクタ
	 *
	 * @param el 編集ダイアログの要素
	 *
	 */
	constructor(el: HTMLElement | null) {
		super(el, {
			bgiframe: true,
			autoOpen: false,
			position: semver.gte('1.11.0', jQuery.ui.version, true) ? 'center' : undefined,
			modal: true,
			buttons: {
				// 「キャンセル」ボタンを押すと closeメソッドが発火
				close: 'キャンセル',
				// 「完了」ボタンを押すと completeメソッドが発火
				complete: '完了',
			},
		});
	}

	/**
	 * 編集ダイアログを開く
	 *
	 * @override
	 * @param type 編集するタイプ
	 *
	 */
	public open(type: BurgerType) {
		this.type = type;
		// 編集画面を再設定
		this.$el.append(BgE.getTypeEditorTemplate(this.type.name));
		// 編集画面内の要素をエディタコンポーネント化
		this._createEditorComponents();
		// カスタムイベントを登録
		this._bindCustomEvent();
		// 登録されているデータを抽出
		const data = this.type.export();
		// beforeOpenイベントを発火
		if (this.type.module) {
			this.type.module.beforeOpen(this, this.type, data);
		}
		// 編集画面に現在の値を反映する
		this._import(data);
		// ダイアログを開く
		super.open();
		// openイベントを発火
		if (this.type.module) {
			this.type.module.open(this, this.type);
		}
	}

	/**
	 * 編集完了時に実行する
	 *
	 * TODO: バリデーションはimportの中ですべき？？
	 *
	 */
	public async complete() {
		if (!this.type) {
			return;
		}
		// データ抽出前の処理
		if (this.type.module) {
			this.type.module.beforeExtract(this, this.type);
		}
		// 値のバリデーションチェック
		const errMsg = this.type.validate(this._export());
		if (errMsg) {
			alert(errMsg);
			return;
		}
		// 値をコンテンツへ反映
		await this.type.import(this._export(), true);
		this.close();
	}

	/**
	 * 編集ダイアログを閉じる時の処理
	 *
	 * @override
	 *
	 */
	protected _close() {
		// コンポーネントオブザーバに登録されたイベントをすべて削除してリセットする
		BgE.componentObserver.off();
		// イベント移譲の解除
		this.$el.off();
		// 内容を空にする
		this.$el.empty();
		// 反映したHTMLの保存
		BgE.save();
	}

	/**
	 * 編集ダイアログを生成する時の処理
	 *
	 * TODO: 本当に必要な処理か調査
	 *
	 * @override
	 *
	 */
	protected _create() {
		this.$el
			.closest('.ui-dialog')
			.find('.ui-button:last') // the first button
			.addClass('last');
	}

	/**
	 * 編集画面内の要素をエディタコンポーネント化する
	 */
	private _createEditorComponents() {
		this.$el.find('[data-bge-class]').each((i, el) => {
			const $component = $(el);
			// 要素に紐付けられているクラス名（コストラクタ名）を取得
			const editorComponentSubClassName = `${$component.data('bgeClass')}`;

			const editorComponentSubClassConstructor =
				// @ts-ignore
				BgE.editorComponent[editorComponentSubClassName];

			if (editorComponentSubClassConstructor) {
				// EditorComponentのサブクラスのコンストラクション 要素に機能を付加する
				const editor = new editorComponentSubClassConstructor(el, this);
				editor.afterInit();
			}
		});
	}

	/**
	 * 編集要素内のカスタムイベントを登録する
	 *
	 * HTMLには`<eventName>:<customEventName>`の形式で定義する
	 * `<eventName>`は省略すると"click"とする
	 *
	 * ```html
	 * <button data-bge-event="click:customEvent">click me!</button>
	 * ```
	 *
	 * イベントハンドラ自体は`init.js`の`registerTypeModule`で定義する
	 *
	 * ```javascript
	 *
	 * ```
	 *
	 */
	private _bindCustomEvent() {
		this.$el.find('[data-bge-event]').each((i, el) => {
			const $this = $(el);
			const bgeEvent = `${$this.data('bge-event')}`;
			const bgeEventQuery = bgeEvent.split(':');
			let eventName = bgeEventQuery[0];
			let handlerName = bgeEventQuery[1];
			if (!handlerName) {
				eventName = 'click';
				handlerName = eventName;
			}
			if (!this.type || !this.type.module) {
				return;
			}
			if (handlerName in this.type.module.customFunctions) {
				if ($.isFunction(this.type.module.customFunctions[handlerName])) {
					this.$el.on(eventName, `[data-bge-event="${bgeEvent}"]`, e => {
						if (!this.type || !this.type.module) {
							return;
						}
						return this.type.module.customFunctions[handlerName].call(
							el,
							e,
							this,
							this.type,
							this.type.module,
						);
					});
				}
			}
		});
	}

	/**
	 * 編集画面内のインプット要素に値をエクスポートする
	 *
	 * @param values エクスポートする値
	 *
	 */
	private _import(values: BgE.IBurgerTypeContentData) {
		setForm(this.$el[0], values);
	}

	/**
	 * 編集した値をハッシュで返す
	 */
	private _export() {
		return extractFormData(this.$el[0]);
	}
}

export function setForm(node: Element, values: BgE.IBurgerTypeContentData) {
	const $this = $(node);
	for (const name of Object.keys(values)) {
		const value = values[name];
		const inputSelector = `[name="bge-${name}"]`;
		const viewSelector = `[data-bge*="${name}"]`;
		if (Array.isArray(value)) {
			const $targetEl = $this.find(inputSelector);
			const $listRoot = $targetEl.closest('[data-bge-list]');
			if (!$listRoot.children().length) {
				continue;
			}
			const $listItem = $listRoot.children().first().clone();
			while (value.length > $listRoot.children().length) {
				$listRoot.append($listItem.clone());
			}
			$listRoot.find(inputSelector).each((i, targetEl) => {
				setFormItem(targetEl as HTMLInputElement, value[i] || '');
			});
			$listRoot.find(viewSelector).each((i, targetEl) => {
				BurgerType.datumToElement(name, value[i] || '', targetEl);
			});
		} else {
			for (const targetEl of $this.find(inputSelector).toArray()) {
				setFormItem(targetEl as HTMLInputElement, value);
			}
			for (const targetEl of $this.find(viewSelector).toArray()) {
				BurgerType.datumToElement(name, value, targetEl);
			}
		}
	}
}

function setFormItem(node: HTMLInputElement, value: BgE.IBurgerTypeContentDatum) {
	if (node.type.toLowerCase() === 'checkbox') {
		if (typeof value === 'string') {
			node.checked = /false|0+/i.test(value) ? false : !!value;
		} else {
			node.checked = !!value;
		}
	}
	if (node.type.toLowerCase() === 'radio') {
		let checked: boolean;
		if (node.value) {
			checked = node.value === value;
		} else {
			checked = !!value;
		}
		node.checked = checked;
	} else if (node.placeholder === value) {
		// placeholder と同じ値の場合は空を渡す
		node.value = '';
	} else {
		node.value = `${value}`;
	}
}

export function extractFormData(node: Element) {
	const raws: BgE.IBurgerTypeContentRawMataDatum[] = [];
	const $inputs = $(node).find('[name^=bge-]');
	$inputs.not(':radio').each((i, el) => {
		const $this = $(el);
		const inputType = $this.attr('type') || '';
		const name = ($this.attr('name') || '').replace(/^bge-(.+)/i, '$1');
		let value: BgE.IBurgerTypeContentDatum;
		if (inputType === 'checkbox') {
			value = !!$this.prop('checked');
		} else {
			value = $this.val() as string;
		}
		raws.push({
			key: name,
			datum: value,
			isArray: !!$this.closest('[data-bge-list]').length,
		});
	});
	const extractedNames: string[] = [];
	$inputs.filter(':radio').each((i, el) => {
		const radio = el as HTMLInputElement;
		const name = radio.name.replace(/^bge-(.+)/i, '$1');
		if (extractedNames.includes(name)) {
			return;
		}
		if (radio.checked) {
			raws.push({
				key: name,
				datum: radio.value,
				isArray: !!$(el).closest('[data-bge-list]').length,
			});
			extractedNames.push(name);
		}
	});
	const data = Util.dataOptimize(raws) as BgE.IBurgerTypeContentData;
	return data;
}
