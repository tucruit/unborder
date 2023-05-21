/*
 *
 * @copyright    Copyright (c) hiniarata co.ltd
 * @link         https://hiniarata.jp
 * 
 * System Requirements: jQuery-3.6.0
 */

$(function () {

});

/**
 * モバイルメニュー
 **/
// ナビゲーションCSS Class
const gNavClass = '.gNav';
// header下レイアウトナビゲーションCSS Class
const gNavUnder = gNavClass + '.isUnder';
let timerForMobileMenu = false;
let currentWidth = window.innerWidth;

$(window).on('load', function(){
  // resetNavi();
});

$(window).on('resize', function () {
  if ($(gNavClass).length) {
    if (timerForMobileMenu !== false) {
      clearTimeout(timerForMobileMenu);
    }
    timerForMobileMenu = setTimeout(function () {
      if (currentWidth == window.innerWidth) {
        // ウインドウ横幅が変わっていないため処理をキャンセル。
        return;
      }
      closeMobileMenue();
      // resetNavi();
      currentWidth = window.innerWidth;
    }, 100);
  }
});

$(document).on('click', '#header-mobileMenuBtn', function () {
  controlMobileMenueOpenMode();
});
$('.gNav-list-closeBtn').on('click', function () {
  controlMobileMenueOpenMode();
});

$('.gNav-list a').on('click', function () {
  closeMobileMenue();
});

$('.withAccordionMenu__02').on('click', function (event) {
  if (isMobile()) {
    $(this).find('.accordionMenu__02').slideToggle(400, 'swing');
    $(this).toggleClass('isAccordionOpen');
  }
  event.stopPropagation();
});

$('.withAccordionMenu__03').on('click', function (event) {
  if (isMobile()) {
    $(this).find('.accordionMenu__03').slideToggle(400, 'swing');
    $(this).toggleClass('isAccordionOpen');
  }
  event.stopPropagation();
});

function resetNavi () {
  if ($(gNavClass).length) {
    $(gNavUnder).css('top', '');
    $(gNavUnder).css('height', '');
    // header下レイアウトナビゲーション 高さ、上位置設定
    if ($(gNavUnder).length) {
      $(gNavUnder).find('.gNavInner').css('height', 'auto');
      if (isMobile()) {
        $(gNavUnder).css('top', $('header').innerHeight());
        $(gNavUnder).find('.gNavInner').css('height', $(window).innerHeight() - $('header').innerHeight());
      }
    }
  }
}
function controlMobileMenueOpenMode() {
  $('.header').toggleClass('isMenuOpen');
  $('#header-mobileMenuBtn').toggleClass('isMenuOpen');
  $('.gNav').fadeToggle(400);
  $('.gNav').toggleClass('isOpen');
}
function closeMobileMenue() {
  $('.header').removeClass('isMenuOpen');
  $('#header-mobileMenuBtn').removeClass('isMenuOpen');
  $('.gNav').hide();
  $('.gNav').removeClass('isOpen');
  $('.accordionMenu__02').css('display', '');
  $('.accordionMenu__03').css('display', '');
  $('.withAccordionMenu__02').removeClass('isAccordionOpen');
  $('.withAccordionMenu__03').removeClass('isAccordionOpen');
}