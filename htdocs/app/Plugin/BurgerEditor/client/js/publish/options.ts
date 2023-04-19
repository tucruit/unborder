export interface BurgerFunctionsOptions {
	colorbox: ColorboxSettings;
	ofi: boolean;
	gallery?: (this: HTMLElement, i: number, el: HTMLElement) => void;
}

export const options: BurgerFunctionsOptions = {
	/**
	 * カラーボックス設定
	 *
	 */
	colorbox: {
		maxWidth: '95%',
		maxHeight: '95%',
	},
	ofi: true,
};
