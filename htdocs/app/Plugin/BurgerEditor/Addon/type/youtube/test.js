import test from 'ava';
import Helper from '../../../src/test/helper';
import BurgerType from '../../../src/js/admin/BgE/BurgerType';
import './init.js';
import * as BgE from '../../../src/js/admin/BgE';
const TYPE_NAME = 'youtube';

test('export', async (t) => {
	const tmpl = await Helper.readBurgerTypeTemplate(TYPE_NAME);
	const b = new BurgerType(tmpl);
	t.deepEqual(
		b.export(),
		{
			id: 'FUgM105uN4c',
			thumb: '//img.youtube.com/vi/FUgM105uN4c/mqdefault.jpg',
		},
	);
});

test('import', async (t) => {
	const tmpl = await Helper.readBurgerTypeTemplate(TYPE_NAME);
	const data = {
		id: 'XXXXXXXXXX',
		thumb: '//img.youtube.com/vi/XXXXXXXXXX/mqdefault.jpg',
	};
	const b = new BurgerType(tmpl);
	await b.import(data);
	t.is(b.el.querySelector('[data-bge*="id"]').getAttribute('data-id'), data.id);
	t.is(b.el.querySelector('[data-bge*="thumb"]').getAttribute('src'), data.thumb);
});
