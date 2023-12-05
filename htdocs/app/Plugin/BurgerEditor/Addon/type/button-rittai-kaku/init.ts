/// <reference path="../../@types/BgE.d.ts" />

interface IButtonTypeContentData extends IBurgerTypeContentData {
	link: string;
	target: '' | '_blank' | '_top' | '_self';
	text: string;

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

interface IButtonTypeCustomData {
	btnClasses: string;
}

BgE.registerTypeModule<IButtonTypeContentData, IButtonTypeCustomData>('Button', {
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
