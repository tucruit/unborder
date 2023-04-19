import * as BgE from '../BgE';
import BurgerEditorElement from './BurgerEditorElement';
import ContentArea from './ContentArea';

export default class InitialInsertionButton extends BurgerEditorElement {
	private _contentArea: ContentArea;

	constructor(contentArea: ContentArea) {
		super(document.createElement('div'));

		this._contentArea = contentArea;

		this._node.classList.add('nodata-button');
		this._node.setAttribute('data-bge', 'initial-insertion');
		this._node.innerHTML = '<button class="insert_after" type="button">下に要素を追加</button>';

		const button = this._node.querySelector('button');
		if (button) {
			button.addEventListener('click', this._insert.bind(this));
		}
	}

	private _insert() {
		if (BgE.editorStatus.isProcessed) {
			return;
		}
		this.hide();
		BgE.insertionPoint.initSet();
		BgE.blockListDialog.open();
	}
}
