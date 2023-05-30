import test from 'ava';
import Helper from '../../../src/test/helper';
import BurgerType from '../../../src/js/admin/BgE/BurgerType';
import './init.js';
const TYPE_NAME = 'gallery';

test('export', async (t) => {
	const tmpl = await Helper.readBurgerTypeTemplate(TYPE_NAME);
	const b = new BurgerType(tmpl);
	t.deepEqual(
		b.export(),
		{
			autoplay: false,
			caption: [
				'',
			],
			ctrl: true,
			delay: 3000,
			duration: 600,
			effect: 'fade',
			marker: 'thumbs',
			path: [
				'<!--?php echo baseUrl(); ?-->files/bgeditor/bg-sample.png',
			],
		},
	);
});

test('import', async (t) => {
	const tmpl = await Helper.readBurgerTypeTemplate(TYPE_NAME);
	const data = {
		autoplay: true,
		caption: [
			'lorem',
			'ipsam',
			'foo',
			'bar',
		],
		ctrl: false,
		delay: 30000,
		duration: 6000,
		effect: 'slide',
		marker: 'dots',
		path: [
			'/path/to/1',
			'/path/to/2',
			'/path/to/3',
			'/path/to/4',
		],
	};
	const b = new BurgerType(tmpl);
	await b.import(data);
	t.is(b.el.querySelector('[data-bge*="autoplay"]').getAttribute('data-gallery-autoplay'), `${data.autoplay}`);
	t.is(b.el.querySelectorAll('[data-bge*="caption"]')[0].innerHTML, data.caption[0]);
	t.is(b.el.querySelectorAll('[data-bge*="caption"]')[1].innerHTML, data.caption[1]);
	t.is(b.el.querySelectorAll('[data-bge*="caption"]')[2].innerHTML, data.caption[2]);
	t.is(b.el.querySelectorAll('[data-bge*="caption"]')[3].innerHTML, data.caption[3]);
	t.is(b.el.querySelectorAll('[data-bge*="caption"]').length, 4);
	t.is(b.el.querySelector('[data-bge*="ctrl"]').getAttribute('data-gallery-ctrl'), `${data.ctrl}`);
	t.is(b.el.querySelector('[data-bge*="delay"]').getAttribute('data-gallery-delay'), `${data.delay}`);
	t.is(b.el.querySelector('[data-bge*="duration"]').getAttribute('data-gallery-duration'), `${data.duration}`);
	t.is(b.el.querySelector('[data-bge*="effect"]').getAttribute('data-gallery-effect'), data.effect);
	t.is(b.el.querySelector('[data-bge*="marker"]').getAttribute('data-gallery-marker'), data.marker);
	t.is(b.el.querySelectorAll('[data-bge*="path"]')[0].getAttribute('src'), data.path[0]);
	t.is(b.el.querySelectorAll('[data-bge*="path"]')[1].getAttribute('src'), data.path[1]);
	t.is(b.el.querySelectorAll('[data-bge*="path"]')[2].getAttribute('src'), data.path[2]);
	t.is(b.el.querySelectorAll('[data-bge*="path"]')[3].getAttribute('src'), data.path[3]);
	t.is(b.el.querySelectorAll('[data-bge*="path"]').length, 4);
});
