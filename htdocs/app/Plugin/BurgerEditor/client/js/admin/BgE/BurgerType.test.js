import * as BgE from '../../../js/admin/BgE';
import { createElement, readBurgerTypeTemplate } from '../../../test/helper';
import BurgerType from '../../../js/admin/BgE/BurgerType';
import test from 'ava';

test('new', async t => {
	const name = 'download-file';
	const tmpl = await readBurgerTypeTemplate(name);
	const b = new BurgerType(tmpl);
	t.is(b.name, name);
});

test('new2', t => {
	const el = createElement('<div></div>');
	const b = new BurgerType(el);
	t.is(b.el.outerHTML, '<div data-bgt="unknown" data-bgt-ver="0.0.0"></div>');
});

test('export', async t => {
	const name = 'download-file';
	const tmpl = await readBurgerTypeTemplate(name);
	const b = new BurgerType(tmpl);
	t.deepEqual(b.export(), {
		path: '<!--?php echo baseUrl(); ?-->files/bgeditor/bg-sample.pdf',
		download: null,
		name: 'サンプルダウンロードファイル',
		'formated-size': '134.92kB',
		size: 138158,
	});
});

test('import', async t => {
	const name = 'download-file';
	const tmpl = await readBurgerTypeTemplate(name);
	const data = {
		path: '/path/to/file.ext',
		download: 'ふぁいるめい',
		name: '名称未設定フォルダ',
		'formated-size': '999.99MB',
		size: 9999999,
	};
	const b = new BurgerType(tmpl);
	await b.import(data);
	t.is(b.el.querySelector('[data-bge*="path:href"]').getAttribute('href'), data.path);
	t.is(b.el.querySelector('[data-bge*="download:download"]').download, data.download);
	t.is(b.el.querySelector('[data-bge*="name"]').innerHTML, '名称未設定フォルダ');
	t.is(b.el.querySelector('[data-bge*="formated-size"]').innerHTML, data['formated-size']);
	t.is(+b.el.querySelector('[data-bge*="size:data-size"]').getAttribute('data-size'), data.size);
});

test('export - custom elements', t => {
	const b = new BurgerType(`
		<div>
			<div data-bge="prop01">prop01-text</div>
			<div data-bge="prop02"><span id="prop02-html">prop02-html</span></div>
			<div data-bge="prop03:title" title="prop03-title"></div>
			<div data-bge="prop04:data-attr, prop05" data-attr="prop04-data-attr">prop05-text</div>
			<ul data-bge-list>
				<li data-bge="prop06">prop06-text-01</li>
				<li data-bge="prop06">prop06-text-02</li>
				<li data-bge="prop06">prop06-text-03</li>
			</ul>
			<ul data-bge-list>
				<li><a data-bge="prop07:href, prop08" href="prop07-href-01">prop08-text-01</a></li>
				<li><a data-bge="prop07:href, prop08" href="prop07-href-02">prop08-text-02</a></li>
			</ul>
			<ul data-bge-list>
				<li data-bge="prop09">prop09-text-01</li>
			</ul>
			<ul>
				<li data-bge="prop10">prop10-text-01</li>
				<li data-bge="prop10">prop10-text-02</li>
			</ul>
		</div>
	`);
	t.deepEqual(b.export(), {
		prop01: 'prop01-text',
		prop02: '<span id="prop02-html">prop02-html</span>',
		prop03: 'prop03-title',
		prop04: 'prop04-data-attr',
		prop05: 'prop05-text',
		prop06: ['prop06-text-01', 'prop06-text-02', 'prop06-text-03'],
		prop07: ['prop07-href-01', 'prop07-href-02'],
		prop08: ['prop08-text-01', 'prop08-text-02'],
		prop09: ['prop09-text-01'],
		prop10: 'prop10-text-02',
	});
});

test('import - custom elements', async t => {
	const b = new BurgerType(`
		<div>
			<div data-bge="prop01">prop01-text</div>
			<div data-bge="prop02"><span id="prop02-html">prop02-html</span></div>
			<div data-bge="prop03:title" title="prop03-title"></div>
			<div data-bge="prop03">prop03-multiple-element</div>
			<div data-bge="prop04:data-attr, prop05" data-attr="prop04-data-attr"></div>
			<ul data-bge-list>
				<li data-bge="prop06">prop06-text-01</li>
			</ul>
			<ul data-bge-list>
				<li><a data-bge="prop07:href, prop08" href="prop07-href-01">prop08-text-01</a></li>
			</ul>
		</div>
	`);
	await b.import({
		prop01: 'prop01-text-rewrite',
		prop02: '<span id="prop02-html">prop02-html-rewrite</span>',
		prop03: 'prop03-rewrite',
		prop04: 'prop04-data-attr-rewrite',
		prop05: 'prop05-text-write',
		prop06: ['prop06-text-01-rewrite', 'prop06-text-02-add', 'prop06-text-03-add', 'prop06-text-04-add'],
		prop07: [
			'prop07-href-01-rewrite',
			'prop07-herf-02-add',
			// empty item for test
		],
		prop08: ['prop08-text-01-rewrite', 'prop08-text-02-add', 'prop08-text-03-add'],
	});
	t.is(b.el.querySelector('[data-bge*="prop01"]').innerHTML, 'prop01-text-rewrite');
	t.is(b.el.querySelector('[data-bge*="prop02"]').innerHTML, '<span id="prop02-html">prop02-html-rewrite</span>');
	t.truthy(b.el.querySelector('#prop02-html'));
	t.is(b.el.querySelector('[data-bge="prop03:title"]').getAttribute('title'), 'prop03-rewrite');
	t.is(b.el.querySelector('[data-bge="prop03"]').innerHTML, 'prop03-rewrite');
	t.is(b.el.querySelector('[data-bge*="prop04:data-attr"]').getAttribute('data-attr'), 'prop04-data-attr-rewrite');
	t.is(b.el.querySelector('[data-bge*="prop05"]').innerHTML, 'prop05-text-write');
	t.is(b.el.querySelectorAll('[data-bge*="prop06"]').length, 4);
	t.is(b.el.querySelectorAll('[data-bge*="prop06"]').item(0).innerHTML, 'prop06-text-01-rewrite');
	t.is(b.el.querySelectorAll('[data-bge*="prop06"]').item(1).innerHTML, 'prop06-text-02-add');
	t.is(b.el.querySelectorAll('[data-bge*="prop06"]').item(2).innerHTML, 'prop06-text-03-add');
	t.is(b.el.querySelectorAll('[data-bge*="prop06"]').item(3).innerHTML, 'prop06-text-04-add');
	t.is(b.el.querySelectorAll('[data-bge*="prop07"]').length, 3);
	t.is(b.el.querySelectorAll('[data-bge*="prop07"]').item(0).getAttribute('href'), 'prop07-href-01-rewrite');
	t.is(b.el.querySelectorAll('[data-bge*="prop07"]').item(1).getAttribute('href'), 'prop07-herf-02-add');
	t.is(b.el.querySelectorAll('[data-bge*="prop07"]').item(2).getAttribute('href'), 'prop07-href-01-rewrite'); // empty item for test
	t.is(b.el.querySelectorAll('[data-bge*="prop08"]').length, 3);
	t.is(b.el.querySelectorAll('[data-bge*="prop08"]').item(0).innerHTML, 'prop08-text-01-rewrite');
	t.is(b.el.querySelectorAll('[data-bge*="prop08"]').item(1).innerHTML, 'prop08-text-02-add');
	t.is(b.el.querySelectorAll('[data-bge*="prop08"]').item(2).innerHTML, 'prop08-text-03-add');
});

test('import - custom elements (list remove)', async t => {
	const b = new BurgerType(`
		<div>
			<ul data-bge-list>
				<div><span data-bge="prop01">prop01-text</span></div>
			</ul>
		</div>
	`);
	await b.import({
		prop01: ['prop01-text-rewrite', 'prop01-text-add'],
	});
	t.is(b.el.querySelectorAll('[data-bge*="prop01"]')[0].innerHTML, 'prop01-text-rewrite');
	t.is(b.el.querySelectorAll('[data-bge*="prop01"]')[1].innerHTML, 'prop01-text-add');

	// list remove
	await b.import({
		prop01: ['prop01-text-rewrite'],
	});
	t.is(b.el.querySelectorAll('[data-bge*="prop01"]')[0].innerHTML, 'prop01-text-rewrite');
	t.false(!!b.el.querySelectorAll('[data-bge*="prop01"]')[1]);
	t.is(b.el.querySelector('[data-bge-list]').children.length, 1);
});

test('import - filter', async t => {
	const b = new BurgerType(`
		<div>
			<div data-bge="prop01">prop01-text</div>
		</div>
	`);
	await b.import({
		prop01: '<div data-bgb="hogehoge">hogehoge</div>',
	});
	t.is(b.el.querySelector('[data-bge*="prop01"]').innerHTML, '<div>hogehoge</div>');
});

test('upgrade', async t => {
	BgE.config.types = {
		sample: {
			version: '2.0.0',
			tmpl: `
				<div data-bgt="sample" data-bgt-ver="2.0.0">
					<div data-bge="a">A2</div>
					<div data-bge="b">B</div>
				</div>
			`,
		},
	};
	const b = new BurgerType(`
		<div data-bgt="sample" data-bgt-ver="1.0.0">
			<div data-bge="a">A</div>
		</div>
	`);
	t.true(b.isOld);
	t.is(b.version, '1.0.0');

	await b.upgrade();
	t.deepEqual(
		{
			a: 'A',
			b: 'B',
		},
		b.export(),
	);
	t.false(b.isOld);
	t.is(b.version, '2.0.0');
});
