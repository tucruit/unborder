import test from 'ava';
import Helper from '../../../src/test/helper';
import BurgerType from '../../../src/js/admin/BgE/BurgerType';
import BurgerBlock from '../../../src/js/admin/BgE/BurgerBlock';
import Migrator from '../../../src/js/admin/BgE/Migrator';
import * as BgE from '../../../src/js/admin/BgE';
import './init.js';
const TYPE_NAME = 'button';

test('export', async (t) => {
	const tmpl = await Helper.readBurgerTypeTemplate(TYPE_NAME);
	const b = new BurgerType(tmpl);
	t.deepEqual(
		b.export(),
		{
			link: '',
			target: '',
			text: 'ボタン',
			kind: 'link',
		},
	);
});

test('import', async (t) => {
	const tmpl = await Helper.readBurgerTypeTemplate(TYPE_NAME);
	const data = {
		link: 'path/to/link',
		target: '_blank',
		text: 'テキスト',
		kind: 'em',
	};
	const b = new BurgerType(tmpl);
	await b.import(data);
	t.is(b.el.querySelector('[data-bge*="link:href"]').getAttribute('href'), data.link);
	t.is(b.el.querySelector('[data-bge*="target:target"]').target, data.target);
	t.is(b.el.querySelector('[data-bge*="text"]').innerHTML, data.text);
	t.is(b.el.querySelector('[data-bge*="kind"]').getAttribute('data-bgt-button-kind'), data.kind);
});

test('import', async (t) => {
	const tmpl = await Helper.readBurgerTypeTemplate(TYPE_NAME);
	const data = {
		link: 'path/to/link',
		target: 'custom-target',
		text: 'テキスト',
		kind: 'em',
	};
	const b = new BurgerType(tmpl);
	await b.import(data);
	t.is(b.el.querySelector('[data-bge*="link:href"]').getAttribute('href'), data.link);
	t.is(b.el.querySelector('[data-bge*="target:target"]').target, 'custom-target');
	t.is(b.el.querySelector('[data-bge*="text"]').innerHTML, data.text);
	t.is(b.el.querySelector('[data-bge*="kind"]').getAttribute('data-bgt-button-kind'), data.kind);
});

test('migrate', async (t) => {
	BgE.config.types = {
		[TYPE_NAME]: await Helper.readBurgerType(TYPE_NAME),
	};
	const old = Helper.createElement(`
		<div data-bgb="button" class="bgb-button">
			<div data-bgt="button" data-bgt-ver="2.1.0" class="bgt-container bgt-button-container">
				<div class="bgt-btn-container">
					<a class="bgt-btn bgt-btn--link" role="button" href="" data-bge="link:href, target:target">
						<span class="bgt-btn__text" data-bge="text">ボタン</span>
					</a>
				</div>
				<input type="hidden" data-bge="type">
			</div>
		</div>
	`);
	const newHTML = `
		<div data-bgb="button" class="bgb-button">
			<div data-bgt="button" data-bgt-ver="2.13.0" class="bgt-container bgt-button-container">
				<div class="bgt-btn-container" data-bgt-button-kind="link" data-bge="kind:data-bgt-button-kind">
					<a class="bgt-btn" role="button" href="" data-bge="link:href, target:target">
						<span class="bgt-btn__text" data-bge="text">ボタン</span>
					</a>
				</div>
			</div>
		</div>
	`;
	const block = new BurgerBlock(old);
	await Migrator.migration(block.node);
	t.is(block.node.outerHTML.replace(/\t|\n/g, ''), newHTML.replace(/\t|\n/g, ''));
});
