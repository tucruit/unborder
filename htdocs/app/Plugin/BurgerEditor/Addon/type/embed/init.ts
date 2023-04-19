/// <reference path="../../@types/BgE.d.ts" />

interface IEmbedTypeContentData extends IBurgerTypeContentData {
	'embed-code': string;
	'embed-label': string;
}

BgE.registerTypeModule<IEmbedTypeContentData>('Embed', {
	open: editorDialog => {
		editorDialog.$el.find('[name=bge-embed-code]').val((_, val): string => {
			return BgE.Util.base64decode(val);
		});
	},
	beforeChange: newValues => {
		newValues['embed-code'] = BgE.Util.base64encode(newValues['embed-code']);
	},
});
