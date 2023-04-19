"use strict";
/// <reference path="../../@types/BgE.d.ts" />
BgE.registerTypeModule('GoogleMaps', {
    open: function (editorDialog) {
        var mapNode = editorDialog.$el.find('.bge-gmap').get(0);
        var geocoder = new google.maps.Geocoder();
        var $lat = editorDialog.$el.find('[name=bge-lat]');
        var $lng = editorDialog.$el.find('[name=bge-lng]');
        var $zoom = editorDialog.$el.find('[name=bge-zoom]');
        var $bgeGoogleMapsSearchText = editorDialog.$el.find('[name=bge-search]');
        var $bgeGoogleMapsSearchButton = editorDialog.$el.find('[name=bge-search-button]');
        var lat = parseFloat($lat.val());
        var lng = parseFloat($lng.val());
        var zoom = parseInt($zoom.val(), 10);
        // make map
        var latlng = new google.maps.LatLng(lat, lng);
        var map = new google.maps.Map(mapNode, {
            zoom: zoom,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            center: latlng,
        });
        var marker = new google.maps.Marker({
            position: latlng,
            map: map,
        });
        // set event listener
        google.maps.event.addListener(map, 'dragend', function () {
            marker.setPosition(map.getCenter());
            var markerLat = map.getCenter().lat();
            var markerLng = map.getCenter().lng();
            $lat.val("" + markerLat);
            $lng.val("" + markerLng);
        });
        google.maps.event.addListener(map, 'drag', function () {
            marker.setPosition(map.getCenter());
        });
        google.maps.event.addListener(map, 'zoom_changed', function () {
            var changedZoom = map.getZoom();
            marker.setPosition(map.getCenter());
            $zoom.val("" + changedZoom);
        });
        // geo search
        $bgeGoogleMapsSearchButton.off('click');
        $bgeGoogleMapsSearchButton.on('click', function () {
            geocoder.geocode({
                address: $bgeGoogleMapsSearchText.val(),
            }, function (results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    map.setCenter(results[0].geometry.location);
                    marker.setPosition(results[0].geometry.location);
                    var geolat = map.getCenter().lat();
                    var geoLng = map.getCenter().lng();
                    $lat.val("" + geolat);
                    $lng.val("" + geoLng);
                }
                else {
                    alert('住所から場所を特定できませんでした。最初にビル名などを省略し、番地までの検索などでお試しください。');
                }
            });
        });
    },
    beforeExtract: function (editorDialog) {
        var $lat = editorDialog.$el.find('[name=bge-lat]');
        var $lng = editorDialog.$el.find('[name=bge-lng]');
        var lat = parseFloat($lat.val());
        var lng = parseFloat($lng.val());
        editorDialog.$el.find('[name=bge-url]').val("//maps.apple.com/?q=" + lat + "," + lng);
    },
    change: function (_, type) {
        var BASE_URL = '//maps.google.com/maps/api/staticmap';
        var $db = $('#DefaultBlock');
        var lat = $(type.el).find('[data-lat]').attr('data-lat');
        var lng = $(type.el).find('[data-lng]').attr('data-lng');
        var zoom = $(type.el).find('[data-zoom]').attr('data-zoom');
        $db.show(); // サイズを取得するために一時的に表示する
        var width = Math.floor($(type.el).width() || 0) || 640; // アップデート時に取得出来ない場合 640を利用
        var height = Math.floor($(type.el).height() || 0) || 400; // アップデート時に取得出来ない場合 400を利用
        $db.hide();
        var param = $.param({
            center: [lat, lng],
            zoom: zoom,
            size: width + "x" + height,
            markers: "color:red|color:red|" + lat + "," + lng,
            key: BgE.googleMapsApiKey,
        });
        var url = BASE_URL + "?" + param;
        $(type.el).find('img').attr('src', url);
    },
    migrate: function (type) {
        var data = type.export();
        if (BgE.versionCheck.lt(type.version, '2.10.0')) {
            var lat = data.lat;
            var lng = data.lng;
            data.url = "//maps.apple.com/?q=" + lat + "," + lng;
            type.version = '2.10.0';
        }
        return data;
    },
    isDisable: function () {
        if (BgE.googleMapsApiKey) {
            return '';
        }
        return 'Google Maps APIキーが登録されていないため、利用できません。\n「システム設定」からAPIキーを登録することができます。';
    },
});
