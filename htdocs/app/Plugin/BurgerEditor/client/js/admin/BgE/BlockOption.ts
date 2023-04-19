import * as BgE from '../BgE';

/**
 * オプションのCSSクラス情報
 */
export interface IOptionClass {
	/**
	 * 表示用のラベル
	 */
	label: string;

	/**
	 * CSSで使用するクラス名
	 */
	className: string;
}

/**
 * ブロックのオプションを扱うクラス
 */
export default class BlockOption {
	/**
	 * CSSクラス名のプレフィックス
	 */
	public static classPrefix = 'bgb-opt--';

	/**
	 * オプションで使われているクラス名を探してオプションを取得する
	 *
	 * @param className 検索するクラス名
	 * @return 検索したクラス名を有するオプション
	 */
	public static getOption(className: string) {
		let option: BlockOption | null = null;
		if (BgE.config.blockClassOption) {
			for (const optionName of Object.keys(BgE.config.blockClassOption)) {
				const blockOption = BgE.config.blockClassOption[optionName];
				for (const optionValue of Object.keys(blockOption)) {
					if (optionValue === className) {
						option = new BlockOption(optionName, BgE.config.blockClassOption[optionName]);
						option.setClass(className);
						break;
					}
				}
			}
		}
		return option;
	}

	/**
	 * オプション名
	 */
	public optionName: string;

	/**
	 * 選択中のクラス
	 */
	public currentClass: IOptionClass | null = null;

	/**
	 * CSSクラスのリスト
	 */
	public classList: IOptionClass[] = [];

	/**
	 * コンストラクタ
	 */
	constructor(optionName: string, optionValues: { [optionValue: string]: string }) {
		this.optionName = optionName;
		for (const valueName of Object.keys(optionValues)) {
			const className = valueName;
			// 「指定なし」は valueNameが空
			if (!className || !optionValues[className]) {
				continue;
			}
			if (className.indexOf(BlockOption.classPrefix) !== 0) {
				// eslint-disable-next-line no-console
				console.warn(
					`Invalid Error: "${optionName}" オプションは "${BlockOption.classPrefix}" で開始される必要があります。` +
						` "${className}" は無効化されました。`,
				);
				break;
			}
			this.classList.push({
				label: optionValues[className],
				className,
			});
		}
	}

	/**
	 * クラス名を設定
	 *
	 */
	public setClass(className: string) {
		for (const classInfo of Array.from(this.classList)) {
			if (classInfo.className === className) {
				this.currentClass = classInfo;
				return;
			}
		}
		// eslint-disable-next-line no-console
		console.warn(`"${className}" というクラス名は オプション "${this.optionName}" には設定できませんでした。`);
	}

	/**
	 * 現在選択されているクラスを取得
	 */
	public getClass() {
		return this.currentClass;
	}
}
