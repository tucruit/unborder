import { options } from './options';

/**
 * 過去のバグへの応急対応とマイグレーション
 *
 * @param $root ルート要素
 */
export function migration($root: JQuery<Document | HTMLElement>) {
	/**
	 * ポップアップ画像が設定されないバグの応急対応
	 *
	 * v2.12.0 から v2.12.1 までの対応
	 *
	 */
	$root.find('[data-bge-popup="true"] a').each((i, el) => {
		const $this = $(el);
		const $img = $this.find('.bgt-box__image');
		const bgi = $img.css('background-image');
		if (bgi) {
			const src = bgi.replace(/\s*url\s*\(((?:"|')?)(.+?)\1\)\s*;?\s*/i, '$2');
			$this.attr('href', src);
		}
	});

	/**
	 * target属性の値がfalseになっている問題
	 */
	$root.find('[data-bgb] [target="false"]').each((i, el) => {
		$(el).removeAttr('target');
	});

	/**
	 * 画像タイプのカラーボックス設定
	 *
	 * .bgt-colorbox は v2.4.xまで
	 * [data-bge-popup="1"] は v2.11.0まで
	 *
	 */
	$root.find('[data-bge-popup="true"] a, [data-bge-popup="1"] a, .bgt-colorbox').colorbox({
		maxWidth: options.colorbox.maxWidth,
		maxHeight: options.colorbox.maxHeight,
		rel: 'bgt-colorbox',
		current: '{current} / {total}',
	});
}
