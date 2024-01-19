/// <reference path="../../@types/BgE.d.ts" />

interface IHrTypeContentData extends IBurgerTypeContentData {
	/**
	 * @version 2.11.0
	 * @see IHrTypeContentData.type
	 * @deprecated
	 */
	type?: string;

	/**
	 * @version 2.12.0
	 * @since 2.12.0
	 */
	kind: string;
}

BgE.registerTypeModule<IHrTypeContentData>('Hr', {
	migrate: type => {
		const data = type.export();
		if (BgE.versionCheck.lt(type.version, '2.12.0')) {
			if (data.type) {
				data.kind = data.type.replace(/^bgt-hr--/, '');
				delete data.type;
			}
			if (!data.kind) {
				data.kind = 'primary';
			}
		}
		return data;
	},
});
