"use strict";
/// <reference path="../../@types/BgE.d.ts" />
BgE.registerTypeModule('DownloadFile', {
    open: function (editorDialog, type) {
        editorDialog.$el.find('[name="bge-download"]').prop('checked', !!type.export().download);
    },
    beforeChange: function (newValues) {
        newValues.download = newValues.download ? newValues.name || newValues.path : null;
    },
});
