import * as BgE from '../BgE';
import BlockOption from './BlockOption';
import BurgerType from './BurgerType';

const bbMap = new WeakMap<BurgerBlock, HTMLElement>();

/**
 * ブロックのデータ
 */
export interface IBurgerBlockData {
	/**
	 * タイプ内のコンテンツ
	 */
	typeData: BgE.IBurgerTypeContentData[];

	/**
	 * オプション
	 */
	options: BlockOption[];

	/**
	 * 独自クラス
	 */
	customClassList: string[];

	/**
	 * グリッド情報
	 */
	gridInfo: IGridInfo;
}

/**
 * グリッド情報
 */
export interface IGridInfo {
	/**
	 * 通常（PC版）の左の比率 x/12
	 */
	normalRatio: number | null;

	/**
	 * SP版の左の比率 x/12
	 */
	spRatio: number | null;

	/**
	 * SP版に対応する
	 */
	spEnabled: boolean;
}

/**
 * 公開期間設定
 */
export interface IScheduledPublishing {
	publishDatetime: string | null;
	unpublishDatetime: string | null;
}

let _lastUID = 0;

/**
 * BurgerBlockクラス
 */
export default class BurgerBlock {
	/**
	 * 使用できない独自クラス
	 */
	public static NG_CUSTOM_CLASS_LIST = ['btn', 'allbtn'];

	/**
	 * オプションをコンテンツのclass属性から抽出する
	 */
	public static extractOptions(el: HTMLElement) {
		const $el = $(el);
		const classAttr = $el.attr('class') || '';
		const classList = classAttr.split(/\s+/);
		const options: BlockOption[] = [];
		// 編集項目の初期値を設定
		classList.forEach(className => {
			if (!className || (className.indexOf('bgb-opt--') !== 0 && className.indexOf('bgb-') === 0)) {
				// classNameが空 もしくは "bgb-opt--"以外の"bgb-"で開始する場合は除外
				return;
			}
			// オプションで使われているクラス名か探してオプションを取得する
			const option = BlockOption.getOption(className);
			if (option) {
				options.push(option);
			}
		});
		return options;
	}

	/**
	 * 独自クラスをコンテンツから抽出する
	 */
	public static extractCustomClass(el: HTMLElement) {
		const $el = $(el);
		const classAttr = $el.attr('class') || '';
		const classList = classAttr.split(/\s+/);
		const customClassList: string[] = [];
		// 編集項目の初期値を設定
		classList.forEach(className => {
			if (!className || (className.indexOf('bgb-opt--') !== 0 && className.indexOf('bgb-') === 0)) {
				// classNameが空 もしくは "bgb-opt--"以外の"bgb-"で開始する場合は除外
				return;
			}

			// オプションで使われているクラス名か探してオプションを取得する
			if (BlockOption.getOption(className)) {
				return;
			}

			// それ以外のクラスは独自クラスとする
			customClassList.push(className);
		});
		return customClassList;
	}

	/**
	 * グリッド情報の取得
	 */
	public static extractGridRatio(el: HTMLElement) {
		const $el = $(el);
		const $changeables = $el.find('[data-bge-grid-changeable]');
		const gridInfo: IGridInfo = {
			spEnabled: false,
			spRatio: 6,
			normalRatio: 6,
		};
		// グリッド変更設定
		[true, false].forEach(isSP => {
			if ($changeables.length) {
				const prefix = isSP ? 'sp-' : '';
				const classAttr = $changeables.attr('class') || '';
				const ratioQuery = classAttr.match(new RegExp(`bgt-${prefix}grid(1[0-2]*|[1-9])`)) || [];
				let ratio = +ratioQuery[1] || 0;
				const enabled = !!ratio;
				if ($changeables.length === 1) {
					/**
					 * [data-bge-grid-changeable] が左右どちらにあるかどうか
					 *
					 * TODO: cssFloatのstyleを取得しているが flexbox などでは破綻する
					 * さらにDOMツリー上に存在していないと上手く取得できない
					 */
					const isAppended = !!$el.closest('body').length;
					if (!isAppended) {
						// 一時的にDOMに追加する
						// スタイルの状態を見るためにcurrentContentArea内でなければならない
						$el.insertAfter(BgE.currentContentArea.containerElement);
					}
					const isRightSide = $changeables.css('cssFloat') === 'right';
					if (isRightSide) {
						// 右にあった場合はグリッドの数を逆にする
						ratio = 12 - ratio;
					}
					if (!isAppended) {
						// DOMから切り離す
						$el.detach();
					}
				}
				if (isSP) {
					gridInfo.spEnabled = enabled;
					if (enabled) {
						gridInfo.spRatio = ratio;
					}
				} else {
					gridInfo.normalRatio = ratio;
				}
			}
		});
		return gridInfo;
	}

	/**
	 * IDの取得
	 */
	public static extractId(el: HTMLElement) {
		return el.id;
	}

	/**
	 * 未知のコンテンツ・ブロック用のラッパーブロックを生成する
	 */
	public static createUnknownBlock(html: string) {
		const block = new BurgerBlock('wysiwyg');
		block.types[0].import({ ckeditor: html });
		block._$el.attr('data-bgb', 'unknown');
		block._name = 'unknown';
		return block;
	}

	/**
	 * 指定のブロックのテンプレートHTMLを取得する
	 */
	private static getTemplate(name: string) {
		const $origin = BgE.$originalBlockElementContainer.find(`[data-bgb="${name}"]`);
		if ($origin.length === 0) {
			throw new Error(`Do not get BurgerBlock template. "${name}" block is not exist.`);
		}
		return $origin.clone()[0];
	}

	/**
	 * タイプリスト
	 */
	public types: BurgerType[] = [];

	/**
	 *
	 * @readonly
	 * @deprecated
	 */
	private get _$el(): JQuery {
		return $(this.node);
	}

	/**
	 * ブロック名
	 */
	private _name: string;

	/**
	 * オプションクラス
	 */
	private _options: BlockOption[] = [];

	/**
	 * 独自クラス
	 */
	private _customClassList: string[] = [];

	/**
	 * ID
	 */
	private _id = '';

	/**
	 * 内部ユニークID
	 */
	private _uid: number;

	/**
	 *
	 */
	private _raf = 0;

	/**
	 *
	 */
	private _timerId = 0;

	/**
	 * グリッド情報
	 */
	private _gridInfo: IGridInfo = {
		normalRatio: null,
		spRatio: null,
		spEnabled: false,
	};

	/**
	 * 公開期間設定
	 */
	private _scheduledPublishing: IScheduledPublishing = {
		publishDatetime: null,
		unpublishDatetime: null,
	};

	/**
	 * コンストラクタ
	 *
	 * 引数にDOM要素を渡した場合、その要素をブロックとしてラップする
	 * 文字列を渡した場合、その名前のブロックを新規生成する
	 *
	 * @param elementOrBlockName ブロック内包する対象のDOM要素 もしくは ブロックの種類名
	 *
	 */
	constructor(elementOrBlockName: HTMLElement | string) {
		this._uid = _lastUID++;

		if (typeof elementOrBlockName === 'string') {
			this._name = elementOrBlockName;
			const el = BurgerBlock.getTemplate(this._name);
			bbMap.set(this, el);
		} else if (elementOrBlockName) {
			const el = elementOrBlockName;
			bbMap.set(this, el);
			this._name = `${this._$el.data('bgb')}`;
		} else {
			throw new Error('Do not create BurgerBlock. A base element is empty.');
		}

		// 内包するタイプをインスタンス化する
		this._$el.find('[data-bgt]').each((i: number, el: HTMLElement): void => {
			const type = new BurgerType(el);
			this.types.push(type);
		});
		// データを抽出する
		this._extractOptions();
		this._extractGridRatio();
		this._extractCustomClass();
		this._extractId();
		this.exportScheduledPublishing();

		// 時限公開設定の有効範囲かどうかを判定するタイマー設定
		this._setTimer();

		// イベント
		this._$el.on('mousemove', () => {
			const isChanged = BgE.editor.setCurrentBlock(this);
			if (isChanged) {
				cancelAnimationFrame(this._raf);
				this._raf = requestAnimationFrame(() => {
					if (!BgE.editor.isSetBlock()) {
						return;
					}
					BgE.currentContentArea.blockMenu.show();
					const rect = this.node.getBoundingClientRect();
					BgE.currentContentArea.blockMenu.setPosition(rect);
				});
			}
		});
		this._$el.on('mouseleave', () => {
			requestAnimationFrame(() => {
				if (!BgE.currentContentArea.blockMenu.isHover) {
					BgE.currentContentArea.blockMenu.hide();
					BgE.editor.clearCurrentBlock();
				}
			});
		});
	}

	/**
	 * ID
	 */
	public get id() {
		return this._id;
	}

	/**
	 * ブロック名
	 */
	public get name() {
		return this._name;
	}

	/**
	 * Node
	 */
	public get node() {
		return bbMap.get(this)!;
	}

	/**
	 * 同じブロックかどうか判定する
	 */
	public is(block: BurgerBlock) {
		return this._uid === block._uid;
	}

	/**
	 * 指定したブロックのコンテンツを自分自身にコピーする
	 *
	 */
	public clone() {
		const originalData: IBurgerBlockData = this._export();
		const newBlock = new BurgerBlock(this._name);
		newBlock._import(originalData);
		return newBlock;
	}

	/**
	 * ブロックを削除する
	 */
	public remove() {
		this._$el.off();
		this._$el.remove();
	}

	/**
	 * ブロックが無効になっているかメッセージを返す
	 *
	 * 有効の場合は空文字列を返す
	 */
	public isDisable() {
		let msg = '';
		for (const type of this.types) {
			msg = type.isDisable();
			if (msg) {
				break;
			}
		}
		return msg;
	}

	/**
	 * オプションのインポート
	 */
	public importOptions(options: BlockOption[]) {
		this._options = options;
		this._addOptionClassToElements();
	}

	/**
	 * オプションのエクスポート
	 */
	public exportOptions() {
		this._extractOptions();
		return this._options;
	}

	/**
	 * 独自クラスのインポート
	 */
	public importCustomClassList(classList: string[]) {
		this._customClassList = classList;
		this._addCustomClassToElements();
	}

	/**
	 * 独自クラスのエクスポート
	 */
	public exportCustomClassList() {
		this._extractCustomClass();
		return this._customClassList;
	}

	/**
	 * IDのインポート
	 */
	public importId(id: string) {
		this._id = id;
		this._setIdToElements();
	}

	/**
	 * IDのエクスポート
	 */
	public exportId() {
		this._extractId();
		return this._id;
	}

	/**
	 * グリッド情報のインポート
	 */
	public importGridInfo(gridInfo: IGridInfo) {
		this._gridInfo = gridInfo;
		this._changeGridForElementsPC();
		this._changeGridForElementsSP();
	}

	/**
	 * グリッド情報のエクスポート
	 */
	public exportGridInfo() {
		this._extractGridRatio();
		return this._gridInfo;
	}

	/**
	 * 公開期間設定のインポート
	 */
	public importScheduledPublishing(scheduledPublishing: IScheduledPublishing) {
		this._scheduledPublishing = scheduledPublishing;
		if (scheduledPublishing.publishDatetime) {
			this._$el.attr('data-bgb-publish-datetime', scheduledPublishing.publishDatetime);
		} else {
			this._$el.removeAttr('data-bgb-publish-datetime');
		}
		if (scheduledPublishing.unpublishDatetime) {
			this._$el.attr('data-bgb-unpublish-datetime', scheduledPublishing.unpublishDatetime);
		} else {
			this._$el.removeAttr('data-bgb-unpublish-datetime');
		}
		this._setTimer();
	}

	/**
	 * 公開期間設定のエクスポート
	 */
	public exportScheduledPublishing() {
		const publishDatetime = this._$el.attr('data-bgb-publish-datetime') || null;
		const unpublishDatetime = this._$el.attr('data-bgb-unpublish-datetime') || null;
		this._scheduledPublishing = {
			publishDatetime,
			unpublishDatetime,
		};
		return this._scheduledPublishing;
	}

	/**
	 * ブロックのデータをJSON文字列からインポート
	 *
	 */
	public importJSONString(jsonString: string) {
		const data = JSON.parse(jsonString) as IBurgerBlockData;
		try {
			this._import(data);
		} catch (error) {
			throw new Error(`ImportError: ${error instanceof Error ? error.message : error}`);
		}
	}

	/**
	 * ブロックのデータをJSON文字列で返す
	 *
	 */
	public toJSONStringify(space?: string | number) {
		return JSON.stringify(this._export(), null, space);
	}

	/**
	 * ブロックのHTMLを文字列で返す
	 */
	public getHTMLStringify() {
		return this.node.outerHTML;
	}

	/**
	 * タイプのデータをインポート
	 *
	 */
	public importTypes(types: (i: number, type: BurgerType) => BgE.IBurgerTypeContentData) {
		this.types.forEach((type, i) => {
			type.import(types(i, type));
		});
	}

	/**
	 * 手前のブロックの存在
	 */
	public existPrev() {
		return !!this._$el.prev().length;
	}

	/**
	 * 次のブロックの存在
	 */
	public existNext() {
		return !!this._$el.next().length;
	}

	/**
	 *
	 */
	public animate(properties: Object, duration: number) {
		return new Promise<void>(resolve => {
			this._$el.animate(properties, duration, resolve);
		});
	}

	/**
	 * ブロックのデータをインポート
	 *
	 */
	private _import(data: IBurgerBlockData) {
		data.typeData.forEach((typeData, i) => {
			this.types[i].import(typeData);
		});
		this.importOptions(data.options);
		this.importCustomClassList(data.customClassList);
		this.importGridInfo(data.gridInfo);
	}

	/**
	 * ブロックのデータをエクスポート
	 *
	 */
	private _export() {
		const data: IBurgerBlockData = {
			typeData: this.types.map(type => type.export()),
			options: this.exportOptions(),
			customClassList: this.exportCustomClassList(),
			gridInfo: this.exportGridInfo(),
		};
		return data;
	}

	/**
	 * オプションをコンテンツのclass属性から抽出する
	 */
	private _extractOptions() {
		this._options = BurgerBlock.extractOptions(this.node);
	}

	/**
	 * 独自クラスをコンテンツから抽出する
	 */
	private _extractCustomClass() {
		this._customClassList = BurgerBlock.extractCustomClass(this.node);
	}

	/**
	 * IDをコンテンツから抽出する
	 */
	private _extractId() {
		this._id = BurgerBlock.extractId(this.node);
	}

	/**
	 * オプションをコンテンツから削除する
	 */
	private _removeOptionClassFromElements() {
		const classAttr = this._$el.attr('class') || '';
		const classList = classAttr.split(/\s+/);
		const useClassList = classList.filter(className => className.indexOf('bgb-opt--') !== 0);
		this._$el.attr('class', useClassList.join(' '));
	}

	/**
	 * オプションをコンテンツに反映
	 */
	private _addOptionClassToElements() {
		this._removeOptionClassFromElements();
		const useClassList: string[] = [];
		this._options.forEach(option => {
			if (option.currentClass) {
				useClassList.push(option.currentClass.className);
			}
		});
		this._$el.addClass(useClassList.join(' '));
	}

	/**
	 * 独自クラスをコンテンツから削除する
	 */
	private _removeCustomClassFromElements() {
		const classAttr = this._$el.attr('class') || '';
		const classList = classAttr.split(/\s+/);
		const useClassList = classList.filter(className => className.indexOf('bgb-') === 0);
		this._$el.attr('class', useClassList.join(' '));
	}

	/**
	 * 独自クラスをコンテンツに反映
	 */
	private _addCustomClassToElements() {
		this._removeCustomClassFromElements();
		const useClassList: string[] = [];
		this._customClassList.forEach(className => {
			if (className && BurgerBlock.NG_CUSTOM_CLASS_LIST.indexOf(className) === -1) {
				useClassList.push(className);
			} else {
				alert(`"${className}" というクラス名は使用できません。`);
			}
		});
		this._$el.addClass(useClassList.join(' '));
	}

	/**
	 * IDをコンテンツに反映
	 */
	private _setIdToElements() {
		this._$el.attr('id', this._id);
	}

	/**
	 * グリッド情報の取得
	 */
	private _extractGridRatio() {
		this._gridInfo = BurgerBlock.extractGridRatio(this.node);
	}

	/**
	 * グリッド情報をコンテンツに反映
	 */
	private _changeGridForElements(isSP: boolean) {
		const prefix = isSP ? 'sp-' : '';
		const $changeables = this._$el.find('[data-bge-grid-changeable]');
		const $L = $changeables.first();
		const $R = $changeables.last();
		const rxGridClass = new RegExp(`(bgt-${prefix}grid(?:1[0-2]*|[1-9]))`, 'g');
		const gridClassesL = ($L.attr('class') || '').match(rxGridClass) || [];
		const gridClassesR = ($R.attr('class') || '').match(rxGridClass) || [];
		// グリッド比設定 - 一旦削除して設定
		$L.removeClass(gridClassesL.join(' '));
		$R.removeClass(gridClassesR.join(' '));

		// SPが無効の場合終了
		if (isSP && !this._gridInfo.spEnabled) {
			return;
		}

		const ratioL = isSP ? this._gridInfo.spRatio : this._gridInfo.normalRatio;
		if (ratioL) {
			const ratioR = 12 - ratioL;
			if ($changeables.length === 1) {
				const ratio = $L.css('cssFloat') === 'left' ? ratioL : ratioR;
				$L.addClass(`bgt-${prefix}grid${ratio}`);
			} else if ($changeables.length > 1) {
				$L.addClass(`bgt-${prefix}grid${ratioL}`);
				$R.addClass(`bgt-${prefix}grid${ratioR}`);
			}
		}
	}

	/**
	 * グリッド情報をコンテンツに反映
	 */
	private _changeGridForElementsPC() {
		this._changeGridForElements(false);
	}

	/**
	 * グリッド情報をコンテンツに反映
	 */
	private _changeGridForElementsSP() {
		this._changeGridForElements(true);
	}

	/**
	 *
	 */
	private _detectTimeRange() {
		const attrName = 'data-bgb-publish-datetime-range';
		const start = this._scheduledPublishing.publishDatetime;
		const end = this._scheduledPublishing.unpublishDatetime;
		if (start == null && end == null) {
			this._$el.removeAttr(attrName);
			return;
		}
		let isOutOfRange = false;
		const nowTimestamp = new Date().valueOf();

		if (start) {
			const startTimestamp = new Date(start).valueOf();
			if (nowTimestamp < startTimestamp) {
				isOutOfRange = true;
			}
		}

		if (end) {
			const endTimestamp = new Date(end).valueOf();
			if (endTimestamp < nowTimestamp) {
				isOutOfRange = true;
			}
		}

		this._$el.attr(attrName, `${!isOutOfRange}`);
	}

	/**
	 * 時限公開設定の有効範囲かどうかを判定するタイマー設定
	 */
	private _setTimer() {
		this._clearTimer();
		this._detectTimeRange();
		this._timerId = window.setInterval(this._detectTimeRange.bind(this), 1000 * 30);
	}

	private _clearTimer() {
		window.clearInterval(this._timerId);
	}
}
