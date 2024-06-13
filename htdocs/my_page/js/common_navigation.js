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
  // if ($(gNavClass).length) {
  //   if (timerForMobileMenu !== false) {
  //     clearTimeout(timerForMobileMenu);
  //   }
  //   timerForMobileMenu = setTimeout(function () {
  //     if (currentWidth == window.innerWidth) {
  //       // ウインドウ横幅が変わっていないため処理をキャンセル。
  //       return;
  //     }
  //     closeMobileMenue();
  //     currentWidth = window.innerWidth;
  //   }, 100);
  // }
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
}