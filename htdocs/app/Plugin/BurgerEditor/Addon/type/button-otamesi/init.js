/// <reference path="../../@types/BgE.d.ts" />
BgE.registerTypeModule('Button', {
    migrate: function (type) {
        var data = type.export();
        if (BgE.versionCheck.lt(type.version, '2.13.0')) {
            if (data.type) {
                data.kind = data.type.replace(/^bgt-btn--/, '');
                delete data.type;
            }
            if (!data.kind) {
                data.kind = 'link';
            }
        }
        return data;
        // style=background-coler:#e6e6fa;
        // var c: string;
        // var h: number, d: Date;
        // d = new Date();
        // h = d.getHours();
        // if (h < 12) {
        // c = "skyblue";
        // } else {
        // c = "lightyellow";
        // }
        // document.body.style.backgroundColor = c;
        // function buttonClick( newColor ){     
        // 	document.getElementById('BG').style.background = newColor;
        // 		   }
        var box = $("#bge-color");
        $(function () {
            box.hover(function () {
                $(this).css('background', 'red');
            }, function () {
                $(this).css('background', 'orange');
            });
        });
    },
});
