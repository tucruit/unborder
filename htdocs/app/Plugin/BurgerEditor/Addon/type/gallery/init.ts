/// <reference path="../../@types/BgE.d.ts" />

interface IGalleryTypeContentData extends IBurgerTypeContentData {
	autoplay: 'true' | 'false';
	caption: string[];
	ctrl: 'true' | 'false';
	delay: number;
	duration: number;
	effect: 'fade' | 'slide';
	marker: 'dots' | 'thumbs' | 'none';
	path: string[];
}

BgE.registerTypeModule<IGalleryTypeContentData>('Gallery', {
	customFunctions: {
		// 削除ボタンクリック時イベント
		removeRow: (e, editorDialog) => {
			if (editorDialog.$el.find('[data-bge-class="MultiFieldSelection"] tr').length === 1) {
				alert('全て削除する場合はブロック要素を削除してください');
				return false;
			}
			if (confirm('削除します。よろしいですか？')) {
				const $thisRow = $(e.target).parents('tr');
				$thisRow.fadeOut(200, () => $thisRow.remove());
			}
			return false;
		},
		// 移動ボタンクリック時イベント
		replaceRow: e => {
			const $thisRow = $(e.target).parents('tr');
			const $nextRow = $thisRow.next();
			if ($nextRow.length && $thisRow.next().get(0).nodeName.toLowerCase() === 'tr') {
				const $src = $thisRow.find('[name="bge-path"]');
				const $caption = $thisRow.find('[name="bge-caption"]');
				const $nextSrc = $nextRow.find('[name="bge-path"]');
				const $nextCaption = $nextRow.find('[name="bge-caption"]');
				const src = ($src.val() as string) || '';
				const caption = ($caption.val() as string) || '';
				const nextSrc = ($nextSrc.val() as string) || '';
				const nextCaption = $nextCaption.val() || '';
				$src.val(nextSrc);
				$caption.val(nextCaption);
				$thisRow.find('[data-bge="path:src"]').attr('src', nextSrc);
				$nextSrc.val(src);
				$nextCaption.val(caption);
				$nextRow.find('[data-bge="path:src"]').attr('src', src);
			} else {
				alert('対象が見つかりません');
			}
			return false;
		},
	},
});
