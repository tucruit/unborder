import { extractFormData, setForm } from '../../../../js/admin/BgE/editorDialog/TypeEditorDialog';
import { createElement } from '../../../../test/helper';
import test from 'ava';

test('import', async t => {
	const form = createElement(`
		<div>
			<input type="hidden" name="bge-path">
			<input type="hidden" name="bge-formated-size" value="0">
			<input type="hidden" name="bge-size" value="0">
			<input type="text" name="bge-name" placeholder="サンプルダウンロードファイル">
			<input type="text" name="bge-name2" placeholder="サンプルダウンロードファイル">
			<input type="checkbox" name="bge-download" value="bge:checked" checked>
			<input type="checkbox" name="bge-download2" value="bge:checked" checked>
			<input type="checkbox" name="bge-download3" checked>
			<input type="checkbox" name="bge-download4">
			<input type="radio" name="bge-switch" value="a">
			<input type="radio" name="bge-switch" value="b">
			<input type="radio" name="bge-switch" value="c">
			<ul data-bge-list>
				<li><input type="text" name="bge-name3"></li>
			</ul>
			<ul data-bge-list>
				<li>
					<input type="hidden" name="bge-view">
					<label data-bge="view"></label>
				</li>
			</ul>
		</div>
	`);
	setForm(form, {
		path: '/path/to/file.ext',
		'formated-size': '999.99MB',
		size: '9999999',
		name: 'ふぁいるめい',
		name2: 'サンプルダウンロードファイル',
		download: true,
		download2: false,
		download3: 'false',
		download4: true,
		switch: 'b',
		name3: ['zero', 'one', 'two', 'three', 'four'],
		view: ['a', 'b', 'c'],
	});
	t.is(form.querySelector('[name="bge-path"]').value, '/path/to/file.ext', 'path');
	t.is(form.querySelector('[name="bge-formated-size"]').value, '999.99MB', 'formated-size');
	t.is(form.querySelector('[name="bge-size"]').value, '9999999', 'size');
	t.is(form.querySelector('[name="bge-name"]').value, 'ふぁいるめい', 'name');
	t.is(form.querySelector('[name="bge-name2"]').value, '', 'name2');
	t.is(form.querySelector('[name="bge-download"]').checked, true, 'download');
	t.is(form.querySelector('[name="bge-download2"]').checked, false, 'download2');
	t.is(form.querySelector('[name="bge-download3"]').checked, false, 'download3');
	t.is(form.querySelector('[name="bge-download4"]').checked, true, 'download4');
	t.is(form.querySelectorAll('[name="bge-switch"]')[0].checked, false, 'bge-switch');
	t.is(form.querySelectorAll('[name="bge-switch"]')[1].checked, true, 'bge-switch');
	t.is(form.querySelectorAll('[name="bge-switch"]')[2].checked, false, 'bge-switch');
	t.is(form.querySelectorAll('[name="bge-name3"]').length, 5, 'name3 length');
	t.is(form.querySelectorAll('[name="bge-name3"]').item(0).value, 'zero');
	t.is(form.querySelectorAll('[name="bge-name3"]').item(1).value, 'one');
	t.is(form.querySelectorAll('[name="bge-name3"]').item(2).value, 'two');
	t.is(form.querySelectorAll('[name="bge-name3"]').item(3).value, 'three');
	t.is(form.querySelectorAll('[name="bge-name3"]').item(4).value, 'four');
	t.is(form.querySelectorAll('[name="bge-view"]').length, 3);
	t.is(form.querySelectorAll('[name="bge-view"]').item(0).value, 'a');
	t.is(form.querySelectorAll('[name="bge-view"]').item(1).value, 'b');
	t.is(form.querySelectorAll('[name="bge-view"]').item(2).value, 'c');
	t.is(form.querySelectorAll('[data-bge="view"]').length, 3);
	t.is(form.querySelectorAll('[data-bge="view"]').item(0).innerHTML, 'a');
	t.is(form.querySelectorAll('[data-bge="view"]').item(1).innerHTML, 'b');
	t.is(form.querySelectorAll('[data-bge="view"]').item(2).innerHTML, 'c');
});

test('export', async t => {
	const form = createElement(`
		<div>
			<input type="hidden" name="bge-path" value="/path/to/file.ext">
			<input type="hidden" name="bge-formated-size" value="999.99MB">
			<input type="hidden" name="bge-size" value="9999999">
			<input type="text" name="bge-name" placeholder="サンプルダウンロードファイル" value="ふぁいるめい">
			<input type="text" name="bge-name2" placeholder="サンプルダウンロードファイル" value="サンプルダウンロードファイル">
			<input type="checkbox" name="bge-download" value="bge:checked" checked>
			<input type="checkbox" name="bge-download2" value="bge:checked">
			<input type="checkbox" name="bge-download3" checked>
			<input type="checkbox" name="bge-download4">
			<input type="radio" name="bge-switch" value="a">
			<input type="radio" name="bge-switch" value="b" checked>
			<input type="radio" name="bge-switch" value="c">
			<div data-bge-list>
				<input type="hidden" name="bge-pathes" value="/path/to/file-1.ext">
				<input type="hidden" name="bge-pathes" value="/path/to/file-2.ext">
				<input type="hidden" name="bge-pathes" value="/path/to/file-3.ext">
			</div>
			<div data-bge-list>
				<input type="hidden" name="bge-array" value="array-item">
			</div>
		</div>
	`);
	const data = extractFormData(form);
	t.deepEqual(
		{
			path: '/path/to/file.ext',
			'formated-size': '999.99MB',
			size: '9999999',
			name: 'ふぁいるめい',
			name2: 'サンプルダウンロードファイル',
			download: true,
			download2: false,
			download3: true,
			download4: false,
			switch: 'b',
			pathes: ['/path/to/file-1.ext', '/path/to/file-2.ext', '/path/to/file-3.ext'],
			array: ['array-item'],
		},
		data,
	);
});
