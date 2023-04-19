import test from 'ava';
import Helper from '../../../src/test/helper';
import BurgerType from '../../../src/js/admin/BgE/BurgerType';
import './init.js';
const TYPE_NAME = 'table';

test('export', async (t) => {
	const tmpl = await Helper.readBurgerTypeTemplate(TYPE_NAME);
	const b = new BurgerType(tmpl);
	t.deepEqual(
		b.export(),
		{
			summary: '',
			caption: '',
			'th-0': '表組の見出し',
			'td-0': '表組の内容を入力してください',
		},
	);
});

test('import', async (t) => {
	const tmpl = await Helper.readBurgerTypeTemplate(TYPE_NAME);
	const data = {
		summary: 'サマリ',
		caption: 'キャプション',
		'th-0': '見出し0',
		'td-0': '内容0',
		'th-1': '見出し1',
		'td-1': '内容1',
		'th-2': '見出し2',
		'td-2': '内容2',
		'th-3': '見出し3',
		'td-3': '内容3',
	};
	const b = new BurgerType(tmpl);
	await b.import(data, true);
	t.is(b.el.querySelector('[data-bge*="summary"]').summary, data.summary);
	t.is(b.el.querySelector('[data-bge*="caption"]').innerHTML, data.caption);
	t.is(b.el.querySelector('[data-bge*="th-0"]').innerHTML, data['th-0']);
	t.is(b.el.querySelector('[data-bge*="td-0"]').innerHTML, data['td-0']);
	t.is(b.el.querySelector('[data-bge*="th-1"]').innerHTML, data['th-1']);
	t.is(b.el.querySelector('[data-bge*="td-1"]').innerHTML, data['td-1']);
	t.is(b.el.querySelector('[data-bge*="th-2"]').innerHTML, data['th-2']);
	t.is(b.el.querySelector('[data-bge*="td-2"]').innerHTML, data['td-2']);
	t.is(b.el.querySelector('[data-bge*="th-3"]').innerHTML, data['th-3']);
	t.is(b.el.querySelector('[data-bge*="td-3"]').innerHTML, data['td-3']);
});
