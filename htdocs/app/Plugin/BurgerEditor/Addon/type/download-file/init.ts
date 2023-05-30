/// <reference path="../../@types/BgE.d.ts" />

interface IDownloadFileTypeContentData extends IBurgerTypeContentData {
	path: string;
	download: string | boolean | null;
	name: string;
	'formated-size': string;
	size: string;
}

BgE.registerTypeModule<IDownloadFileTypeContentData>('DownloadFile', {
	open: (editorDialog, type) => {
		editorDialog.$el.find('[name="bge-download"]').prop('checked', !!type.export().download);
	},
	beforeChange: newValues => {
		newValues.download = newValues.download ? newValues.name || newValues.path : null;
	},
});
