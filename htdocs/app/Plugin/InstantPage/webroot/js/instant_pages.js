/**
 * name check プラグイン
 */
$(function () {
    // テーマ切り替え
    var template = $('#InstantPageInstantPageTemplateId');
    //console.log(template.val());
    if (template.val()) {
        let templateIds = $('.themeBox-btn__apply');
        $.each(templateIds, function(){
            let inner = $(this).html();
            if ($(this).data('template') == template.val()) {
                inner = inner.replace('適用する', '適用済み');
                //console.log(inner);
                $(this).html(inner).css('background-color', '#ccc');
            }
        });
        // ボタン押下時のアクション
        $('.themeBox-btn__apply').click(function() {
           $('.themeBox-btn__apply').css('background-color', '#ea5457').children('span').text('適用する');
           $(this).css('background-color', '#ccc').children('span').text('適用済み');
           // 入力項目の切り替え
           template.val($(this).data('template'));
           //console.log($(this).data('template'));
        });

    }
	// nameチェック
	//$('.mailCheck').after('<input type="button" class="button bca-btn bca-actions__item" id="nameCheck" name="" required="" value="アカウント重複チェック"></input>');
	$('.nameCheck').keyup(function(){
		var checkId = $(".nameCheck").val();
		var regexp = /^[a-zA-Z0-9\-+.@_]+$/;
		if (!checkId.match(regexp)) {
            $('.nameCheck').parent().children('.notice-message').remove();
            $('.nameCheck').parent().children('.error-message').remove();
			$('.nameCheck').parent().append('<div class="error-message"><small>半角英数字とハイフン、アンダースコアのみで入力してください。</small></div>');
            $('.error-message').css('color','#f20014');
		} else {
 			var url = '/instant_page/instant_pages/ajax_name_check';
			ajaxCheck(url, checkId);
		}
		return false;
	});

    // Ajax チェック
    function ajaxCheck(url, checkId) {
            console.log(url);
            console.log(checkId);
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            data: {
                id: checkId
            },
            cache: false
        }).done(function( data, textStatus, jqXHR ) {
           $(data.field).parent().children('.notice-message').remove();
           $(data.field).parent().children('.error-message').remove();
            if (data.status) {
                $(data.field).parent().append('<div class="notice-message">' + data.message + '</div>');
            } else {
                $(data.field).parent().append('<div class="error-message"><small>' + data.message + '</small></div>');
            }
            $('.error-message').css('color','#f20014');
        }).fail(function( jqXHR, textStatus, errorThrown ) {
           $(data.field).parent().children('.notice-message').remove();
           $(data.field).parent().children('.error-message').remove();
            $(data.field).parent().append('<div class="error-message">' + errorThrown + '</div>');
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        });
    }



});
