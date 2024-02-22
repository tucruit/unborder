import test from 'ava';
import Helper from '../../../src/test/helper';
import BurgerType from '../../../src/js/admin/BgE/BurgerType';
import './init.js';
import * as BgE from '../../../src/js/admin/BgE';
const TYPE_NAME = 'title-h2';

test('export', async (t) => {
	const tmpl = await Helper.readBurgerTypeTemplate(TYPE_NAME);
	const b = new BurgerType(tmpl);
	t.deepEqual(
		b.export(),
		{
			'title-h2': '見出しを入力してください',
		},
	);
});

test('import', async (t) => {
	const tmpl = await Helper.readBurgerTypeTemplate(TYPE_NAME);
	const data = {
		'title-h2': '見出し',
	};
	const b = new BurgerType(tmpl);
	await b.import(data);
	t.is(b.el.querySelector('[data-bge*="title-h2"]').innerHTML, data['title-h2']);
});
