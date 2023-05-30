/**
 * baserAjaxDataList プラグイン
 */
(function($){

			// $.ajax({
			// 	type: 'GET',
			// 	datatype:'html',
			// 	url: "/instant_page/instant_pages/index/",
			// 	data: '',
			// 	success: function(result) {
			// 		$("#all").html(result);
			// 		$("#all").children('div.p-map__inr').attr('id', 'allInr');
			// 		$("#all").find('div.pagination').attr('id', 'allPagination');

			// 		if ("efo" in window) {
			// 			window.efo();
			// 		}
			// 	},
			// 	error: function(XMLHttpRequest, textStatus, errorThrown) {
			// 		console.log('Error : ' + errorThrown);
			// 	}
			// });
			$.get('/instant_page/instant_pages/',
				function (result) {
					$("#all").html(result);
					if ("efo" in window) {
						window.efo();
					}
				});
			$.get('/instant_page/instant_pages/index?area_id=1',
				function (result) {
					$("#hokkaido").html(result);
					if ("efo" in window) {
						window.efo();
					}
				});
			$.get('/instant_page/instant_pages/index?area_id=2',
				function (result) {
					$("#kanto").html(result);
					if ("efo" in window) {
						window.efo();
					}
				});
			$.get('/instant_page/instant_pages/index?area_id=3',
				function (result) {
					$("#tokyo").html(result);
					if ("efo" in window) {
						window.efo();
					}
				});
			$.get('/instant_page/instant_pages/index?area_id=4',
				function (result) {
					$("#hokuriku").html(result);
					if ("efo" in window) {
						window.efo();
					}
				});
			$.get('/instant_page/instant_pages/index?area_id=5',
				function (result) {
					$("#kinki").html(result);
					if ("efo" in window) {
						window.efo();
					}
				});
			$.get('/instant_page/instant_pages/index?area_id=6',
				function (result) {
					$("#shikoku").html(result);
					if ("efo" in window) {
						window.efo();
					}
				});
			$.get('/instant_page/instant_pages/index?area_id=7',
				function (result) {
					$("#kyushu").html(result);
					if ("efo" in window) {
						window.efo();
					}
				});
			return false;
})(jQuery);
