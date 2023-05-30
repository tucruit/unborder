import 'psycle/lib/classes/PsycleTransitionFade';
import 'psycle/lib/classes/PsycleTransitionSlide';
import Psycle from 'psycle';
import objectFitImagesFromNPM from 'object-fit-images';
import { options } from '../options';

export function gallery(this: HTMLElement, i: number, el: HTMLElement) {
	const $this = $(el);
	const $gallery = $this.find('.bgt-gallery');
	const p = new Psycle($gallery, {
		transition: $gallery.attr('data-gallery-effect'),
		container: '.bgt-gallery__group',
		panels: '.bgt-gallery__item',
		dragBlockVertical: true,
		draggable: true,
		swipeable: true,
		duration: parseInt($gallery.attr('data-gallery-duration') || '', 10),
		delay: parseInt($gallery.attr('data-gallery-delay') || '', 10),
		auto: $gallery.attr('data-gallery-autoplay') === 'true',
		repeat: 'loop',
	});

	const $m = p.marker();
	$m.addClass('bgt-gallery-marker');
	$m.appendTo($this);
	$m.find('li').each((liI, liEl) => {
		const img = $this.find('.bgt-gallery__item').eq(liI).find('img').prop('src');
		if (img) {
			liEl.style.backgroundImage = `url("${img}")`;
		}
	});

	if ($gallery.attr('data-gallery-ctrl') === 'true') {
		const $ctrl = $(`<div class="bgt-gallery-ctrl">
			<button class="bgt-gallery-ctrl__prev"><span>Prev</span></button>
			<button class="bgt-gallery-ctrl__next"><span>Next</span></button>
		</div>`);
		$ctrl.appendTo($gallery);
		p.controller($ctrl, {
			prev: '.bgt-gallery-ctrl__prev',
			next: '.bgt-gallery-ctrl__next',
			ifFirstClass: 'bgt-gallery-ctrl__state-is-first',
			ifLastClass: 'bgt-gallery-ctrl__state-is-last',
			ifIgnoreClass: 'bgt-gallery-ctrl__state-is-ignore',
		});
	}

	/**
	 * Use object-fit-images
	 * Polyfill object-fit/object-position (https://github.com/bfred-it/object-fit-images)
	 */
	if (options.ofi) {
		if ('objectFitImages' in window) {
			// @ts-ignore
			window['objectFitImages']('[data-bgt="gallery"] img');
		} else {
			objectFitImagesFromNPM('[data-bgt="gallery"] img');
		}
	}
}
