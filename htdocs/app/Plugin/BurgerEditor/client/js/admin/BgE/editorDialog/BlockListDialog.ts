import * as BgE from '../../BgE';
import * as semver from 'semver';
import BurgerBlock from '../BurgerBlock';
import EditorDialog from './EditorDialog';

/**
 * ブロック選択ダイアログクラス
 */
export default class BlockListDialog extends EditorDialog {
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
			},
		});
		// ブロック追加
		this.$el.on('click', 'li', e => {
			const $target: JQuery = $(e.currentTarget);
			const specificBlock = `${$target.data('bge-specific-block')}`;
			switch (specificBlock) {
				case 'copy': {
					const copiedHTML: string | null = BgE.getCopiedBlock();
					if (copiedHTML) {
						const $parsedBlock: JQuery = $(copiedHTML);
						const parsedHTML: HTMLElement = $parsedBlock.get(0);
						const originalBlock: BurgerBlock = new BurgerBlock(parsedHTML);
						this.addBlock(originalBlock);
					}
					break;
				}
				default: {
					// data-bge-block属性の値からブロック名を取得
					const blockName = `${$target.data('bge-block')}`;
					const block: BurgerBlock = new BurgerBlock(blockName);
					const message = block.isDisable();
					if (message) {
						alert(message);
						return false;
					} else {
						this.addBlock(block);
					}
				}
			}
			this.close();
			return false;
		});
	}

	/**
	 * ブロックを追加する
	 *
	 * @param blockName ブロックの名前
	 *
	 */
	public addBlock(block: BurgerBlock): void {
		BgE.insertionPoint.insert(block);
	}

	/**
	 * 編集ダイアログを開いた時の処理
	 *
	 * @override
	 *
	 */
	protected _open(): boolean {
		if (!BgE.getCopiedBlock()) {
			return true;
		}
		this.$el.find('.bg-blocks').prepend(`
			<dl class="bge-copied-block">
				<dt>クリップボード</dt>
				<dd>
					<ul>
						<li data-bge-specific-block="copy">
							<figure>
								<div class="bge-block-icon"></div>
								<figcaption>ペースト<figcaption/>
							</figure>
						</li>
					</ul>
				</dd>
			</dl>
		`);
		return false;
	}

	/**
	 * 編集ダイアログを閉じた時の処理
	 *
	 * @override
	 *
	 */
	protected _close() {
		this.$el.find('.bge-copied-block').remove(); // これなに
		if (!BgE.editorStatus.isProcessed) {
			BgE.save();
		}
	}
}
