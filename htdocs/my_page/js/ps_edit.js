/**
 * help tippy
 **/
tippy('.subMenuBox-header-helpIcon', {
  content(reference) {
    return reference.nextElementSibling.innerHTML;
  },
  allowHTML: true,
  trigger: 'click',
});

/**
 * 読み込み時処理
 **/
$(function() {
  if($('.edit').length) {
    // サイドメニュー開閉ボタンタイトル設定
    setEditPageMenuBtnTxt();
    // サイドメニューテキストボックス最大文字数表示設定
    setDispFieldForEditPageTxtMaxLength();
  }
});

/**
 * Load時処理
 **/
$(window).on('load', function(){
  // 公開状況に応じてボタンのTextとCSS Classを設定
  setStatusBtnStyle($('.edit-sub-menu-status-btn').eq(0));
});

/**
 * EDIT MENU OPEN BTN
 **/
$('.edit-sub-menuOpenBtn').on('click', function(){
  $('.edit').toggleClass('isMenuClose');
  setEditPageMenuBtnTxt();
});
function setEditPageMenuBtnTxt () {
  if ($('.edit').hasClass('isMenuClose')) {
    $('.edit').find('.edit-sub-menuOpenBtn-txt').text('OPEN');
  }
  else {
    $('.edit').find('.edit-sub-menuOpenBtn-txt').text('CLOSE');
  }
}

/**
 * EDIT THEME SELECT MENU OPEN
 **/
$('#subMenuGroupPageConfig-themeSelect').on('click', function () {
  $('#edit-themeListWrap').fadeIn();
  $(this).addClass('isModalOpen');
});

/**
 * EDIT THEME SELECT MENU CLOSE
 **/
$('.edit-themeList-footer-closeBtn').on('click', function () {
  $('#edit-themeListWrap').fadeOut();
  $('#subMenuGroupPageConfig-themeSelect').removeClass('isModalOpen');
});

/**
 * EDIT STATUS BTN
 **/
$('.edit-sub-menu-status-btn').on('click', function(){
  // 公開状況を変更
  changeStatusValue();
  // 公開状況に応じてボタンのTextとCSS Classを設定
  setStatusBtnStyle($(this));
});

function changeStatusValue() {
  let selfStatusValue = $('#selfStatusValue');
  if (selfStatusValue.val() == 0) {
    selfStatusValue.val('1');
  }
  else {
    selfStatusValue.val('0');
  }
}
function setStatusBtnStyle(targetBtnObj) {
  let selfStatusValue = $('#selfStatusValue');
  let mainTxt = '';
  let subTxt = '';
  if (selfStatusValue.val() == 0) {
    targetBtnObj.removeClass('isStatus1');
    mainTxt = '公開する';
    subTxt = '（現在下書中）';
  }
  else {
    targetBtnObj.addClass('isStatus1');
    mainTxt = '公開しない';
    subTxt = '（現在公開中）';
  }
  targetBtnObj.find('.isMainTxt').text(mainTxt);
  targetBtnObj.find('.isSubTxt').text(subTxt);
}

/**
 * EDIT MENU BOX OPEN/CLOSE
 **/
$('.subMenuBox-title').on('click', function() {
  let objInputBlock = $(this).next('.subMenuBox-inputBlock');
  if(objInputBlock.length) {
    $(this).parents('.subMenuBox').toggleClass('isMenuBoxOpen');
    objInputBlock.slideToggle();
  }
});

/**
 * EDIT TEXT LENGTH
 **/
function setDispFieldForEditPageTxtMaxLength() {
  if (($('.edit-sub-menu .inputSet-input').length)) {
    const $txtBoxs = $('.edit-sub-menu .inputSet-input');
    $txtBoxs.each(function () {
      console.log('aaa');
      $(this).next('.inputSet-inputLength').find('.maxLength').text($(this).attr('maxlength'));
    });
  }
}


$('.edit-sub-menu .inputSet-input').on('keyup', function () {
  $(this).next('.inputSet-inputLength').find('.nowLength').text($(this).val().length);
});