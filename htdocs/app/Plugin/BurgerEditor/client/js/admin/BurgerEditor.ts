import BurgerBlock from './BgE/BurgerBlock';

export default class BurgerEditor {
	private _currentBlock: BurgerBlock | null = null;

	public setCurrentBlock(block: BurgerBlock) {
		// if (window.console) {
		// 	console.info(`set block: ${block.name}`);
		// }
		let isChanged = true;
		if (this._currentBlock) {
			isChanged = !this._currentBlock.is(block);
		}
		this._currentBlock = block;
		return isChanged;
	}

	public clearCurrentBlock() {
		// if (window.console) {
		// 	if (this._currentBlock) {
		// 		console.info(`clear block: ${this._currentBlock.name}`);
		// 	} else {
		// 		console.info(`block is already empty`);
		// 	}
		// }
		this._currentBlock = null;
	}

	public getCurrentBlock() {
		if (!this._currentBlock) {
			// eslint-disable-next-line no-console
			console.warn('block is unselected.');
		}
		return this._currentBlock;
	}

	public isSetBlock() {
		return !!this._currentBlock;
	}
}
