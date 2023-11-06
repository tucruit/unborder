/// <reference path="../../@types/BgE.d.ts" />

interface IButtonCustomTypeContentData extends IBurgerTypeContentData {
	link: string;
	target: '' | '_blank' | '_top' | '_self';
	text: string;
	padding: '' | 'p-large' | 'p-medium' | 'p-small';
	margin: '' | 'm-large' | 'm-medium' | 'm-small';

	/**
	 * @version 2.1.0
	 * @see IButtonTypeContentData.type
	 * @deprecated
	 */
	type?: 'bgt-btn--link' | 'bgt-btn--em' | 'bgt-btn--external' | 'bgt-btn--back';

	/**
	 * @version 2.13.0
	 * @since 2.13.0
	 */
	kind: string;
}

interface IButtonCustomTypeData {
	btnClasses: string;
}

BgE.registerTypeModule<IButtonCustomTypeContentData, IButtonCustomTypeData>('ButtonCustom', {
	migrate: type => {
		const data = type.export();
		if (BgE.versionCheck.lt(type.version, '2.13.0')) {
			if (data.type) {
				data.kind = data.type.replace(/^bgt-btn--/, '');
				delete data.type;
			}
			if (!data.kind) {
				data.kind = 'link';
			}
		}
		return data;
	},
});
