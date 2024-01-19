"use strict";
/// <reference path="../../@types/BgE.d.ts" />
BgE.registerTypeModule('Hr', {
    migrate: function (type) {
        var data = type.export();
        if (BgE.versionCheck.lt(type.version, '2.12.0')) {
            if (data.type) {
                data.kind = data.type.replace(/^bgt-hr--/, '');
                delete data.type;
            }
            if (!data.kind) {
                data.kind = 'primary';
            }
        }
        return data;
    },
});
