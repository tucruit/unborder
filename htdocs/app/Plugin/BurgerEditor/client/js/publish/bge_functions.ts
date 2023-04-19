import { BurgerFunctionsOptions, options as _options } from './options';
import { gallery } from './libs/gallery';
import { googleMaps } from './libs/google-maps';
import { migration } from './migration';
import { youtube } from './libs/youtube';

// eslint-disable-next-line @typescript-eslint/no-namespace
namespace BurgerFunctions {
	export const options = _options;

	export const execute = (root: Document | HTMLElement, options: BurgerFunctionsOptions) => {
		const $root = $(root);

		migration($root);

		/**
		 * Use Google Maps
		 *
		 */
		$root.find('.bgt-google-maps').each((i, el) => googleMaps(el));

		/**
		 * Use YouTube
		 *
		 */
		$root.find('.bgt-youtube').each((i, el) => youtube(el));

		/**
		 * Use Gallery
		 */
		$root.find('[data-bgt="gallery"]').each(options.gallery || gallery);
	};
}

window['BgE'] = { ...window.BgE, ...BurgerFunctions };

window.addEventListener('DOMContentLoaded', () =>
	// @ts-ignore
	BurgerFunctions.execute(document, window['BgE'].options),
);
