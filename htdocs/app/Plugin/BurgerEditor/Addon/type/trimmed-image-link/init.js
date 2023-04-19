"use strict";
/// <reference path="../../@types/BgE.d.ts" />
BgE.registerTypeModule('TrimmedImageLink', {
    change: function (_, type) {
        // リンクのtargetを設定
        var $link = $(type.el).find('.bge-trimmed-image-link-a');
        var target = $(type.el).find('.bge-trimmed-image-link-target').val() === '1' ? '_blank' : '_self';
        $link.attr('target', target);
    },
    beforeChange: function (value) {
        return new Promise(function (resolve) {
            // 入力はチェックボックスなので論理値
            value.target = value.target ? '_blank' : '';
            value.rel = value.target ? 'noopener noreferrer' : null;
            resolve();
        });
    },
});
