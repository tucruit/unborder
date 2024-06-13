"use strict";
/// <reference path="../../@types/BgE.d.ts" />
BgE.registerTypeModule('ImageLink', {
    open: function (editorDialog, type) {
        var data = type.export();
        if (data.loading === 'eager' && data.decoding === 'auto') {
            editorDialog.$el.find('[name="bge-lazy"]').prop('checked', false);
        }
    },
    beforeChange: function (value) {
        return new Promise(function (resolve) {
            // 入力はチェックボックスなので論理値
            value.target = value.target ? '_blank' : '';
            value.rel = value.target ? 'noopener noreferrer' : null;
            var img = new Image();
            var scale = value.hr ? 2 : 1;
            var ORIGIN = '__org';
            var pathParse = value.path.match(/^(.*)(\.(?:jpe?g|gif|png|webp))$/i);
            img.onload = function () {
                value.width = img.width / scale;
                value.height = img.height / scale;
                resolve();
            };
            var error = function () {
                value.width = 'auto';
                value.height = 'auto';
                img.src = value.path;
                value.srcset = '';
                resolve();
            };
            if (value.empty === '1') {
                error();
                return;
            }
            img.onerror = error;
            img.onabort = error;
            if (pathParse) {
                var name_1 = pathParse[1], ext = pathParse[2];
                img.src = "" + name_1 + ORIGIN + ext;
                value.srcset = "" + name_1 + ext + ", " + name_1 + ORIGIN + ext + " 2x";
            }
            else {
                img.src = value.path;
                value.srcset = '';
            }
            if (value.lazy) {
                value.loading = 'lazy';
                value.decoding = 'async';
            }
            else {
                value.loading = 'eager';
                value.decoding = 'auto';
            }
        });
    },
});
