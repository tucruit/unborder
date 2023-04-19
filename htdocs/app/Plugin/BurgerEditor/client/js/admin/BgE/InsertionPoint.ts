import * as BgE from '../BgE';
import BurgerBlock from './BurgerBlock';
import BurgerEditorElement from './BurgerEditorElement';

export default class InsertionPoint extends BurgerEditorElement {
	constructor() {
		super(document.createElement('div'));

		this._node.setAttribute('data-bge', 'insertion-point');
	}

	public initSet() {
		BgE.currentContentArea.containerElement.appendChild(this._node);
	}

	public set(targetBlock: BurgerBlock, after: boolean = true) {
		const targetElement: HTMLElement = targetBlock.node;
		if (after) {
			BgE.currentContentArea.containerElement.insertBefore(this._node, targetElement.nextSibling);
		} else {
			BgE.currentContentArea.containerElement.insertBefore(this._node, targetElement);
		}
	}

	public unset() {
		if (this._node.innerHTML === '') {
			if (this._node.remove) {
				this._node.remove();
			} else {
				if (this._node.parentNode) {
					this._node.parentNode.removeChild(this._node);
				}
			}
		}
	}

	public insert(insertionBlock: BurgerBlock) {
		return new Promise<BurgerBlock>((resolve, reject) => {
			BgE.editorStatus.isProcessed = true;
			this._node.appendChild(insertionBlock.node);
			BgE.currentContentArea.update();
			this._node.style.height = 'auto';
			this._node.style.height = `${this._node.getBoundingClientRect().height}px`;
			const $addPoint = $(this._node);
			$addPoint
				.hide()
				.slideDown()
				.promise()
				.done(() => {
					$(insertionBlock.node).unwrap(); // unwrapメソッドでthis._nodeはnullにならない
					BgE.editorStatus.isProcessed = false;
					BgE.save();
					resolve(insertionBlock);
				});
		});
	}
}
