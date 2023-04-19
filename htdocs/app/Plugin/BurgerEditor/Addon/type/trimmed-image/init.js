"use strict";
/// <reference path="../../@types/BgE.d.ts" />
BgE.registerTypeModule('TrimmedImage', {
    change: function (value, type) {
        if (!value.popup) {
            var $a = $(type.el).find('a');
            $a.removeAttr('href');
        }
    },
});
