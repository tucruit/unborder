import test from 'ava';
import Helper from '../../../src/test/helper';
import BurgerType from '../../../src/js/admin/BgE/BurgerType';
import './init.js';
const TYPE_NAME = 'download-file';

test('export', async (t) => {
	const tmpl = await Helper.readBurgerTypeTemplate(TYPE_NAME);
	const b = new BurgerType(tmpl);
	t.deepEqual(
		b.export(),
		{
			path: '<!--?php echo baseUrl(); ?-->files/bgeditor/bg-sample.pdf',
			download: null,
			name: 'サンプルダウンロードファイル',
			'formated-size': '134.92kB',
			size: 138158,
		},
	);
});

test('import', async (t) => {
	const tmpl = await Helper.readBurgerTypeTemplate(TYPE_NAME);
	const data = {
		path: '/path/to/file.ext',
		download: true,
		name: 'ふぁいるめい',
		'formated-size': '999.99MB',
		size: 9999999,
	};
	const b = new BurgerType(tmpl);
	await b.import(data, true);
	t.is(b.el.querySelector('[data-bge*="path:href"]').getAttribute('href'), data.path);
	t.is(b.el.querySelector('[data-bge*="download:download"]').download, data.name);
	t.is(b.el.querySelector('[data-bge*="name"]').innerHTML, data.name);
	t.is(b.el.querySelector('[data-bge*="formated-size"]').innerHTML, data['formated-size']);
	t.is(b.el.querySelector('[data-bge*="size:data-size"]').getAttribute('data-size'), `${data.size}`);
});

test('import2', async (t) => {
	const tmpl = await Helper.readBurgerTypeTemplate(TYPE_NAME);
	const data = {
		path: '/path/to/file.ext',
		download: true,
		name: '',
		'formated-size': '999.99MB',
		size: 9999999,
	};
	const b = new BurgerType(tmpl);
	await b.import(data, true);
	t.is(b.el.querySelector('[data-bge*="path:href"]').getAttribute('href'), data.path);
	t.is(b.el.querySelector('[data-bge*="download:download"]').download, data.path);
	t.is(b.el.querySelector('[data-bge*="name"]').innerHTML, data.name);
	t.is(b.el.querySelector('[data-bge*="formated-size"]').innerHTML, data['formated-size']);
	t.is(b.el.querySelector('[data-bge*="size:data-size"]').getAttribute('data-size'), `${data.size}`);
});

test('import3', async (t) => {
	const tmpl = await Helper.readBurgerTypeTemplate(TYPE_NAME);
	const data = {
		path: '/path/to/file.ext',
		download: false,
		name: '',
		'formated-size': '999.99MB',
		size: 9999999,
	};
	const b = new BurgerType(tmpl);
	await b.import(data);
	t.is(b.el.querySelector('[data-bge*="path:href"]').getAttribute('href'), data.path);
	t.is(b.el.querySelector('[data-bge*="download:download"]').hasAttribute('download'), false);
	t.is(b.el.querySelector('[data-bge*="name"]').innerHTML, data.name);
	t.is(b.el.querySelector('[data-bge*="formated-size"]').innerHTML, data['formated-size']);
	t.is(b.el.querySelector('[data-bge*="size:data-size"]').getAttribute('data-size'), `${data.size}`);
});
