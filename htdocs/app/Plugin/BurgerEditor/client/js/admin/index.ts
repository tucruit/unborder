import 'core-js';

import * as BgE from './BgE';

// @ts-ignore
// tslint:disable-next-line:no-string-literal
window['BgE'] = BgE;

$(() => {
	$('#PageName').focus();

	BgE.init();

	// yuga.jsのクラス追加を削除
	$('.even').removeClass('even');
	$('.odd').removeClass('odd');
	$('.empty').removeClass('empty');
	$('.firstChild').removeClass('firstChild');
	$('.lastChild').removeClass('lastChild');
	$('br').removeAttr('class');
});
