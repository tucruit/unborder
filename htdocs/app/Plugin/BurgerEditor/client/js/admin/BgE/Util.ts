import * as BgE from '../BgE';
import { Base64 } from 'js-base64';
import { arrayToHash } from '@burger-editor/frozen-patty/lib/Util';

/**
 * ユーティリティクラス
 *
 */
export default class Util {
	/**
	 * 現在のURLのオリジン
	 */
	public static get origin() {
		return `${location.protocol}//${location.hostname}${location.port ? ':' + location.port : ''}`;
	}

	/**
	 * 改行コードを改行タグに変換
	 *
	 * 未使用
	 *
	 * @param text 対象のテキスト
	 * @return 変換されたテキスト
	 *
	 */
	public static nl2br(text: string) {
		return `${text}`.replace(/(\r\n|\n\r|\r|\n)/g, '<br />');
	}

	/**
	 * 改行タグを改行コードに変換
	 *
	 * 未使用
	 *
	 * @param text 対象のテキスト
	 * @return 変換されたテキスト
	 *
	 */
	public static br2nl(html: string) {
		return `${html}`.replace(/<\s*?br\s*?\/?>/g, '\r\n');
	}

	/**
	 * 数値をバイトサイズ単位にフォーマットする
	 *
	 * @param byteSize 対象の数値
	 * @param digits 小数点の桁数
	 * @param autoFormat SI接頭辞をつけるかどうか
	 * @return フォーマットされた文字列
	 *
	 */
	public static formatByteSize(byteSize: number, digits: number = 2, autoFormat: boolean = true) {
		let compute = byteSize;
		let counter = 0;
		const unit = ['byte', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB'];
		if (autoFormat) {
			while (compute > 1024) {
				compute /= 1024;
				counter += 1;
			}
			if (counter === 0) {
				digits = 0;
			}
			return compute.toFixed(digits) + unit[counter];
		} else {
			return byteSize + unit[0];
		}
	}

	/**
	 * YouTubeの動画URLからIDを抽出する
	 *
	 * 何も抽出できなかった場合 空文字列を返す
	 *
	 * 参考: http://stackoverflow.com/questions/6903823/regex-for-youtube-id
	 * 以下の形式が対応可能
	 * http://www.youtube.com/sandalsResorts#p/c/54B8C800269D7C1B/0/FJUvudQsKCM
	 * http://youtu.be/NLqAF9hrVbY
	 * http://www.youtube.com/embed/NLqAF9hrVbY
	 * https://www.youtube.com/embed/NLqAF9hrVbY
	 * http://www.youtube.com/v/NLqAF9hrVbY?fs=1&hl=en_US
	 * http://www.youtube.com/watch?v=NLqAF9hrVbY
	 * http://www.youtube.com/ytscreeningroom?v=NRHVzbJVx8I
	 * http://www.youtube.com/watch?v=JYArUl0TzhA&feature=featured
	 *
	 * @params idOrUrl YouTubeのURLもしくはID
	 * @return 抽出したID
	 *
	 */
	public static parseYTId(idOrUrl: string) {
		let id = '';
		if (!idOrUrl) {
			return id;
		}
		const match = idOrUrl.match(
			/(?:youtu\.be\/|youtube\.com(?:\/embed\/|\/v\/|\/watch\?v=|\/user\/\S+|\/ytscreeningroom\?v=))([\w-]{10,12})\b/,
		);
		if (match) {
			id = match[1];
		} else {
			id = idOrUrl;
		}
		return id;
	}

	/**
	 * 現在のウィンドウのサイズから最適なダイアログのサイズを返す
	 *
	 * @param maxSize 最大サイズ
	 * @param vector 測る方向 "width" もしくは "height"
	 * @param margin マージン
	 * @return 最適なサイズ
	 *
	 */
	public static getDialogSize(maxSize: number, vector: 'width' | 'height', margin = 50) {
		let windowSize: number;
		switch (vector) {
			case 'width': {
				windowSize = window.document.documentElement ? window.document.documentElement.clientWidth : 0;
				break;
			}
			case 'height': {
				windowSize = window.document.documentElement ? window.document.documentElement.clientHeight : 0;
				break;
			}
			default: {
				return 0;
			}
		}
		const result = Math.min(windowSize - margin, maxSize);
		// console.log({maxSize, vector, margin, result});
		return result;
	}

	/**
	 * 正しいCSSクラス名かどうかチェックする
	 *
	 * @param className チェック対象
	 * @return 結果
	 */
	public static isValidAsClassName(className: string) {
		const validClassName = /^-?[_a-zA-Z]+[_a-zA-Z0-9-]*$/;
		return validClassName.test(className);
	}

	/**
	 * background-imageからパスを取得する
	 *
	 * @param className チェック対象
	 * @return 結果
	 */
	public static getBackgroundImagePath(value: string) {
		return decodeURI(value.replace(/^url\(["']?([^"']+)["']?\)$/i, '$1').replace(Util.origin, ''));
	}

	public static dataOptimize(raws: BgE.IBurgerTypeContentRawMataDatum[]) {
		const a = raws.map(r => [r.key, r.datum, r.isArray] as [string, BgE.IBurgerTypeContentDatum, boolean]);
		return arrayToHash(a);
	}

	/**
	 * Base64変換
	 *
	 * @param str
	 */
	public static base64encode(str: string) {
		return Base64.encode(str);
	}

	/**
	 * Base64変換
	 *
	 * @param str
	 */
	public static base64decode(str: string) {
		return Base64.decode(str);
	}
}
