/**
 * id email check プラグイン
 */
$(function () {
    var submitBtnCount = 0;
    var namecheck = 0;
    var messagecheck = 0;
    var captchacheck = 0;
    var emailcheck = 0;
    // 送信ボタン非表示
    $('#BtnMessageSubmit').hide().parent('div').css('opacity', '.2');
    // name 入力必須
    $('#MailMessageName').keyup(function(){
        var name = $("#MailMessageName").val();
        if (name.length) {
           // console.log(submitBtnCount);
           namecheck = 1;
       } else {
        namecheck = 0;
       }
    });
    // メッセージ必須
    $('#MailMessageMessage').keyup(function(){
        var message = $("#MailMessageMessage").val();
        if (message.length > 0) {
           console.log(message.length > 0);
           messagecheck = 1;
           console.log(messagecheck);
       } else {
        messagecheck = 0;
       }
    });
    // イメージ認証チェック
    $('#MailMessageAuthCaptcha').keyup(function(){
        var AuthCaptcha = $("#MailMessageAuthCaptcha").val();
        var AuthCaptchaId = $("#MailMessageCaptchaId").val();
        var checkId = AuthCaptcha + '|' + AuthCaptchaId;
        var url = '/instant_page/register_message/ajax_auth_captcha';
        return ajaxCheck(url, checkId);
    });

    // メールアドレスチェック
    $('.mailCheck').keyup(function(){
        var checkMail = $(".mailCheck").val();
        var regexp = /^[a-zA-Z0-9\-+.@_]+$/;
        if (!checkMail.match(regexp)) {
            $('.mailCheck').parent().children('.notice-message').remove();
            $('.mailCheck').parent().children('.error-message').remove();
            $('.mailCheck').parent().append('<div class="error-message"><small>半角英数字とハイフン、アンダースコアのみで入力してください。</small></div>');
            $('.error-message').css('color','#f20014');
        } else {
            var url = '/instant_page/register_message/ajax_email_validate';
            return ajaxCheck(url, checkMail);
        }
        return false;
    });

    // Ajax チェック
    function ajaxCheck(url, checkId) {
            // console.log(url);
            // console.log(checkId);
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
                console.log(data.message);
                if (data.message == '正しいです') {
                    captchacheck = 1;
                    submitBtnCount = submitBtnCount +1;
                } else if (data.message == '利用可能なメールアドレスです。') {
                    emailcheck = 1;
                    submitBtnCount = submitBtnCount +1;
                }
                // console.log(submitBtnCount);
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
    // 全て正しく埋まった時点で、送信ボタン表示
    $('input').keyup(function(){
        console.log('name; ' + namecheck);
        console.log('message; ' + messagecheck);
        console.log('captcha; ' + captchacheck);
        console.log('email; ' + emailcheck);
        submitBtnCount = namecheck + messagecheck + captchacheck + emailcheck;
        if (submitBtnCount == 4) {
            $('#BtnMessageSubmit').show().parent('div').css('opacity', '1');
        } else {
            $('#BtnMessageSubmit').hide().parent('div').css('opacity', '.2');
        }
    });

    $(".form-submit").click(function () {
        $("#MailMessageMode").val(
            $(this).attr('id').replace('BtnMessage', '')
        );
        $(this).prop('disabled', true);//ボタンを無効化する
        $(this).closest('form').submit();//フォームを送信する
        return true;
    });
});
