"use strict";
/// <reference path="../../@types/BgE.d.ts" />
var FALLBACK_TITLE = 'YouTube動画';
BgE.registerTypeModule('Youtube', {
    open: function (editorDialog, types) {
        var $title = editorDialog.$el.find('[name="bge-title"]');
        var title = types.export().title;
        if (title === FALLBACK_TITLE) {
            $title.val('');
        }
        var BASE_URL = '//www.youtube.com/embed/';
        var BASIC_PARAM = '?rel=0&loop=1&autoplay=1&autohide=1&start=0';
        var THUMB_URL = '//img.youtube.com/vi/';
        var THUMB_FILE_NAME = '/mqdefault.jpg'; // /0.jpgでも可能
        var $id = editorDialog.$el.find('[name="bge-id"]');
        var $url = editorDialog.$el.find('[name="bge-url"]');
        var $thumb = editorDialog.$el.find('[name="bge-thumb"]');
        var $preview = editorDialog.$el.find('.bge-youtube-preview');
        var preview = function () {
            var id = BgE.Util.parseYTId($id.val());
            var url = BASE_URL + id + BASIC_PARAM;
            $preview.attr('src', url);
            $preview.appendTo(editorDialog.$el);
            $url.val(url);
            $thumb.val(THUMB_URL + id + THUMB_FILE_NAME);
        };
        $id.on('change input', preview);
        preview();
    },
    beforeChange: function (newValues) {
        newValues.id = BgE.Util.parseYTId(newValues.id);
        newValues.title = newValues.title || FALLBACK_TITLE;
    },
});
