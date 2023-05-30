interface JQueryStatic {
	camelCase(text: string): string;
	bcToken?: BcToken;
	bcUtil?: BcUtil;
}

interface JQuery {
	upload(url: string, data: any, callback: { (data: any): void }, type: string): JQuery;
	upload(url: string, callback: { (data: any): void }, type: string): JQuery;
	timepicker(option: any): JQuery;
}

interface BcToken {
	check(callback: () => any, config: any): any;
	getForm(url: string, callback: () => any, config: any): any;
	getHiddenToken(): any;
	key: string | null;
	replaceLinkToSubmitToken(selector: string): any;
	requested: boolean;
	requesting: boolean;
	submitToken(url: string): any;
	update(callback: () => any, config: any): any;
}

interface BcUtil {
	showLoader(): any;
}
