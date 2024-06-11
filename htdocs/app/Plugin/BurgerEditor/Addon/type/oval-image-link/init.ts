/// <reference path="../../@types/BgE.d.ts" />

interface ImageLinkTypeContentData extends IBurgerTypeContentData {
	link: string;
	target: '_blank' | '';
	rel: string | null;
	hr: boolean;
	empty: '1' | '0';
	path: string;
	alt: string;
	caption: string;
	width: number | 'auto';
	height: number | 'auto';
	srcset: string;
	lazy: boolean;
	loading: 'eager' | 'lazy';
	decoding: 'sync' | 'async' | 'auto';
}

interface ImageLinkTypeCustomData {}

BgE.registerTypeModule<ImageLinkTypeContentData, ImageLinkTypeCustomData>('ImageLink', {
	open: (editorDialog, type) => {
		const data = type.export();
		if (data.loading === 'eager' && data.decoding === 'auto') {
			editorDialog.$el.find('[name="bge-lazy"]').prop('checked', false);
		}
	},
	beforeChange: value => {
		return new Promise<void>(resolve => {
			// 入力はチェックボックスなので論理値
			value.target = value.target ? '_blank' : '';
			value.rel = value.target ? 'noopener noreferrer' : null;

			const img = new Image();
			const scale = value.hr ? 2 : 1;
			const ORIGIN = '__org';
			const pathParse = value.path.match(/^(.*)(\.(?:jpe?g|gif|png|webp))$/i);
			img.onload = () => {
				value.width = img.width / scale;
				value.height = img.height / scale;
				resolve();
			};
			const error = () => {
				value.width = 'auto';
				value.height = 'auto';
				img.src = value.path;
				value.srcset = '';
				resolve();
			};
			if (value.empty === '1') {
				error();
				return;
			}
			img.onerror = error;
			img.onabort = error;
			if (pathParse) {
				const [, name, ext] = pathParse;
				img.src = `${name}${ORIGIN}${ext}`;
				value.srcset = `${name}${ext}, ${name}${ORIGIN}${ext} 2x`;
			} else {
				img.src = value.path;
				value.srcset = '';
			}
			if (value.lazy) {
				value.loading = 'lazy';
				value.decoding = 'async';
			} else {
				value.loading = 'eager';
				value.decoding = 'auto';
			}
		});
	},
});
