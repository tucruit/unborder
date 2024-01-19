import test from 'ava';
import Helper from '../../../src/test/helper';
import BurgerBlock from '../../../src/js/admin/BgE/BurgerBlock';
import Migrator from '../../../src/js/admin/BgE/Migrator';
import * as BgE from '../../../src/js/admin/BgE';
import './init.js';

const TYPE_NAME = 'hr';

test('migrate', async (t) => {
	BgE.config.types = {
		[TYPE_NAME]: await Helper.readBurgerType(TYPE_NAME),
	};
	const oldHTML = `
		<div data-bgb="hr" class="bgb-hr">
			<div data-bgt="hr" data-bgt-ver="2.11.0" class="bgt-container bgt-hr-container">
				<div class="bgt-hr-container">
					<hr class="bgt-hr bgt-hr--HOGEHOGE">
				</div>
				<input type="hidden" data-bge="type" value="bgt-hr--HOGEHOGE">
			</div>
		</div>
	`;
	const el = Helper.createElement(oldHTML);
	const newHTML = `
		<div data-bgb="hr" class="bgb-hr">
			<div data-bgt="hr" data-bgt-ver="2.12.0" class="bgt-container bgt-hr-container">
				<div class="bgt-hr-container" data-bgt-hr-kind="HOGEHOGE" data-bge="kind:data-bgt-hr-kind">
					<hr class="bgt-hr">
				</div>
			</div>
		</div>
	`;
	const block = new BurgerBlock(el);
	await Migrator.migration(block.node);
	t.is(block.node.outerHTML.replace(/\t|\n/g, ''), newHTML.replace(/\t|\n/g, ''));
});

test('migrate - default is primary', async (t) => {
	BgE.config.types = {
		[TYPE_NAME]: await Helper.readBurgerType(TYPE_NAME),
	};
	const oldHTML = `
		<div data-bgb="hr" class="bgb-hr">
			<div data-bgt="hr" data-bgt-ver="2.11.0" class="bgt-container bgt-hr-container">
				<div class="bgt-hr-container">
					<hr class="bgt-hr">
				</div>
			</div>
		</div>
	`;
	const el = Helper.createElement(oldHTML);
	const newHTML = `
		<div data-bgb="hr" class="bgb-hr">
			<div data-bgt="hr" data-bgt-ver="2.12.0" class="bgt-container bgt-hr-container">
				<div class="bgt-hr-container" data-bgt-hr-kind="primary" data-bge="kind:data-bgt-hr-kind">
					<hr class="bgt-hr">
				</div>
			</div>
		</div>
	`;
	const block = new BurgerBlock(el);
	await Migrator.migration(block.node);
	t.is(block.node.outerHTML.replace(/\t|\n/g, ''), newHTML.replace(/\t|\n/g, ''));
});
