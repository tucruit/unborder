import * as BgE from '../BgE';
import BlockMenu from './BlockMenu';
import BurgerBlock from './BurgerBlock';
import BurgerEditorElement from './BurgerEditorElement';
import InitialInsertionButton from './InitialInsertionButton';
import Migrator from './Migrator';

const CONTAINER_PADDING = 10;
const CONTENT_ID = 'bge-content';
const CONTENT_CLASSES = ['bge-contents', 'bge_contents', 'bge_content'];

/**
 * ContentAreaクラス
 */
abstract class ContentArea extends BurgerEditorElement {
	public blockMenu: BlockMenu;

	/**
	 * 表示コンテンツを内包するHTML要素（iframe内）
	 *
	 * ```html
	 * <div  id="bge-content">...</div>
	 * ```
	 */
	private _containerElement: HTMLElement;

	/**
	 * コンテンツの内容を格納するinput要素
	 *
	 */
	private _storageElement: HTMLInputElement;

	/**
	 * ヴィジュアルエディタのフレーム
	 */
	private _frameElement: HTMLIFrameElement;

	/**
	 * ソース表示用のテキストエリア
	 */
	private _sourceTextarea: HTMLTextAreaElement;

	/**
	 * 要素追加ボタン
	 */
	private _insertionButton: InitialInsertionButton;

	private _isVisualMode = true;

	/**
	 * コンストラクタ
	 *
	 * @param node HTML要素
	 * @param storageNode コンテンツの内容を格納するinput要素
	 */
	constructor(node: HTMLElement | null, storageNode: HTMLInputElement) {
		super(node);
		if (!node || !storageNode) {
			throw new Error('コンテンツを保持する要素の取得に失敗しました。');
		}
		this._storageElement = storageNode;

		// ルート要素の設定
		node.setAttribute('data-component', 'ContentArea');

		// プレーンテキストエリアの設定
		this._sourceTextarea = document.createElement('textarea');
		this._sourceTextarea.spellcheck = false;
		node.appendChild(this._sourceTextarea);

		// フレームの生成
		this._frameElement = document.createElement('iframe');
		this._frameElement.setAttribute('width', '100%;');
		this._frameElement.setAttribute('scrolling', 'no');
		node.appendChild(this._frameElement);

		if (!this._frameElement.contentWindow) {
			throw new Error('Impossible error: The contentWindow of created iframe is null.');
		}

		this._frameElement.contentWindow.document.open();
		this._frameElement.contentWindow.document.close();

		// スタイルシートの取得
		const bgeStyleDefaultPath = removeStylesheet('link[href*="bge_style_default.css"]');
		const bgeStylePath = removeStylesheet('link[href*="bge_style.css"]');
		const bgeCSSPath = getStylesheet('link[href*="burger_editor.css"]');

		// スタイルシートをiframeに適応
		if (this._frameElement.contentWindow.document.head) {
			if (bgeStyleDefaultPath) {
				this._frameElement.contentWindow.document.head.appendChild(
					createCSSLinkTag(bgeStyleDefaultPath, this._frameElement.contentWindow),
				);
			}
			if (bgeStylePath) {
				this._frameElement.contentWindow.document.head.appendChild(
					createCSSLinkTag(bgeStylePath, this._frameElement.contentWindow),
				);
			}
			if (bgeCSSPath) {
				this._frameElement.contentWindow.document.head.appendChild(
					createCSSLinkTag(bgeCSSPath, this._frameElement.contentWindow),
				);
			}
		}

		// iframe bodyのスタイル設定
		this._frameElement.contentWindow.document.body.setAttribute('style', 'margin: 0; border: 0;');

		// 本文データを設定
		// this._frameElement.contentWindow.document.body.innerHTML = `<div id="${CONTENT_ID}" style="padding: ${CONTAINER_PADDING}px; overflow: hidden;">${storageNode.value}</div>`;
		// this._containerElement = this._frameElement.contentWindow.document.getElementById(CONTENT_ID)!;
		this._containerElement = this._frameElement.contentWindow.document.createElement('div');
		this._containerElement.id = CONTENT_ID;
		this._containerElement.style.padding = `${CONTAINER_PADDING}px`;
		this._containerElement.style.overflow = 'hidden';
		this._containerElement.setAttribute('class', CONTENT_CLASSES.join(' '));
		this._containerElement.innerHTML = storageNode.value;

		// ブロックメニュー
		this.blockMenu = new BlockMenu(this._frameElement.contentWindow.document.createElement('div'));

		// 初期挿入ボタン
		this._insertionButton = new InitialInsertionButton(this);

		// 要素をiframe内に設置
		const els = this._frameElement.contentWindow.document.createDocumentFragment();
		els.appendChild(this.blockMenu.getNode());
		els.appendChild(this._insertionButton.getNode());
		els.appendChild(this._containerElement);
		this._frameElement.contentWindow.document.body.appendChild(els);

		// イベントの設定
		window.addEventListener('resize', this._setHeightTrigger.bind(this), true);
		window.addEventListener('DOMContentLoaded', this._setHeightTrigger.bind(this), false);
		window.document.addEventListener('load', this._setHeightTrigger.bind(this), true);
		this._frameElement.contentWindow.addEventListener('resize', this._setHeightTrigger.bind(this), true);
		this._frameElement.contentWindow.addEventListener('DOMContentLoaded', this._setHeightTrigger.bind(this), false);
		this._frameElement.contentWindow.document.addEventListener('load', this._setHeightTrigger.bind(this), true);
		this._sourceTextarea.addEventListener('blur', this._saveSource.bind(this), false);

		// 本文データをブロックとタイプに関連付け
		this._initBlocksAndTypes();

		// モード初期化
		this._switchMode(true);

		/**
		 * 編集アイコン
		 *
		 * タイプにマウスオンすると表示されるマウスに追随するアイコン
		 *
		 * TODO: リファクタ
		 */
		const $editorIcon = $('.edit-inner');
		$(this.containerElement)
			.on('mousemove', '[data-bgt]', e => {
				requestAnimationFrame(() => {
					const r = this.getNode().getBoundingClientRect();
					$editorIcon.show();
					$editorIcon.css({
						left: e.pageX + r.left + window.pageXOffset,
						top: e.pageY + r.top + window.pageYOffset,
					});
				});
			})
			.on('mouseleave', '[data-bgt]', () => {
				$editorIcon.hide();
			});
	}

	/**
	 * 表示コンテンツを内包するHTML要素（iframe内）
	 *
	 * ```html
	 * <div id="bge-content">...</div>
	 * ```
	 */
	public get containerElement() {
		return this._containerElement;
	}

	public get isVisualMode() {
		return this._isVisualMode;
	}

	/**
	 * 内容（HTML文字列）の設定
	 */
	public setContentsAsString(htmlString: string) {
		this._containerElement.innerHTML = htmlString.trim();
	}

	/**
	 * 内容（HTML文字列）の取得
	 */
	public getContentsAsString() {
		return this._containerElement.innerHTML.trim();
	}

	/**
	 * 内容（DOM）の設定
	 */
	public setContentsAsDOM(element: HTMLElement) {
		this._containerElement.innerHTML = '';
		this._containerElement.appendChild(element);
	}

	/**
	 * 編集した要素をstorage要素へ保存
	 *
	 * 引数で内容を指定することが可能
	 *
	 * @param content 内容（HTML文字列）
	 *
	 */
	public save(content?: string) {
		if (content) {
			this._storageElement.value = content;
		} else {
			const $container = $(this._containerElement);

			/**
			 * 不要な属性の削除
			 *
			 * TODO: BurgerBlockクラスが担う
			 */

			// 削除しないリスト
			const removeAttrNameIgnoreList = [
				'class',
				'id',
				'data-bgb',
				'data-bgb-publish-datetime',
				'data-bgb-unpublish-datetime',
				'data-bgb-publish-datetime-range',
			];
			$container.find('[data-bgb]').each((i, el) => {
				const attrList: NamedNodeMap = el.attributes;
				for (let j = 0, l = attrList.length; j < l; j++) {
					const attr: Attr | null = attrList.item(j);
					if (attr && removeAttrNameIgnoreList.indexOf(attr.name) === -1) {
						el.removeAttribute(attr.name);
					}
				}
			});

			// 挿入点の解除
			BgE.insertionPoint.unset();

			const value = this.getContentsAsString();
			this._storageElement.value = value;
			this._sourceTextarea.value = value;
		}

		this.update();
	}

	/**
	 * 内容をコピーする
	 *
	 */
	public copyTo(contentArea: ContentArea) {
		contentArea.setContentsAsString(this.getContentsAsString());
		contentArea._initBlocksAndTypes();
	}

	/**
	 * 内容が同じかどうか
	 */
	public isSame(contentArea: ContentArea) {
		return this.getContentsAsString() === contentArea.getContentsAsString();
	}

	/**
	 * 内容が空かどうか
	 */
	public isEmpty() {
		return this.getContentsAsString() === '';
	}

	public update() {
		this._setHeightTrigger();
		this._showInsertionButton();
	}

	public check() {
		Migrator.check(this.containerElement);
	}

	public toggleDisplayMode() {
		this._saveSource();
		this._switchMode(!this._isVisualMode);
	}

	private _initBlocksAndTypes() {
		// BurgerEditor関連のコンテンツがまったく入っていない場合
		const $contents = $(this.containerElement);
		if (
			!this.isEmpty() &&
			$contents.find('[data-bgb], .bgb-container, .bg-editor-block-container, .cb-editor-block-container')
				.length === 0
		) {
			const block = BurgerBlock.createUnknownBlock(this.getContentsAsString());
			this.setContentsAsDOM(block.node);
		} else {
			$contents.find('>:not([data-bgb])').each((i, el: HTMLElement) => {
				BurgerBlock.createUnknownBlock(el.outerHTML);
				el.remove();
			});
			$contents.find('[data-bgb]').each((i, el: HTMLElement) => {
				new BurgerBlock(el);
			});
		}
		this.check();
		this.save();
	}

	/**
	 * 「下に要素を追加」の表示
	 */
	private _showInsertionButton() {
		if (this.isEmpty()) {
			this._insertionButton.show();
		} else {
			this._insertionButton.hide();
		}
	}

	/**
	 *
	 */
	private _setHeightTrigger(): void {
		if ('requestAnimationFrame' in window) {
			requestAnimationFrame(() => this._setHeight());
		} else {
			this._setHeight();
		}
	}

	private _setHeight(): void {
		const height = this._containerElement.getBoundingClientRect().height + CONTAINER_PADDING * 2;
		this._frameElement.setAttribute('height', `${height}`);
	}

	private _switchMode(visualMode: boolean): void {
		this._isVisualMode = visualMode;
		this._node.setAttribute('data-component-mode', this._isVisualMode ? 'visual' : 'source');
		this._frameElement.hidden = !visualMode;
		this._sourceTextarea.hidden = !!visualMode;
		this._sourceTextarea.disabled = !!visualMode;
	}

	private _saveSource(): void {
		if (!this._isVisualMode) {
			this.setContentsAsString(this._sourceTextarea.value);
			this.save();
		}
	}
}

export default ContentArea;

function getStylesheet(selector: string) {
	const styleTag = document.querySelector(selector);
	if (!styleTag) {
		throw new Error('CSS用のlink要素の取得に失敗しました。');
	}
	const cssPath = styleTag.getAttribute('href');
	return cssPath;
}

function removeStylesheet(selector: string) {
	const styleTag = document.querySelector<HTMLLinkElement>(selector);
	if (!styleTag) {
		throw new Error('CSS用のlink要素の取得に失敗しました。');
	}
	const cssPath = styleTag.getAttribute('href');
	// スタイルの無効化
	styleTag.type = 'text/bge-css-copied';
	styleTag.rel = 'nofollow';
	return cssPath;
}

function createCSSLinkTag(cssPath: string, baseWindow: Window) {
	const linkTag = baseWindow.document.createElement('link');
	linkTag.rel = 'stylesheet';
	linkTag.href = cssPath;
	return linkTag;
}
