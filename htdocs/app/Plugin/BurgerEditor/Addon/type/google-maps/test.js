import test from 'ava';
import Helper from '../../../src/test/helper';
import BurgerType from '../../../src/js/admin/BgE/BurgerType';
import './init.js';
import * as BgE from '../../../src/js/admin/BgE';
const TYPE_NAME = 'google-maps';

test('export', async (t) => {
	const tmpl = await Helper.readBurgerTypeTemplate(TYPE_NAME);
	const b = new BurgerType(tmpl);
	t.deepEqual(
		b.export(),
		{
			lat: 35.681382,
			lng: 139.766084,
			zoom: 16,
			url: '//maps.apple.com/?q=35.681382,139.766084',
		},
	);
});

test('import', async (t) => {
	const tmpl = await Helper.readBurgerTypeTemplate(TYPE_NAME);
	const data = {
		lat: 100,
		lng: 200,
		zoom: 20,
		url: '//maps.apple.com/?q=',
	};
	const b = new BurgerType(tmpl);
	await b.import(data);
	t.is(b.el.querySelector('[data-bge*="lat"]').getAttribute('data-lat'), `${data.lat}`);
	t.is(b.el.querySelector('[data-bge*="lng"]').getAttribute('data-lng'), `${data.lng}`);
	t.is(b.el.querySelector('[data-bge*="zoom"]').getAttribute('data-zoom'), `${data.zoom}`);
	t.is(b.el.querySelector('[data-bge*="url"]').getAttribute('href'), data.url);
});
