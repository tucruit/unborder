import * as BgE from '../../BgE';
import BurgerType from '../BurgerType';
import EditorComponent from './EditorComponent';
import TypeEditorDialog from '../editorDialog/TypeEditorDialog';

/**
 * ファイルアップロードメッセンジャークラス
 */
export default class MultiFieldSelection extends EditorComponent {
	private _$listRoot: JQuery;
	private _$listItemTmpl: JQuery;

	/**
	 * コンストラクタ
	 *
	 * @param el コンポーネントの要素
	 * @param module コンポーネントが使用されるタイプモジュール
	 *
	 */
	constructor(el: HTMLElement, editorDialog: TypeEditorDialog) {
		super(el, editorDialog);
		BgE.componentObserver.on('bge-multi-field-add', this._add, this);
		this._$listRoot = this.$el.find('[data-bge-list]');
		this._$listItemTmpl = this._$listRoot.children().first().clone();
	}

	private _add(data: BgE.IBurgerTypeContentData) {
		const $item = this._$listItemTmpl.clone();
		for (const datumName of Object.keys(data)) {
			const datum = data[datumName];
			const $input = $item.find(`[name="bge-${datumName}"]`);
			$input.val(`${datum}`);
			const $view = $item.find(`[data-bge*="${datumName}"]`);
			if ($view.length) {
				BurgerType.datumToElement(datumName, `${datum}`, $view.get(0));
			}
		}
		this._$listRoot.append($item);
	}
}
