import * as BgE from '../../BgE';
import * as semver from 'semver';
import { IGridInfo, IScheduledPublishing } from '../BurgerBlock';
import BlockOption from '../BlockOption';
import EditorDialog from './EditorDialog';
import Util from '../Util';

/**
 * ブロック詳細設定ダイアログ
 *
 * TODO: 各処理の粒度を上げる
 *
 */
export default class BlockConfigDialog extends EditorDialog {
	/**
	 * ブロックオプション設定
	 */
	private _options: { [optionName: string]: BlockOption } = {};

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

		if (BgE.config.blockClassOption) {
			for (const optionName of Object.keys(BgE.config.blockClassOption)) {
				const option: BlockOption = new BlockOption(optionName, BgE.config.blockClassOption[optionName]);
				this._options[optionName] = option;
			}
		}

		if (!el) {
			return;
		}

		// 公開期間設定
		$(el)
			.find('[data-bge-block-option-scheduled-publishing]')
			.each((i, _el) => {
				const $input = $(_el);
				const type = $input.attr('data-bge-block-option-scheduled-publishing');
				switch (type) {
					case 'publish-date':
					case 'unpublish-date': {
						$input.datepicker();
						break;
					}
					case 'publish-time':
					case 'unpublish-time': {
						$input.timepicker({ timeFormat: 'H:i' });
						break;
					}
				}
			});

		// 項目の表示非表示
		if (BgE.config.setting && BgE.config.setting.publishTimer) {
			$('[data-bge-block-option-scheduled-publishing-area]').prop('hidden', false);
		}
	}

	/**
	 * 編集ダイアログ内の入力内容を空にする
	 */
	public reset() {
		super.reset();
		this.$el.find('[data-bge-block-option-select-box]').empty();
	}

	/**
	 * 編集完了時に実行する
	 */
	public complete() {
		const currentBlock = BgE.editor.getCurrentBlock();
		if (currentBlock) {
			currentBlock.importOptions(this._exportOption());
			currentBlock.importCustomClassList(this._exportCustomClassList());
			currentBlock.importId(this._exportId());
			currentBlock.importGridInfo(this._exportGridInfo());
			currentBlock.importScheduledPublishing(this._exportScheduledPublishing());
		}

		// ブロックコンバート
		// const convType: string = $(this).find('[name="bgb-block-covert-pattern"]').val();
		// if (convType) {
		// 	let $targetBlock: JQuery = currentBlock.$el;
		// 	const convTypeTo: string = convType.replace(/[a-z0-9-]+__([a-z0-9-]+)/i, '$1');
		// 	switch (convType) {
		// 		case 'image2__image-text2':
		// 		case 'image-link2__image-link-text2': {
		// 			$targetBlock = $targetBlock.add($targetBlock.next());
		// 			const $targetTypes: JQuery = $targetBlock.find('[data-bgt]');
		// 			const _valueList: IBurgerTypeContentData[] = [];
		// 			const valueList: IBurgerTypeContentData[] = [];
		// 			$targetTypes.each(function() {
		// 				// TODO: export
		// 				// const $this: JQuery = $(this);
		// 				// const value: IBurgerTypeContentData = BurgerType.getValues($this);
		// 				// _valueList.push(value);
		// 			});
		// 			valueList[0] = _valueList[0];
		// 			valueList[1] = _valueList[2];
		// 			valueList[2] = _valueList[1];
		// 			valueList[3] = _valueList[3];
		// 			// TODO: import
		// 			// const tmpl: HTMLElement = getBlockTemplate(convTypeTo);
		// 			// const block: BurgerBlock = new BurgerBlock(tmpl, valueList);
		// 			// $targetBlock.last().after(block.$el);
		// 			// $targetBlock.remove();

		// 		}
		// 		break;
		// 		case 'image3__image-text3':
		// 		case 'image-link3__image-link-text3': {
		// 			$targetBlock = $targetBlock.add($targetBlock.next());
		// 			const $targetTypes: JQuery = $targetBlock.find('[data-bgt]');
		// 			const _valueList: IBurgerTypeContentData[] = [];
		// 			const valueList: IBurgerTypeContentData[] = [];
		// 			$targetTypes.each(function() {
		// 				// TODO: export
		// 				// const $this: JQuery = $(this);
		// 				// const value: IBurgerTypeContentData = BurgerType.getValues($this);
		// 				// _valueList.push(value);
		// 			});
		// 			valueList[0] = _valueList[0];
		// 			valueList[1] = _valueList[2];
		// 			valueList[2] = _valueList[1];
		// 			valueList[3] = _valueList[3];
		// 			// TODO: import
		// 			// const tmpl: HTMLElement = getBlockTemplate(convTypeTo);
		// 			// const block: BurgerBlock = new BurgerBlock(tmpl, valueList);
		// 			// $targetBlock.last().after(block.$el);
		// 			// $targetBlock.remove();
		// 		}
		// 		break;
		// 		default: {
		// 			// void
		// 		}
		// 	}
		// }
		this.close();
	}

	/**
	 * 編集ダイアログを開いた時の処理
	 *
	 * @override
	 *
	 */
	protected _open() {
		this.reset();

		const currentBlock = BgE.editor.getCurrentBlock();
		if (currentBlock) {
			this._importOption(currentBlock.exportOptions());
			this._importCustomClassList(currentBlock.exportCustomClassList());
			this._importId(currentBlock.exportId());
			this._importGridInfo(currentBlock.exportGridInfo());
			this._importScheduledPublishing(currentBlock.exportScheduledPublishing());
		}

		// const $targetBlock: JQuery = currentBlock.$el;
		// const blockName: string = `${$targetBlock.data('bgb')}`;
		// const nextBlockName: string = `${$targetBlock.next().data('bgb')}`;
		// let isConverting: boolean = false;
		// const $blockCovert: JQuery = $('.bgb-block-covert');
		// const convertPattern: { name: string, from: string, to: string }[] = [];
		// switch (blockName) {
		// 	case 'image2': {
		// 		if (nextBlockName === 'wysiwyg2') {
		// 			isConverting = true;
		// 			const from: string = blockName;
		// 			const to: string = 'image-text2';
		// 			convertPattern.push({
		// 				name: `${from}__${to}`,
		// 				from: from,
		// 				to: to,
		// 			});
		// 		}
		// 	}
		// 	break;
		// 	case 'image-link2': {
		// 		if (nextBlockName === 'wysiwyg2') {
		// 			isConverting = true;
		// 			const from: string = blockName;
		// 			const to: string = 'image-link-text2';
		// 			convertPattern.push({
		// 				name: `${from}__${to}`,
		// 				from: from,
		// 				to: to,
		// 			});
		// 		}
		// 	}
		// 	break;
		// 	case 'image3': {
		// 		if (nextBlockName === 'wysiwyg3') {
		// 			isConverting = true;
		// 			const from: string = blockName;
		// 			const to: string = 'image-text3';
		// 			convertPattern.push({
		// 				name: `${from}__${to}`,
		// 				from: from,
		// 				to: to,
		// 			});
		// 		}
		// 	}
		// 	break;
		// 	case 'image-link3': {
		// 		if (nextBlockName === 'wysiwyg3') {
		// 			isConverting = true;
		// 			const from: string = blockName;
		// 			const to: string = 'image-link-text3';
		// 			convertPattern.push({
		// 				name: `${from}__${to}`,
		// 				from: from,
		// 				to: to,
		// 			});
		// 		}
		// 	}
		// 	break;
		// 	default: {
		// 		// void
		// 	}
		// }
		// if (isConverting) {
		// 	$blockCovert.show();
		// 	const $pattern: JQuery = $blockCovert.find('.bgb-block-convert-pattern');
		// 	$pattern.empty();
		// 	const pTmpl: (...data: any[]) => any = _.template($blockCovert.find('script').html());
		// 	const listHTML: string = pTmpl({ patterns: convertPattern });
		// 	$pattern.html(listHTML);
		// } else {
		// 	$blockCovert.hide();
		// }
	}

	/**
	 * 編集ダイアログを開いた時の処理
	 *
	 * override前提
	 *
	 */
	protected _close() {
		$('[data-bge-grid-changer]').hide();
		BgE.save();
	}

	/**
	 * オプションをダイアログに反映させる
	 */
	private _importOption(blockOptions: BlockOption[]) {
		this.$el.find('[data-bge-block-option]').each((i, el) => {
			const $optionSet = $(el);
			const optionName = $optionSet.attr('data-bge-block-option') || '';
			const option = this._options[optionName];
			if (!option) {
				return;
			}
			const $selectBox = $optionSet.find('[data-bge-block-option-select-box]');
			if (!$selectBox.length) {
				return;
			}
			let hasSelectedOption = false;
			option.classList.forEach(classInfo => {
				const $optionEl = $(`<option value="${classInfo.className}">${classInfo.label}</option>`);
				$optionEl.appendTo($selectBox);
				blockOptions.forEach(blockOption => {
					if (blockOption.currentClass && blockOption.currentClass.className === classInfo.className) {
						$optionEl.prop('selected', true);
						hasSelectedOption = true;
					}
				});
			});
			$selectBox.prepend($(`<option${hasSelectedOption ? '' : ' selected'}>指定なし</option>`));
		});
	}

	/**
	 * ダイアログで選択されたオプションを取得
	 */
	private _exportOption(): BlockOption[] {
		const blockOptions: BlockOption[] = [];
		this.$el.find('[data-bge-block-option]').each((i, el) => {
			const $optionSet = $(el);
			const $selectBox = $optionSet.find('[data-bge-block-option-select-box]');
			if (!$selectBox.length) {
				return;
			}
			const $selectedOption = $selectBox.find('option:selected');
			if (!$selectedOption.length) {
				return;
			}
			const className = ($selectedOption.val() as string) || '';
			const option = BlockOption.getOption(className);
			if (option) {
				blockOptions.push(option);
			}
		});
		return blockOptions;
	}

	/**
	 * 独自クラスをダイアログに反映させる
	 */
	private _importCustomClassList(classList: string[]) {
		const $input = this.$el.find('[data-bge-block-option-custom-class] [data-bge-block-option-input]');
		$input.val(classList.join(' '));
	}

	/**
	 * ダイアログに入力された独自クラスを取得
	 */
	private _exportCustomClassList(): string[] {
		const $input = this.$el.find('[data-bge-block-option-custom-class] [data-bge-block-option-input]');
		const classListString: string = ($input.val() as string) || '';
		const classList = classListString.split(/\s+/g);
		const optimizedClassList: string[] = [];
		const invalidClassList: string[] = [];
		classList.forEach(className => {
			if (className !== '' && optimizedClassList.indexOf(className) === -1) {
				if (Util.isValidAsClassName(className)) {
					optimizedClassList.push(className);
				} else {
					invalidClassList.push(className);
				}
			}
		});
		if (invalidClassList.length) {
			alert(`以下のクラス名は使用できない文字を含むため除外されます。\n${invalidClassList.join('\n')}`);
		}
		return optimizedClassList;
	}

	/**
	 * 独自クラスをダイアログに反映させる
	 */
	private _importId(id: string) {
		if (!id) {
			return;
		}
		id = id.replace(new RegExp(`^${BgE.BLOCK_ID_PREFIX}`), '');
		const $input = this.$el.find('[data-bge-block-option-id] [data-bge-block-option-input]');
		$input.val(id);
	}

	/**
	 * ダイアログに入力されたIDを取得
	 */
	private _exportId() {
		const $input = this.$el.find('[data-bge-block-option-id] [data-bge-block-option-input]');
		const input = (($input.val() as string) || '').trim();
		if (!input) {
			return '';
		}
		const id = BgE.BLOCK_ID_PREFIX + input;
		// 使用可能文字チェック
		if (!/^[a-zA-Z0-9.:_-]+$/.test(id)) {
			alert(`"${id}" はid属性として使用できない文字が含まれています。`);
			return '';
		}
		const currentBlock = BgE.editor.getCurrentBlock();
		if (document.getElementById(id) && currentBlock && currentBlock.id !== id) {
			alert(`"${id}" は既に定義されています。`);
			return '';
		}
		return `${id}`;
	}

	/**
	 * グリッド情報をダイアログに反映
	 *
	 */
	private _importGridInfo(gridInfo: IGridInfo) {
		const currentBlock = BgE.editor.getCurrentBlock();
		if (!currentBlock) {
			// eslint-disable-next-line no-console
			console.warn('BgE.editor.getCurrentBlock() is null.');
			return;
		}

		const $gridChanger = this.$el.find('[data-bge-grid-changer]');
		const $changeables = $(currentBlock.node.querySelectorAll('[data-bge-grid-changeable]'));

		// 基本的には項目を隠す
		$gridChanger.hide();

		// [data-bge-grid-changeable] 要素の有無の確認
		if ($changeables.length === 0) {
			return;
		}

		// [data-bge-grid-changeable] 要素がある場合のみ表示
		$gridChanger.show();

		// グリッド変更設定
		const $gridRatio = this.$el.find('[name=bge-grid-ratio]'); // ブロック編集ダイアロググリッド比
		const $gridRatioSP = this.$el.find('[name=bge-sp-grid-ratio]'); // ブロック編集ダイアロググリッド比
		const $gridSPEnabled = this.$el.find('[name=bge-sp-grid-ratio-enabled]');

		$gridRatio.val(`${gridInfo.normalRatio}`);
		if (gridInfo.spEnabled) {
			$gridRatioSP.val(`${gridInfo.spRatio}`);
		}

		$gridSPEnabled.prop('checked', gridInfo.spEnabled);
		$gridRatioSP.prop('disabled', !gridInfo.spEnabled);
		$gridSPEnabled.off().on('change', e => {
			$gridRatioSP.prop('disabled', !$gridSPEnabled.prop('checked'));
		});
	}

	/**
	 * ダイアログのグリッド情報を取得
	 *
	 */
	private _exportGridInfo() {
		const $gridRatio = this.$el.find('[name=bge-grid-ratio]'); // ブロック編集ダイアロググリッド比
		const $gridRatioSP = this.$el.find('[name=bge-sp-grid-ratio]'); // ブロック編集ダイアロググリッド比
		const $gridSPEnabled = this.$el.find('[name=bge-sp-grid-ratio-enabled]');
		const normalRatio = +($gridRatio.val() as string);
		const spRatio = +($gridRatioSP.val() as string);
		const spEnabled = $gridSPEnabled.prop('checked');
		const gridInfo: IGridInfo = {
			normalRatio,
			spRatio,
			spEnabled,
		};
		return gridInfo;
	}

	/**
	 * 公開期間設定をダイアログに反映
	 *
	 */
	private _importScheduledPublishing(scheduledPublishing: IScheduledPublishing) {
		if (scheduledPublishing.publishDatetime) {
			const [date, time] = scheduledPublishing.publishDatetime.split(' ');
			this.$el.find('[data-bge-block-option-scheduled-publishing="publish-date"]').val(date);
			this.$el.find('[data-bge-block-option-scheduled-publishing="publish-time"]').val(time);
		}
		if (scheduledPublishing.unpublishDatetime) {
			const [date, time] = scheduledPublishing.unpublishDatetime.split(' ');
			this.$el.find('[data-bge-block-option-scheduled-publishing="unpublish-date"]').val(date);
			this.$el.find('[data-bge-block-option-scheduled-publishing="unpublish-time"]').val(time);
		}
	}

	/**
	 * ダイアログの公開期間設定を取得
	 *
	 */
	private _exportScheduledPublishing() {
		const _publishDatetime: [string | null, string | null] = [null, null];
		const _unpublishDatetime: [string | null, string | null] = [null, null];
		this.$el.find('[data-bge-block-option-scheduled-publishing]').each((i, _el) => {
			const $input = $(_el);
			const type = $input.attr('data-bge-block-option-scheduled-publishing');
			const value = $input.val();
			if (!value || typeof value !== 'string') {
				return;
			}
			switch (type) {
				case 'publish-date': {
					_publishDatetime[0] = value;
					break;
				}
				case 'publish-time': {
					_publishDatetime[1] = value;
					break;
				}
				case 'unpublish-date': {
					_unpublishDatetime[0] = value;
					break;
				}
				case 'unpublish-time': {
					_unpublishDatetime[1] = value;
					break;
				}
			}
		});
		const publishDatetime =
			_publishDatetime[0] && _publishDatetime[1] ? _publishDatetime.join(' ') : _publishDatetime[0];
		const unpublishDatetime =
			_unpublishDatetime[0] && _unpublishDatetime[1] ? _unpublishDatetime.join(' ') : _unpublishDatetime[0];
		const scheduledPublishing: IScheduledPublishing = {
			publishDatetime: publishDatetime || null,
			unpublishDatetime: unpublishDatetime || null,
		};
		return scheduledPublishing;
	}
}
