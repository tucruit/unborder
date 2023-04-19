/// <reference path="../../@types/BgE.d.ts" />

interface IGoogleMapsTypeContentData extends IBurgerTypeContentData {
	lat: number;
	lng: number;
	zoom: number;
	url: string;
}

BgE.registerTypeModule('GoogleMaps', {
	open: editorDialog => {
		const mapNode = editorDialog.$el.find('.bge-gmap').get(0);
		const geocoder = new google.maps.Geocoder();
		const $lat = editorDialog.$el.find('[name=bge-lat]');
		const $lng = editorDialog.$el.find('[name=bge-lng]');
		const $zoom = editorDialog.$el.find('[name=bge-zoom]');

		const $bgeGoogleMapsSearchText = editorDialog.$el.find('[name=bge-search]');
		const $bgeGoogleMapsSearchButton = editorDialog.$el.find('[name=bge-search-button]');

		const lat = parseFloat($lat.val() as string);
		const lng = parseFloat($lng.val() as string);
		const zoom = parseInt($zoom.val() as string, 10);

		// make map
		const latlng = new google.maps.LatLng(lat, lng);
		const map = new google.maps.Map(mapNode, {
			zoom,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			center: latlng,
		});
		const marker = new google.maps.Marker({
			position: latlng,
			map,
		});

		// set event listener
		google.maps.event.addListener(map, 'dragend', () => {
			marker.setPosition(map.getCenter());
			const markerLat = map.getCenter().lat();
			const markerLng = map.getCenter().lng();
			$lat.val(`${markerLat}`);
			$lng.val(`${markerLng}`);
		});
		google.maps.event.addListener(map, 'drag', () => {
			marker.setPosition(map.getCenter());
		});
		google.maps.event.addListener(map, 'zoom_changed', () => {
			const changedZoom = map.getZoom();
			marker.setPosition(map.getCenter());
			$zoom.val(`${changedZoom}`);
		});

		// geo search
		$bgeGoogleMapsSearchButton.off('click');
		$bgeGoogleMapsSearchButton.on('click', (): void => {
			geocoder.geocode(
				{
					address: $bgeGoogleMapsSearchText.val() as string,
				},
				(results, status): void => {
					if (status === google.maps.GeocoderStatus.OK) {
						map.setCenter(results[0].geometry.location);
						marker.setPosition(results[0].geometry.location);
						const geolat = map.getCenter().lat();
						const geoLng = map.getCenter().lng();
						$lat.val(`${geolat}`);
						$lng.val(`${geoLng}`);
					} else {
						alert(
							'住所から場所を特定できませんでした。最初にビル名などを省略し、番地までの検索などでお試しください。',
						);
					}
				},
			);
		});
	},

	beforeExtract: editorDialog => {
		const $lat = editorDialog.$el.find('[name=bge-lat]');
		const $lng = editorDialog.$el.find('[name=bge-lng]');
		const lat = parseFloat($lat.val() as string);
		const lng = parseFloat($lng.val() as string);
		editorDialog.$el.find('[name=bge-url]').val(`//maps.apple.com/?q=${lat},${lng}`);
	},

	change: (_, type) => {
		const BASE_URL = '//maps.google.com/maps/api/staticmap';
		const $db = $('#DefaultBlock');
		const lat = $(type.el).find('[data-lat]').attr('data-lat');
		const lng = $(type.el).find('[data-lng]').attr('data-lng');
		const zoom = $(type.el).find('[data-zoom]').attr('data-zoom');
		$db.show(); // サイズを取得するために一時的に表示する
		const width = Math.floor($(type.el).width() || 0) || 640; // アップデート時に取得出来ない場合 640を利用
		const height = Math.floor($(type.el).height() || 0) || 400; // アップデート時に取得出来ない場合 400を利用
		$db.hide();
		const param = $.param({
			center: [lat, lng],
			zoom,
			size: `${width}x${height}`, // サイズはAPIの仕様で最大 640x640 の画像しか参照できない
			markers: `color:red|color:red|${lat},${lng}`,
			key: BgE.googleMapsApiKey,
		});
		const url = `${BASE_URL}?${param}`;
		$(type.el).find('img').attr('src', url);
	},

	migrate: type => {
		const data = type.export() as IGoogleMapsTypeContentData;
		if (BgE.versionCheck.lt(type.version, '2.10.0')) {
			const lat = data.lat;
			const lng = data.lng;
			data.url = `//maps.apple.com/?q=${lat},${lng}`;
			type.version = '2.10.0';
		}
		return data;
	},

	isDisable: () => {
		if (BgE.googleMapsApiKey) {
			return '';
		}
		return 'Google Maps APIキーが登録されていないため、利用できません。\n「システム設定」からAPIキーを登録することができます。';
	},
});
