"use strict";
/// <reference path="../../@types/BgE.d.ts" />
BgE.registerTypeModule('Gallery', {
    customFunctions: {
        // 削除ボタンクリック時イベント
        removeRow: function (e, editorDialog) {
            if (editorDialog.$el.find('[data-bge-class="MultiFieldSelection"] tr').length === 1) {
                alert('全て削除する場合はブロック要素を削除してください');
                return false;
            }
            if (confirm('削除します。よろしいですか？')) {
                var $thisRow_1 = $(e.target).parents('tr');
                $thisRow_1.fadeOut(200, function () { return $thisRow_1.remove(); });
            }
            return false;
        },
        // 移動ボタンクリック時イベント
        replaceRow: function (e) {
            var $thisRow = $(e.target).parents('tr');
            var $nextRow = $thisRow.next();
            if ($nextRow.length && $thisRow.next().get(0).nodeName.toLowerCase() === 'tr') {
                var $src = $thisRow.find('[name="bge-path"]');
                var $caption = $thisRow.find('[name="bge-caption"]');
                var $nextSrc = $nextRow.find('[name="bge-path"]');
                var $nextCaption = $nextRow.find('[name="bge-caption"]');
                var src = $src.val() || '';
                var caption = $caption.val() || '';
                var nextSrc = $nextSrc.val() || '';
                var nextCaption = $nextCaption.val() || '';
                $src.val(nextSrc);
                $caption.val(nextCaption);
                $thisRow.find('[data-bge="path:src"]').attr('src', nextSrc);
                $nextSrc.val(src);
                $nextCaption.val(caption);
                $nextRow.find('[data-bge="path:src"]').attr('src', src);
            }
            else {
                alert('対象が見つかりません');
            }
            return false;
        },
    },
});
