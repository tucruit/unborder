import * as BgE from '../BgE';
import BurgerEditorElement from './BurgerEditorElement';

/**
 * BlockMenuクラス
 */
export default class BlockMenu extends BurgerEditorElement {
	private _isHover: boolean;

	/**
	 * コンストラクタ
	 *
	 * @param parentBlock 属するブロック
	 */
	constructor(el: HTMLElement) {
		el.setAttribute('data-bge', 'bgb-menu');
		el.innerHTML = `
			<div class="bgb-menu-btns">
				<div class="bgb-menu-btn-area-move">
					<button type="button" class="bgb-menu-move-up"><span>ひとつ上へ移動</span></button>
					<button type="button" class="bgb-menu-move-down"><span>ひとつ下へ移動</span></button>
				</div>
				<div class="bgb-menu-btn-area-command">
					<button type="button" class="bgb-menu-insert-before"><span>上にブロックを追加</span></button>
					<button type="button" class="bgb-menu-insert-after"><span>下にブロックを追加</span></button>
					<button type="button" class="bgb-menu-block-config"><span>オプション設定</span></button>
					<button type="button" class="bgb-menu-block-copy"><span>ブロックをコピー</span></button>
					<button type="button" class="bgb-menu-delete"><span>ブロックを削除</span></button>
				</div>
			</div>
		`;
		super(el);
		this._isHover = false;

		this.hide();

		const $el = $(this._node);
		$el.on('click', '.bgb-menu-insert-before, .bgb-menu-insert-after', this._insert.bind(this));
		$el.on('click', '.bgb-menu-block-config', this._openConfig.bind(this));
		$el.on('click', '.bgb-menu-block-copy', this._copy.bind(this));
		$el.on('click', '.bgb-menu-delete', this._delete.bind(this));
		$el.on('click', '.bgb-menu-move-up, .bgb-menu-move-down', this._move.bind(this));
		$el.on('mouseenter', () => (this._isHover = true));
		$el.on('mouseleave', () => (this._isHover = false));
	}

	public get hidden() {
		return !this.visible();
	}

	public get isHover() {
		return this._isHover;
	}

	/**
	 * メニューアイコンの表示
	 */
	public show() {
		super.show();
		const $this = $(this.getNode());
		const currentBlock = BgE.editor.getCurrentBlock();
		if (currentBlock && !currentBlock.existPrev()) {
			$this.find('.bgb-menu-move-up').prop('disabled', true);
		}
		if (currentBlock && !currentBlock.existNext()) {
			$this.find('.bgb-menu-move-down').prop('disabled', true);
		}
	}

	/**
	 * メニューアイコンの非表示
	 */
	public hide() {
		super.hide();
		const $this = $(this.getNode());
		$this.find('.bgb-menu-move-up, .bgb-menu-move-down').prop('disabled', false);
	}

	public setPosition(rect: ClientRect) {
		this._node.style.top = `${rect.top}px`;
		this._node.style.left = `${rect.left}px`;
		this._node.style.width = `${rect.width}px`;
	}

	/**
	 * ブロック挿入
	 */
	private _insert(e: JQueryEventObject) {
		const currentBlock = BgE.editor.getCurrentBlock();
		if (currentBlock) {
			const $this = $(e.target);
			const isAfter = $this.hasClass('bgb-menu-insert-after');
			BgE.insertionPoint.set(currentBlock, isAfter);
			BgE.blockListDialog.open();
		}
	}

	/**
	 * オプション設定を開く
	 */
	private _openConfig() {
		if (BgE.editorStatus.isProcessed) {
			return;
		}
		BgE.blockConfigDialog.open();
	}

	/**
	 * コピー
	 */
	private _copy() {
		const currentBlock = BgE.editor.getCurrentBlock();
		if (BgE.editorStatus.isProcessed || !currentBlock) {
			return;
		}
		if (currentBlock.name === 'unknown') {
			alert(
				'このブロックをコピーするには、ブロックのアップデートが必要です。\nブロックをアップロードしてください。',
			);
			return;
		}
		this.hide();
		const html = currentBlock.getHTMLStringify();
		this.show();
		BgE.copyBlock(html);
		$(`
			<div id="bge-dialog" title="ブロックをコピーしました">
				<p>ブロックの追加ボタンからペースト（貼り付ける）ことができます。</p>
			</div>
		`).dialog({
			show: {
				effect: 'fade',
				duration: 300,
			},
			hide: {
				effect: 'fade',
				duration: 300,
			},
			width: 260,
			height: 80,
			resizable: false,
			open: (e: Event, ui: JQueryUI.DialogOptions) => {
				setTimeout(() => {
					if (!e.target) {
						return;
					}
					$(e.target).dialog('close');
				}, 1600);
			},
		});
	}

	/**
	 * 削除
	 *
	 */
	private async _delete() {
		const currentBlock = BgE.editor.getCurrentBlock();
		if (BgE.editorStatus.isProcessed || !currentBlock) {
			return;
		}
		if (
			confirm(
				'ブロック要素を削除します。\n削除したブロック要素はもとに戻すことはできません。\n削除してもよろしいですか？',
			)
		) {
			BgE.editorStatus.isProcessed = true;
			BgE.currentContentArea.blockMenu.hide();
			await currentBlock.animate({ height: 0, opacity: 0 }, 500);
			BgE.editor.clearCurrentBlock();
			currentBlock.remove();
			BgE.save();
			BgE.editorStatus.isProcessed = false;
		}
	}

	/**
	 * 移動
	 */
	private _move(e: JQueryEventObject) {
		const currentBlock = BgE.editor.getCurrentBlock();
		if (BgE.editorStatus.isProcessed || !currentBlock) {
			return;
		}
		const $this = $(e.target);
		const $from = $(currentBlock.node);
		const isUp = $this.hasClass('bgb-menu-move-up');
		let $to: JQuery;
		if (isUp) {
			$to = $from.prev();
		} else {
			$to = $from.next();
		}
		const DURATION = 600;
		const areaStyle = {
			visibility: 'hidden',
			pointerEvents: 'none',
		};
		BgE.editorStatus.isProcessed = true;
		this.hide();
		$from.add($to).addClass('-bge-animation-replacement');
		$('.bge-view-value').css('position', 'relative'); // TODO: あとで移動
		const fromRectBefore = $from.position();
		const toRectBefore = $to.position();
		if (isUp) {
			$from.insertBefore($to);
		} else {
			$from.insertAfter($to);
		}
		const fromRectAfter = $to.position();
		const toRectAfter = $from.position();
		const $fromArea = $('<div />');
		const $toArea = $('<div />');
		$fromArea.append($from.clone()).css(areaStyle);
		$toArea.append($to.clone()).css(areaStyle);
		$fromArea.insertAfter($from);
		$toArea.insertAfter($to);
		$from.css({
			position: 'absolute',
			top: fromRectBefore.top,
			left: fromRectBefore.left,
			zIndex: 1,
		});
		$to.css({
			position: 'absolute',
			top: toRectBefore.top,
			left: toRectBefore.left,
			zIndex: 0,
		});
		$from.animate(
			{
				top: toRectAfter.top,
				left: toRectAfter.left,
			},
			DURATION,
		);
		$to.animate(
			{
				top: fromRectAfter.top,
				left: fromRectAfter.left,
			},
			DURATION,
			() => {
				$fromArea.remove();
				$toArea.remove();
				$from.removeAttr('style');
				$to.removeAttr('style');
				$from.add($to).removeClass('-bge-animation-replacement');
				BgE.editorStatus.isProcessed = false;
				BgE.save();
			},
		);
	}
}
