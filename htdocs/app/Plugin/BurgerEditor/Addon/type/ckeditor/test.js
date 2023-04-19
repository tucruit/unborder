import test from 'ava';
import Helper from '../../../src/test/helper';
import BurgerType from '../../../src/js/admin/BgE/BurgerType';
import './init.js';
import * as BgE from '../../../src/js/admin/BgE';
const TYPE_NAME = 'ckeditor';

test('export', async (t) => {
	const tmpl = await Helper.readBurgerTypeTemplate(TYPE_NAME);
	const b = new BurgerType(tmpl);
	t.deepEqual(
		b.export(),
		{
			ckeditor: '本文を入力してください',
		},
	);
});

test('import', async (t) => {
	const tmpl = await Helper.readBurgerTypeTemplate(TYPE_NAME);
	const data = {
		ckeditor: 'TEXT_TEXT_TEXT',
	};
	const b = new BurgerType(tmpl);
	await b.import(data);
	t.is(b.el.querySelector('[data-bge="ckeditor"]').innerHTML, data.ckeditor);
});
