/// <reference path="../../@types/BgE.d.ts" />

interface ITrimmedImageTypeContentData extends IBurgerTypeContentData {
	popup: boolean;
	empty: '1' | '0';
	path: string;
	alt: string;
	caption: string;
}

interface ITrimmedImageTypeCustomData {}

BgE.registerTypeModule<ITrimmedImageTypeContentData, ITrimmedImageTypeCustomData>('TrimmedImage', {
	change: (value, type) => {
		if (!value.popup) {
			const $a = $(type.el).find('a');
			$a.removeAttr('href');
		}
	},
});
