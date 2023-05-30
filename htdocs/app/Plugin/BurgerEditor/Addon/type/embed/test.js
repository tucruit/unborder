import test from 'ava';
import Helper from '../../../src/test/helper';
import BurgerType from '../../../src/js/admin/BgE/BurgerType';
import './init.js';
const TYPE_NAME = 'embed';

test('export', async (t) => {
	const tmpl = await Helper.readBurgerTypeTemplate(TYPE_NAME);
	const b = new BurgerType(tmpl);
	t.deepEqual(
		b.export(),
		{
			'embed-code': '',
			'embed-label': '埋め込みタグ',
		},
	);
});

test('import', async (t) => {
	const tmpl = await Helper.readBurgerTypeTemplate(TYPE_NAME);
	const data = {
		'embed-code': '<code></code>',
		'embed-label': 'カスタムラベル',
	};
	const b = new BurgerType(tmpl);
	await b.import(data, true);
	t.is(b.el.querySelector('[data-bge="embed-code"]').innerHTML, 'PGNvZGU+PC9jb2RlPg==');
	t.is(b.el.querySelector('[data-bge="embed-label"]').innerHTML, data['embed-label']);
});
