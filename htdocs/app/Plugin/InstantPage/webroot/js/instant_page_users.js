/**
 * id email check プラグイン
 */
$(function () {
	// メールアドレス（ID）チェック
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
 			var url = '/instant_page/register_message/ajax_id_check';
			ajaxCheck(url, checkId);
		}
		return false;
	});

    $('.mailCheck').keyup(function(){
        var checkMail = $(".mailCheck").val();
        var regexp = /^[a-zA-Z0-9\-+.@_]+$/;
        if (!checkMail.match(regexp)) {
            $('.mailCheck').parent().children('.notice-message').remove();
            $('.mailCheck').parent().children('.error-message').remove();
            $('.mailCheck').parent().append('<div class="error-message"><small>半角英数字とハイフン、アンダースコアのみで入力してください。</small></div>');
            $('.error-message').css('color','#f20014');
        } else {
            var url = '/instant_page/register_message/ajax_email_check';
            ajaxCheck(url, checkMail);
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
