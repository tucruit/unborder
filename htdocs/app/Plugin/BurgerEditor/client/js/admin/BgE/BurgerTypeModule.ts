import * as BgE from '../BgE';
import BurgerType from './BurgerType';
import TypeEditorDialog from './editorDialog/TypeEditorDialog';

/**
 * タイプモジュール生成オプション
 */
export interface IBurgerTypeModuleConstrucorOption {
	/**
	 * モジュール間で共通で使用するデータ
	 *
	 */
	// tslint:disable-next-line
	data?: { [customProperty: string]: any };

	/**
	 * カスタム関数
	 */
	customFunctions?: IBurgerTypeModuleConstrucorOptionCustomFunctions;

	/**
	 * 編集ダイアログを開いた際にコンテンツのデータが編集ダイアログに反映される前に呼び出される
	 *
	 * @param editorDialog 編集ダイアログ
	 * @param type 対象のタイプ
	 *
	 */
	beforeOpen?(editorDialog: TypeEditorDialog, type: BurgerType): void;

	/**
	 * 編集ダイアログを開いた際に呼び出される
	 *
	 * @param editorDialog 編集ダイアログ
	 * @param type 対象のタイプ
	 *
	 */
	open?(editorDialog: TypeEditorDialog, type: BurgerType): void;

	/**
	 * 編集ダイアログを保存する際に編集要素からデータを取得する前に呼び出される
	 *
	 * @param editorDialog 編集ダイアログ
	 * @param type 対象のタイプ
	 *
	 */
	beforeExtract?(editorDialog: TypeEditorDialog, type: BurgerType): void;

	/**
	 * 編集ダイアログを保存してコンテンツにデータが反映される前に呼び出される
	 *
	 * @param newValues 新しい入力データ
	 * @param type 対象のタイプ
	 *
	 */
	beforeChange?(newValues: BgE.IBurgerTypeContentData, type: BurgerType): Promise<void> | void;

	/**
	 * 編集ダイアログを保存してコンテンツにデータが反映された際に呼び出される
	 *
	 * @param values 新しい入力データ
	 * @param type 対象のタイプ
	 *
	 */
	change?(values: BgE.IBurgerTypeContentData, type: BurgerType): Promise<void> | void;

	/**
	 * マイグレーション
	 *
	 * @param currentVersion 現在のバージョン
	 * @param oldData 古いデータ
	 * @param type 対象のタイプ
	 */
	migrate?(type: BurgerType): BgE.IBurgerTypeContentData;

	/**
	 * マイグレーション
	 *
	 */
	migrateElement?(data: BgE.IBurgerTypeContentData, type: BurgerType): Promise<void> | void;

	/**
	 * 無効になっているかメッセージを返す
	 *
	 * 有効の場合は空文字列を返す
	 */
	isDisable?(type: BurgerType): string;
}

/**
 * タイプモジュールで定義されるカスタム関数
 */
export interface IBurgerTypeModuleConstrucorOptionCustomFunctions {
	[funcName: string]: (
		e: JQuery.Event,
		editorDialog: TypeEditorDialog,
		type: BurgerType,
		module: BurgerTypeModule,
		...args: any[] // tslint:disable-line
	) => any; // tslint:disable-line
}

/**
 * タイプモジュールクラス
 *
 * タイプの生成時や編集時に処理をするモジュールを管理するクラス
 *
 */
export default class BurgerTypeModule {
	/**
	 * カスタム関数
	 */
	public customFunctions: IBurgerTypeModuleConstrucorOptionCustomFunctions = {};

	/**
	 * 編集ダイアログを開いた際にコンテンツのデータが編集ダイアログに反映される前に呼び出される
	 *
	 * @param editorDialog 編集ダイアログ
	 * @param type 対象のタイプ
	 *
	 */
	private _beforeOpen?: (editorDialog: TypeEditorDialog, type: BurgerType, data?: BgE.IBurgerTypeContentData) => void;

	/**
	 * 編集ダイアログを開いた際に呼び出される
	 *
	 * @param editorDialog 編集ダイアログ
	 * @param type 対象のタイプ
	 *
	 */
	private _open?: (editorDialog: TypeEditorDialog, type: BurgerType) => void;

	/**
	 * 編集ダイアログを保存する際に編集要素からデータを取得する前に呼び出される
	 *
	 * @param editorDialog 編集ダイアログ
	 * @param type 対象のタイプ
	 *
	 */
	private _beforeExtract?: (editorDialog: TypeEditorDialog, type: BurgerType) => void;

	/**
	 * 編集ダイアログを保存してコンテンツにデータが反映される前に呼び出される
	 *
	 * @param newValues 新しい入力データ
	 * @param type 対象のタイプ
	 *
	 */
	private _beforeChange?: (newValues: BgE.IBurgerTypeContentData, type: BurgerType) => Promise<void> | void;

	/**
	 * 編集ダイアログを保存してコンテンツにデータが反映された際に呼び出される
	 *
	 * @param values 新しい入力データ
	 * @param type 対象のタイプ
	 *
	 */
	private _change?: (values: BgE.IBurgerTypeContentData, type: BurgerType) => Promise<void> | void;

	/**
	 * マイグレーション
	 *
	 * @param currentVersion 現在のバージョン
	 * @param oldData 古いデータ
	 * @param type 対象のタイプ
	 */
	private _migrate?: (type: BurgerType) => BgE.IBurgerTypeContentData;

	/**
	 * マイグレーション
	 *
	 */
	private _migrateElement?: (data: BgE.IBurgerTypeContentData, type: BurgerType) => Promise<void> | void;

	/**
	 * 無効になっているかメッセージを返す
	 *
	 * 有効の場合は空文字列を返す
	 */
	private _isDisable?: (type: BurgerType) => string;

	/**
	 * カスタムデータ
	 */
	private _data: { [customProperty: string]: any } | null = null; // tslint:disable-line:no-any

	/**
	 * コンストラクタ
	 *
	 * @param option タイプモジュール生成オプション
	 *
	 */
	constructor(option: IBurgerTypeModuleConstrucorOption = {}) {
		if (option.beforeOpen) {
			this._beforeOpen = option.beforeOpen;
		}
		if (option.open) {
			this._open = option.open;
		}
		if (option.beforeExtract) {
			this._beforeExtract = option.beforeExtract;
		}
		if (option.beforeChange) {
			this._beforeChange = option.beforeChange;
		}
		if (option.change) {
			this._change = option.change;
		}
		if (option.migrate) {
			this._migrate = option.migrate;
		}
		if (option.migrateElement) {
			this._migrateElement = option.migrateElement;
		}
		if (option.isDisable) {
			this._isDisable = option.isDisable;
		}
		this.customFunctions = option.customFunctions || {};
		this._data = option.data || null;
	}

	/**
	 * 編集ダイアログを開いた際に呼び出されるコールバックを実行する
	 *
	 * コールバックが登録されていない場合はなにもしない
	 *
	 * @param editorDialog 編集ダイアログ
	 * @param type 対象のタイプ
	 *
	 */
	public open(editorDialog: TypeEditorDialog, type: BurgerType) {
		if (this._open) {
			this._open(editorDialog, type);
		}
	}

	/**
	 * 編集ダイアログを開いた際にコンテンツのデータが編集ダイアログに反映される前に呼び出されるコールバックを実行する
	 *
	 * コールバックが登録されていない場合はなにもしない
	 *
	 * @param editorDialog 編集ダイアログ
	 * @param type 対象のタイプ
	 *
	 */
	public beforeOpen(editorDialog: TypeEditorDialog, type: BurgerType, data?: BgE.IBurgerTypeContentData) {
		if (this._beforeOpen) {
			this._beforeOpen(editorDialog, type, data);
		}
	}

	/**
	 * 編集ダイアログを保存する際に編集要素からデータを取得する前に呼び出される
	 *
	 * コールバックが登録されていない場合はなにもしない
	 *
	 * @param editorDialog 編集ダイアログ
	 * @param type 対象のタイプ
	 *
	 */
	public beforeExtract(editorDialog: TypeEditorDialog, type: BurgerType) {
		if (this._beforeExtract) {
			this._beforeExtract(editorDialog, type);
		}
	}

	/**
	 * 編集ダイアログを保存してコンテンツにデータが反映される前に呼び出されるコールバックを実行する
	 *
	 * コールバックが登録されていない場合はなにもしない
	 *
	 * @param newValues 新しい入力データ
	 * @param type 対象のタイプ
	 *
	 */
	public async beforeChange(newValues: BgE.IBurgerTypeContentData, type: BurgerType) {
		if (this._beforeChange) {
			await this._beforeChange(newValues, type);
		}
	}

	/**
	 * 編集ダイアログを保存してコンテンツにデータが反映された際に呼び出されるコールバックを実行する
	 *
	 * コールバックが登録されていない場合はなにもしない
	 *
	 * @param values 新しい入力データ
	 * @param type 対象のタイプ
	 *
	 */
	public async change(values: BgE.IBurgerTypeContentData, type: BurgerType) {
		if (this._change) {
			await this._change(values, type);
		}
	}

	/**
	 * マイグレーション
	 *
	 * @param currentVersion 現在のバージョン
	 * @param oldData 古いデータ
	 * @param type 対象のタイプ
	 */
	public migrate(type: BurgerType): BgE.IBurgerTypeContentData {
		return this._migrate ? this._migrate(type) : type.export();
	}

	public async migrateElement(data: BgE.IBurgerTypeContentData, type: BurgerType) {
		if (this._migrateElement) {
			await this._migrateElement(data, type);
		}
	}

	/**
	 * カスタム関数を発火させる
	 */
	// tslint:disable:no-any trailing-comma
	public fire(
		custonFunctionName: string,
		editorDialog: TypeEditorDialog,
		type: BurgerType,
		module: BurgerTypeModule,
		...args: any[]
	) {
		const e: JQuery.Event = new $.Event(custonFunctionName);
		if (custonFunctionName in this.customFunctions) {
			return this.customFunctions[custonFunctionName].call(this, e, editorDialog, type, module, ...args);
		}
	}
	// tslint:enable:no-any trailing-comma

	/**
	 * カスタムデータにデータを登録する
	 */
	// tslint:disable-next-line:no-any
	public setData(customProperty: string, value: any) {
		if (this._data) {
			if (customProperty in this._data) {
				this._data[customProperty] = value;
			}
		}
	}

	/**
	 * カスタムデータを参照する
	 */
	public getData(customProperty: string) {
		if (this._data) {
			if (customProperty in this._data) {
				return this._data[customProperty];
			}
		}
	}

	/**
	 * 無効になっているかメッセージを返す
	 *
	 * 有効の場合は空文字列を返す
	 */
	public isDisable(type: BurgerType) {
		if (this._isDisable) {
			return this._isDisable(type);
		}
		return '';
	}
}
