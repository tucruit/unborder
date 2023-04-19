declare namespace BgE {
	export function registerTypeModule<TTypeData extends IBurgerTypeContentData, TCustomData = Record<string, any>>(
		moduleName: string,
		option?: IBurgerTypeModuleConstrucorOption<TTypeData, TCustomData>,
	): void;

	export interface IBurgerTypeModuleConstrucorOption<TTypeData extends IBurgerTypeContentData, TCustomData> {
		/**
		 * モジュール間で共通で使用するデータ
		 */
		data?: TCustomData;

		/**
		 * カスタム関数
		 */
		customFunctions?: IBurgerTypeModuleConstrucorOptionCustomFunctions<TTypeData, TCustomData>;

		/**
		 * 編集ダイアログを開いた際にコンテンツのデータが編集ダイアログに反映される前に呼び出される
		 *
		 * @param editorDialog 編集ダイアログ
		 * @param type 対象のタイプ
		 *
		 */
		beforeOpen?(editorDialog: TypeEditorDialog, type: BurgerType<TTypeData, TCustomData>, data?: TCustomData): void;

		/**
		 * 編集ダイアログを開いた際に呼び出される
		 *
		 * @param editorDialog 編集ダイアログ
		 * @param type 対象のタイプ
		 *
		 */
		open?(editorDialog: TypeEditorDialog, type: BurgerType<TTypeData, TCustomData>): void;

		/**
		 * 編集ダイアログを保存する際に編集要素からデータを取得する前に呼び出される
		 *
		 * @param editorDialog 編集ダイアログ
		 * @param type 対象のタイプ
		 *
		 */
		beforeExtract?(editorDialog: TypeEditorDialog, type: BurgerType<TTypeData, TCustomData>): void;

		/**
		 * 編集ダイアログを保存してコンテンツにデータが反映される前に呼び出される
		 *
		 * @param newValues 新しい入力データ
		 * @param type 対象のタイプ
		 *
		 */
		beforeChange?(newValues: TTypeData, type: BurgerType<TTypeData, TCustomData>): Promise<void> | void;

		/**
		 * 編集ダイアログを保存してコンテンツにデータが反映された際に呼び出される
		 *
		 * @param values 新しい入力データ
		 * @param type 対象のタイプ
		 *
		 */
		change?(values: TTypeData, type: BurgerType<TTypeData, TCustomData>): Promise<void> | void;

		/**
		 * マイグレーション
		 *
		 * @param type 対象のタイプ
		 */
		migrate?(type: BurgerType<TTypeData, TCustomData>): TTypeData;

		/**
		 * マイグレーション
		 *
		 */
		migrateElement?(data: TTypeData, type: BurgerType<TTypeData, TCustomData>): Promise<void> | void;

		/**
		 * 無効になっているかメッセージを返す
		 *
		 * 有効の場合は空文字列を返す
		 */
		isDisable?(type: BurgerType<TTypeData, TCustomData>): string;
	}

	/**
	 * タイプモジュールで定義されるカスタム関数
	 */
	export interface IBurgerTypeModuleConstrucorOptionCustomFunctions<
		TTypeData extends IBurgerTypeContentData,
		TCustomData
	> {
		[funcName: string]: (
			e: JQueryEventObject,
			editorDialog: TypeEditorDialog,
			type: BurgerType<TTypeData, TCustomData>,
			module: BurgerTypeModule<TTypeData, TCustomData>,
		) => any;
	}

	export const config: IBurgerEdintorConfig;

	/**
	 *
	 * use BgE.config.utility.googleMapsApiKey
	 *
	 * @deprecated
	 */
	export const cssListForCKEditor: string;

	/**
	 *
	 * use BgE.config.utility.googleMapsApiKey
	 *
	 * @deprecated
	 */
	export const googleMapsApiKey: string;

	export const versionCheck: {
		/*
		 * v1 < v2
		 */
		lt(v1: string, v2: string, loose?: boolean): boolean;

		/**
		 * v1 > v2
		 */
		gt(v1: string, v2: string, loose?: boolean): boolean;

		/**
		 * v1 <= v2
		 */
		lte(v1: string, v2: string, loose?: boolean): boolean;

		/**
		 * v1 >= v2
		 */
		gte(v1: string, v2: string, loose?: boolean): boolean;
	};

	export class Util {
		/**
		 * 現在のURLのオリジン
		 */
		public static origin: string;

		/**
		 * 改行コードを改行タグに変換
		 *
		 * 未使用
		 *
		 * @param text 対象のテキスト
		 * @return 変換されたテキスト
		 *
		 */
		public static nl2br(text: string): string;

		/**
		 * 改行タグを改行コードに変換
		 *
		 * 未使用
		 *
		 * @param text 対象のテキスト
		 * @return 変換されたテキスト
		 *
		 */
		public static br2nl(html: string): string;

		/**
		 * 数値をバイトサイズ単位にフォーマットする
		 *
		 * @param byteSize 対象の数値
		 * @param digits 小数点の桁数
		 * @param autoFormat SI接頭辞をつけるかどうか
		 * @return フォーマットされた文字列
		 *
		 */
		public static formatByteSize(byteSize: number, digits: number, autoFormat: boolean): string;

		/**
		 * YouTubeの動画URLからIDを抽出する
		 *
		 * 何も抽出できなかった場合 空文字列を返す
		 *
		 * 参考: http://stackoverflow.com/questions/6903823/regex-for-youtube-id
		 * 以下の形式が対応可能
		 * http://www.youtube.com/sandalsResorts#p/c/54B8C800269D7C1B/0/FJUvudQsKCM
		 * http://youtu.be/NLqAF9hrVbY
		 * http://www.youtube.com/embed/NLqAF9hrVbY
		 * https://www.youtube.com/embed/NLqAF9hrVbY
		 * http://www.youtube.com/v/NLqAF9hrVbY?fs=1&hl=en_US
		 * http://www.youtube.com/watch?v=NLqAF9hrVbY
		 * http://www.youtube.com/ytscreeningroom?v=NRHVzbJVx8I
		 * http://www.youtube.com/watch?v=JYArUl0TzhA&feature=featured
		 *
		 * @params idOrUrl YouTubeのURLもしくはID
		 * @return 抽出したID
		 *
		 */
		public static parseYTId(idOrUrl: string): string;

		/**
		 * 現在のウィンドウのサイズから最適なダイアログのサイズを返す
		 *
		 * @param maxSize 最大サイズ
		 * @param vector 測る方向 "width" もしくは "height"
		 * @return 最適なサイズ
		 *
		 */
		public static getDialogSize(maxSize: number, vector: string): number;

		/**
		 * 正しいCSSクラス名かどうかチェックする
		 *
		 * @param className チェック対象
		 * @return 結果
		 */
		public static isValidAsClassName(className: string): boolean;

		/**
		 * Base64変換
		 *
		 * @param str
		 */
		static base64encode(str: string): string;

		/**
		 * Base64変換
		 *
		 * @param str
		 */
		static base64decode(str: string): string;
	}
}

declare class TypeEditorDialog {
	$el: JQuery;
}

declare class BurgerType<TTypeData extends IBurgerTypeContentData, TCustomData> {
	/**
	 * タイプを内包するHTML要素
	 */
	public el: HTMLElement;

	/**
	 * タイプ名
	 */
	public name: string;

	/**
	 * モジュール（機能）セット
	 */
	public module: BurgerTypeModule<TTypeData, TCustomData>;

	public version: string;

	export(): TTypeData;
	import(value: TTypeData): void;
}

declare class BurgerTypeModule<TTypeData extends IBurgerTypeContentData, TCustomData> {
	setData(customProperty: keyof TCustomData, value: TCustomData[keyof TCustomData]): void;
	getData(customProperty: keyof TCustomData): TCustomData[keyof TCustomData];

	fire(
		custonFunctionName: string,
		editorDialog: TypeEditorDialog,
		type: BurgerType<TTypeData, TCustomData>,
		module: BurgerTypeModule<TTypeData, TCustomData>,
		...args: any[]
	): any | undefined;
}

declare type IBurgerTypeContentDatum = string | number | boolean | null;

declare interface IBurgerTypeContentData {
	[key: string]: IBurgerTypeContentDatum | IBurgerTypeContentDatum[] | undefined;
}
/**
 * 設定情報のインターフェイス
 */
declare interface IBurgerEdintorConfig {
	cmsVersion: string;
	api: IBurgerEdintorConfigAPIs;
	utility: IBurgerEdintorConfigUtility;
	blockClassOption?: { [optionName: string]: { [optionValue: string]: string } };
	ckeditorConfig?: { [optionName: string]: Object };
	flag?: IBurgerEditorConfigFlag;
}

declare interface IBurgerEdintorConfigAPIs {
	/**
	 * 画像タイプで取得する画像リストのリクエストURL
	 */
	imgList: string;

	/**
	 * 画像タイプでアップロードする際のリクエストURL
	 */
	imgUpload: string;

	/**
	 * 画像タイプで削除する際のリクエストURL
	 */
	imgDelete: string;

	/**
	 * ファイルアップロードタイプで取得するファイルリストのリクエストURL
	 */
	fileList: string;

	/**
	 * ファイルアップロードタイプでアップロードする際のリクエストURL
	 */
	fileUpload: string;

	/**
	 * ファイルアップロードタイプで削除する際のリクエストURL
	 */
	fileDelete: string;
}

declare interface IBurgerEdintorConfigUtility {
	/**
	 * GoogleMaps APIキー
	 */
	googleMapsApiKey: string;

	/**
	 * CKEditorで使用するCSSファイルのリスト
	 *
	 */
	cssList: string[];

	/**
	 * 本稿のフィールド要素のid
	 */
	mainFieldId: string | null;

	/**
	 * 下書きのフィールド要素のid
	 */
	draftFieldId: string | null;
}

/**
 * 設定情報機能有効フラグのインターフェイス
 */
declare interface IBurgerEditorConfigFlag {
	proposal?: { [flagName: string]: boolean };
}
