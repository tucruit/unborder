"use strict";
/// <reference path="../../@types/BgE.d.ts" />
BgE.registerTypeModule('Embed', {
    open: function (editorDialog) {
        editorDialog.$el.find('[name=bge-embed-code]').val(function (_, val) {
            return BgE.Util.base64decode(val);
        });
    },
    beforeChange: function (newValues) {
        newValues['embed-code'] = BgE.Util.base64encode(newValues['embed-code']);
    },
});
