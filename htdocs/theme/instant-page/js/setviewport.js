/*
 *
 * @copyright    Copyright (c) hiniarata co.ltd
 * @link         https://hiniarata.jp
 */

/*
 * Mobile閲覧時Viewport設定処理
 */
$(function () {
    // サイトbody等に設定している PC min-width を設定してください
    // 通常 $width_base + ($sidePadding_base * 2) の値
    const minContentsWidth = 1054;
    // PC最小幅を設定してください
    const minPcWidth = 800;

    let ua = navigator.userAgent.toLowerCase();
    // iPhone
    let isiPhone = (ua.indexOf('iphone') > -1);
    // iPad
    let isiPad = ua.indexOf('ipad') > -1 || ua.indexOf('macintosh') > -1 && 'ontouchend' in document;
    let isiPadChrome = (ua.indexOf('CriOS') > -1);
    // Android
    let isAndroid = (ua.indexOf('android') > -1) && (ua.indexOf('mobile') > -1);
    // Android Tablet
    let isAndroidTablet = (ua.indexOf('android') > -1) && (ua.indexOf('mobile') == -1);
    // iOS
    if (isiPhone || isAndroid) {
        document.querySelector('meta[name="viewport"]').setAttribute('content', 'width=device-width,initial-scale=1');
    } else if (isiPad || isiPadChrome) {
        window.onorientationchange = directionCheck;
    } else if (isAndroidTablet) {
        window.onresize = directionCheck;
    } else {
        document.querySelector('meta[name="viewport"]').setAttribute('content', 'width=device-width,initial-scale=1');
    }
    directionCheck();

    function directionCheck() {
        let direction = Math.abs(window.orientation);
        if (direction == 90) {
            document.querySelector('meta[name="viewport"]').setAttribute('content', 'width=' + minContentsWidth + ', maximum-scale=1.0');
            // iPad Air等 縦表示時幅PC最小幅以上の機種への対応
            if (isiPad || isiPadChrome || isAndroidTablet) {
                if (($(window).innerWidth() >= minPcWidth) && ($(window).innerWidth() <= minContentsWidth)) {
                    document.querySelector('meta[name="viewport"]').setAttribute('content', 'width=' + minContentsWidth);
                }
            }
        } else {
            document.querySelector('meta[name="viewport"]').setAttribute('content', 'width=device-width,initial-scale=1');
            // iPad Air等 縦表示時幅PC最小幅以上の機種への対応
            if (isiPad || isiPadChrome || isAndroidTablet) {
                if ($(window).innerWidth() >= minPcWidth) {
                    document.querySelector('meta[name="viewport"]').setAttribute('content', 'width=' + minPcWidth + '');
                }
            }
        }
    }
    $(window).on('orientationchange', function (evt) {
        let angle;
        angle = screen && screen.orientation && screen.orientation.angle;
        if (angle == null) {
            angle = window.orientation || 0;
        }
        if (angle % 180 !== 0) {
            document.querySelector('meta[name="viewport"]').setAttribute('content', 'width=device-width,initial-scale=1');
        } else {
            if (isiPad || isiPadChrome) {
                document.querySelector('meta[name="viewport"]').setAttribute('content', 'width=' + minContentsWidth + ', maximum-scale=1.0');
            }
        }
    }).trigger('orientationchange');

    //end of jQuery
});
