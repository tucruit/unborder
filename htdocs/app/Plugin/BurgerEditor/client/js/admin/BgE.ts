import * as semver from 'semver';
import { default as BurgerTypeModule, IBurgerTypeModuleConstrucorOption } from './BgE/BurgerTypeModule';
import BlockConfigDialog from './BgE/editorDialog/BlockConfigDialog';
import BlockListDialog from './BgE/editorDialog/BlockListDialog';
import BurgerEditor from './BurgerEditor';
import ComponentObserver from './BgE/editorComponent/ComponentObserver';
import ContentArea from './BgE/ContentArea';
import DraftContentArea from './BgE/DraftContentArea';
import FileUploadMessenger from './BgE/editorComponent/FileUploadMessenger';
import FileUploader from './BgE/editorComponent/FileUploader';
import ImageUploader from './BgE/editorComponent/ImageUploader';
import InsertionPoint from './BgE/InsertionPoint';
import MainContentArea from './BgE/MainContentArea';
import MultiFieldSelection from './BgE/editorComponent/MultiFieldSelection';
import MultiFieldSelector from './BgE/editorComponent/MultiFieldSelector';
import TypeEditorDialog from './BgE/editorDialog/TypeEditorDialog';
import UploadFileDeleter from './BgE/editorComponent/UploadFileDeleter';
import UploadFileList from './BgE/editorComponent/UploadFileList';
import UploadFileSearchButton from './BgE/editorComponent/UploadFileSearchButton';
import UploadFileSearchForm from './BgE/editorComponent/UploadFileSearchForm';
import UploadImageDeleter from './BgE/editorComponent/UploadImageDeleter';
import UploadImageList from './BgE/editorComponent/UploadImageList';
import UploadImageListMultiSelect from './BgE/editorComponent/UploadImageListMultiSelect';

import UtilClass from './BgE/Util';

// tslint:disable-next-line
export const Util = UtilClass;

/**
 * バージョン
 */
export const version = '2.25.1';

/**
 * タイプ内のコンテンツデータの値
 */
export type IBurgerTypeContentDatum = string | number | boolean | null | undefined;

/**
 * タイプ内のコンテンツデータ
 */
export interface IBurgerTypeContentData {
	[key: string]: IBurgerTypeContentDatum | IBurgerTypeContentDatum[];
}

/**
 * 正規化する前のタイプ内のコンテンツデータ
 */
export interface IBurgerTypeContentRawMataDatum {
	key: keyof IBurgerTypeContentData;
	datum: IBurgerTypeContentDatum;
	isArray: boolean;
}

/**
 * 設定情報のインターフェイス
 */
export interface IBurgerEdintorConfig {
	cmsVersion?: string;
	api?: IBurgerEdintorConfigAPIs;
	utility?: IBurgerEdintorConfigUtility;
	blockClassOption?: { [optionName: string]: { [optionValue: string]: string } };
	ckeditorConfig?: { [optionName: string]: Object };

	/**
	 * タイプのバージョンリスト
	 */
	types?: {
		[typeName: string]: {
			version: string;
			tmpl: string;
		};
	};
	flag?: IBurgerEditorConfigFlag;

	/**
	 * `Config/setting.php`ファイルの`"Bge"`の情報
	 */
	setting?: {
		fileShare: boolean;
		autoWrapper: boolean;
		defaultImagePopup: boolean;
		publishTimer: boolean;
		noResizeExtension: string[];
		uploadImageSize: {
			imgSizeWidthMax: number;
			imgSizeWidthDefault: number;
			imgSizeWidthSmall: number;
		};
		uploadImageDataSize: number;
	};
}

export interface IBurgerEdintorConfigAPIs {
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

export interface IBurgerEdintorConfigUtility {
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
export interface IBurgerEditorConfigFlag {
	proposal?: { [flagName: string]: boolean };
}

/**
 * エディタの状態
 */
export interface IEditorStatus {
	/**
	 * 処理中フラグ
	 *
	 * アニメーション中などに他の操作ができないようにするためのフラグ
	 *
	 */
	isProcessed: boolean;
}

/**
 * ブロックコピーの機能のHTMLを保存するローカルストレージキー
 */
export const STORAGE_KEY_OF_COPIED_BLOCK = 'bge-copied-block';

/**
 * ブロックのIDに付加するプレフィックス
 *
 */
export const BLOCK_ID_PREFIX = 'bge-';

/**
 * コアオブジェクト
 */
export const editor = new BurgerEditor();

/**
 * 設定情報
 */
export const config: IBurgerEdintorConfig = {};

/**
 * エディタの状態
 */
export const editorStatus: IEditorStatus = {
	isProcessed: false,
};

export let currentContentArea: ContentArea;

/**
 * 追加するブロックを選択するダイアログ
 */
export let blockListDialog: BlockListDialog;

/**
 * ブロック編集ダイアログ
 */
export let blockConfigDialog: BlockConfigDialog;

/**
 * タイプ編集ダイアログ
 */
export let typeEditorDialog: TypeEditorDialog;

/**
 * コンポーネントオブザーバ
 */
export let componentObserver: ComponentObserver;

/**
 * タイプモジュールコレクション
 *
 */
export const modules: { [typeName: string]: BurgerTypeModule } = {};

/**
 * GoogleMaps APIキー
 *
 * @deprecated
 */
export let googleMapsApiKey: string;

/**
 * CKEditorで使用するCSSファイルのリスト
 *
 * カンマ区切りの文字列
 *
 * @deprecated
 *
 */
export let cssListForCKEditor: string;

/**
 * ブロックの原本が格納されている要素
 *
 * 中のブロック要素はcloneされてブロックとして追加される
 *
 */
export let $originalBlockElementContainer: JQuery;

/**
 * ブロックを追加する対象の要素
 */
export let insertionPoint: InsertionPoint;

/**
 * コンポーネントクラス
 */
export const editorComponent = {
	FileUploader,
	FileUploadMessenger,
	ImageUploader,
	MultiFieldSelection,
	MultiFieldSelector,
	UploadFileDeleter,
	UploadFileList,
	UploadFileSearchButton,
	UploadFileSearchForm,
	UploadImageDeleter,
	UploadImageList,
	UploadImageListMultiSelect,
};

/**
 * タイプ編集画面の原本が格納されている要素
 *
 * cloneされて編集画面として描画される
 *
 */
let $originalTypeEditorElementContainer: JQuery;

/**
 * 管理画面上のコンテンツの出力結果および編集可能領域（本稿）
 */
let mainContentArea: MainContentArea;

/**
 * 管理画面上のコンテンツの出力結果および編集可能領域（下書き）
 *
 * 下書きがない場合もある
 */
let draftContentArea: DraftContentArea | null = null;

/**
 * BurgerEditor初期化
 */
export function init() {
	getSettings();
	mainToDraftInit();

	// フォーム送信時イベントを上書き
	$('#BtnSave').unbind('click');
	$('#BtnSave').click(() => {
		if ($.bcUtil) {
			$.bcUtil.showLoader();
		}

		save();

		$('#BlogPostMode').val('save');
		if ($.bcToken) {
			$.bcToken.check(
				() => {
					const $BlogPostForm = $('#BlogPostForm');
					const $PageAdminEditForm = $('#PageAdminEditForm'); // baserCMS v4.x
					const $PageForm = $('#PageForm'); // baserCMS v3.x
					if ($BlogPostForm.length) {
						$BlogPostForm.submit();
					} else if ($PageAdminEditForm.length) {
						$PageAdminEditForm.submit();
					} else if ($PageForm.length) {
						$PageForm.submit();
					}
				},
				{ useUpdate: false, hideLoader: false },
			);
		}

		return false;
	});

	const btnPreview = document.getElementById('BtnPreview') as HTMLInputElement | null;
	if (btnPreview) {
		if (semver.lt(config.cmsVersion!, '4.0.0')) {
			// 保存前確認時イベントを上書き
			$(btnPreview).unbind('click');
			$(btnPreview).click(() => {
				if ($.bcToken && $.bcToken.update) {
					// baserCMS v3.0.10.1 未満は $.bcTokenが存在しない
					$.bcToken.update(
						() => {
							beforePreviewForBaserCMS3(() => {
								if ($.bcToken) {
									$("input[name='data[_Token][key]']").val($.bcToken.key || '');
								}
								$('#LinkPreview').trigger('click');
							});
						},
						{
							loaderType: 'none',
						},
					);
				} else {
					beforePreviewForBaserCMS3(() => {
						$('#LinkPreview').trigger('click');
					});
				}

				return false;
			});
		} else {
			// baserCMS4.0.x暫定対応

			// 判定する方法が不明なので、ありえるものは全部生成する
			const previewField = ['data[Page][contents_tmp]', 'data[BlogPost][detail_tmp]'];
			const previewFieldElements = previewField.map(fieldName => {
				const previewFieldElement = document.createElement('input');
				previewFieldElement.type = 'hidden';
				previewFieldElement.name = fieldName;
				// 配列が多い場合はdocumentFragmentを検討
				if (btnPreview.form) {
					btnPreview.form.appendChild(previewFieldElement);
				}
				return previewFieldElement;
			});

			const beforePreview = () => {
				previewFieldElements.forEach(previewFieldElement => {
					previewFieldElement.value = currentContentArea.getContentsAsString();
				});
			};

			// イベントを先頭に挿入する
			$(btnPreview).on('click', beforePreview);
			const unofficialJQuery: any = $; // tslint:disable-line:no-any
			if ($.isFunction(unofficialJQuery._data)) {
				const eventMap = unofficialJQuery._data(btnPreview).events;
				if (eventMap && eventMap.click) {
					const clickHandlers: Object[] = eventMap.click;
					const _beforePreview = clickHandlers.pop();
					if (_beforePreview) {
						clickHandlers.unshift(_beforePreview);
					}
				}
			}
		}
	}
}

/**
 * タイプモジュールを生成・登録する
 *
 * Addonフォルダ内の各タイプのinit.jsから呼び出される
 *
 * @param name タイプ名
 * @param option タイプ編集時のイベント処理などを登録する
 *
 */
export function registerTypeModule(name: string, option: IBurgerTypeModuleConstrucorOption = {}) {
	if (name in modules) {
		// eslint-disable-next-line no-console
		console.warn(`"${name}" is already exists.`);
		return;
	}
	modules[name] = new BurgerTypeModule(option);
}

/**
 * 指定のタイプのテンプレートHTMLを取得する
 *
 * TODO: TypeEditorDialogへ移動
 */
export function getTypeEditorTemplate(typeName: string) {
	return $originalTypeEditorElementContainer.find(`.Type${typeName}`).clone()[0];
}

/**
 * 保存
 *
 * storage要素（input:hidden）への値の代入までで、DBへの送信ではない
 */
export function save() {
	mainContentArea.save();
	if (draftContentArea) {
		draftContentArea.save();
	}
	// eslint-disable-next-line no-console
	console.info('save to input element for storage.');
}

/**
 * ストレージにブロックを保存する
 *
 * @param html HTML文字列
 */
export function copyBlock(html: string) {
	sessionStorage.setItem(STORAGE_KEY_OF_COPIED_BLOCK, html);
}

/**
 * ストレージに保存してあるブロックを参照する
 *
 * @return HTML文字列
 */
export function getCopiedBlock(): string | null {
	return sessionStorage.getItem(STORAGE_KEY_OF_COPIED_BLOCK);
}

export const versionCheck = {
	/**
	 * v1 < v2
	 */
	lt(v1: string, v2: string, loose?: boolean) {
		return semver.lt(v1, v2, loose);
	},

	/**
	 * v1 > v2
	 */
	gt(v1: string, v2: string, loose?: boolean) {
		return semver.gt(v1, v2, loose);
	},

	/**
	 * v1 <= v2
	 */
	lte(v1: string, v2: string, loose?: boolean) {
		return semver.lte(v1, v2, loose);
	},

	/**
	 * v1 >= v2
	 */
	gte(v1: string, v2: string, loose?: boolean) {
		return semver.gte(v1, v2, loose);
	},
};

/**
 * 必要な設定データを取得
 *
 * PHP → HTMLのinput要素へレンダリング → JSで取得
 *
 */
function getSettings() {
	parseConfig(document.getElementById('bge-config') as HTMLScriptElement);

	const mainContentStorageId = config.utility ? config.utility.mainFieldId || '' : '';
	const draftContentStorageId = config.utility ? config.utility.draftFieldId : null;

	$originalBlockElementContainer = $('#DefaultBlock');
	$originalTypeEditorElementContainer = $('#InputArea');

	cssListForCKEditor = config.utility ? config.utility.cssList.join(',') : '';
	googleMapsApiKey = config.utility ? config.utility.googleMapsApiKey : '';

	// CMSのバージョンの確認
	const _cmsVersion = config.cmsVersion || '';
	if (semver.valid(_cmsVersion)) {
		config.cmsVersion = semver.clean(_cmsVersion) || '';
	} else {
		const semCMSVersion = _cmsVersion.replace(/^([0-9]+\.[0-9]+\.[0-9]+).*/, '$1');
		if (semCMSVersion !== _cmsVersion) {
			// eslint-disable-next-line no-console
			console.warn(
				`baserCMSのバージョン情報が不正です。「${_cmsVersion}」はセマンティック バージョニング 2.0.0の仕様に従っていません。${semCMSVersion}として解釈します。`,
			);
			config.cmsVersion = semCMSVersion;
		} else {
			throw new Error(
				`baserCMSのバージョン情報が不正です。「${_cmsVersion}」はセマンティック バージョニング 2.0.0の仕様に従っていません。`,
			);
		}
	}

	componentObserver = new ComponentObserver();

	insertionPoint = new InsertionPoint();
	mainContentArea = new MainContentArea(
		document.getElementById('ValueArea'),
		document.getElementById(mainContentStorageId) as HTMLInputElement,
	);
	if (draftContentStorageId) {
		draftContentArea = new DraftContentArea(
			document.getElementById('DraftArea'),
			document.getElementById(draftContentStorageId) as HTMLInputElement,
		);
	}

	blockListDialog = new BlockListDialog(document.getElementById('PanelArea'));
	blockConfigDialog = new BlockConfigDialog(document.getElementById('BgBlockConfigArea'));
	typeEditorDialog = new TypeEditorDialog(document.getElementById('ContentsEditArea'));
}

function parseConfig(scriptElement: HTMLScriptElement) {
	if (scriptElement) {
		const json = scriptElement.innerText;
		try {
			const parsedJSON = JSON.parse(json);
			$.extend(config, parsedJSON);
			// eslint-disable-next-line no-console
			console.info('success: Configuration JSON is parsed.');
		} catch (error) {
			// eslint-disable-next-line no-console
			console.warn('parse error: Configuration JSON.');
		}
	}
}

/**
 * 本稿・下書きボタンの初期処理
 *
 * イベントの定義と実行
 * TODO: リファクタ・型付け
 *
 */
function mainToDraftInit() {
	if (!draftContentArea) {
		return;
	}

	// 本稿切替
	$('#CbeHonkouBtn').on('click', e => {
		if (!draftContentArea) {
			return;
		}
		const $this = $(e.currentTarget);
		mainContentArea.show();
		draftContentArea.hide();
		$this.closest('.draft-btn').find('.on').removeClass('on');
		$this.addClass('on');
		$('#CbeHonkouCopyBtn').addClass('on');
		currentContentArea = mainContentArea;
		currentContentArea.update();
		currentContentArea.check();
	});

	// 下書き切替
	$('#CbeSoukouBtn').on('click', e => {
		if (!draftContentArea) {
			return;
		}
		const $this = $(e.currentTarget);
		draftContentArea.show();
		mainContentArea.hide();
		$this.closest('.draft-btn').find('.on').removeClass('on');
		$this.addClass('on');
		$('#CbeSoukouCopyBtn').addClass('on');
		currentContentArea = draftContentArea;
		currentContentArea.update();
		currentContentArea.check();
	});

	// 草稿から本稿へコピー
	$('#CbeSoukouCopyBtn').click(() => {
		if (!draftContentArea) {
			return;
		}
		if (
			mainContentArea.isEmpty() ||
			draftContentArea.isSame(mainContentArea) ||
			confirm('下書き内容を本稿へ上書きしてもよろしいですか？')
		) {
			draftContentArea.copyTo(mainContentArea);
			$('#CbeHonkouBtn').trigger('click');
		}
	});

	// 本稿から草稿へコピー
	$('#CbeHonkouCopyBtn').click(() => {
		if (!draftContentArea) {
			return;
		}
		if (
			draftContentArea.isEmpty() ||
			mainContentArea.isSame(draftContentArea) ||
			confirm('本稿の内容を下書きへコピーしてもよろしいですか？')
		) {
			mainContentArea.copyTo(draftContentArea);
			$('#CbeSoukouBtn').trigger('click');
		}
	});

	$('#CbeHonkouBtn, #CbeSoukouBtn').on('dblclick', e => {
		const $this = $(e.currentTarget);
		if (!e.altKey || !$this.hasClass('on')) {
			return;
		}
		$this.trigger('click');
		currentContentArea.toggleDisplayMode();
		const modeDisplay = currentContentArea.isVisualMode ? '' : '<span>ソース表示</span>';
		$this.find('span').remove();
		$this.html((i, html) => html + modeDisplay);
	});

	// 本稿草稿ボタン初期設定
	$('#CbeHonkouBtn').trigger('click');
}

/**
 * プレビュー前処理
 *
 * TODO: リファクタ・型付け
 *
 * @param cb コールバック
 *
 */
function beforePreviewForBaserCMS3(cb: Function) {
	if (draftContentArea) {
		if (mainContentArea.visible() && !draftContentArea.visible()) {
			// 本稿のデータを保管領域に保管する
			mainContentArea.save();
		} else {
			const draftContent = draftContentArea.getContentsAsString();
			// 一時的に 下書きの内容を 本稿の保管領域に保管する
			mainContentArea.save(draftContent);
		}
	}

	const data = $(mainContentArea.getNode()).closest('form').serialize();
	const previewUrl = $('#PreviewUrl, #CreatePreviewUrl').text();

	$.ajax({
		type: 'POST',
		url: previewUrl,
		data,
		// tslint:disable-next-line
		success: (result: any): void => {
			if (result) {
				cb();
			} else {
				alert('プレビューの読み込みに失敗しました。');
			}
		},
		error: (): void => {
			alert('プレビューの読み込みに失敗しました。');
		},
	});
}
