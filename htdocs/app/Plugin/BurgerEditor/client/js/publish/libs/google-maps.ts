export function googleMaps(el: HTMLElement) {
	if (!window?.google?.maps) {
		return;
	}

	const $el = $(el);

	const defaultLat = 35.681382;
	const defaultLng = 139.766084;
	const defaultZoom = 14;

	const mapCenterLat = +$el.data('lat') || defaultLat;
	const mapCenterLng = +$el.data('lng') || defaultLng;
	const zoom = +$el.data('zoom') || defaultZoom;

	const letLng = new google.maps.LatLng(mapCenterLat, mapCenterLng);

	const mapOption: google.maps.MapOptions = {
		zoom,
		scrollwheel: false,
		center: letLng,
		styles: undefined,
		mapTypeControlOptions: {
			mapTypeIds: [google.maps.MapTypeId.HYBRID, google.maps.MapTypeId.ROADMAP],
		},
	};

	const map = new google.maps.Map(el, mapOption);

	new google.maps.Marker({
		position: letLng,
		map,
	});
}
