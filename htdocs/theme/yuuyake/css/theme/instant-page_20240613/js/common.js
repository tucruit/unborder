/*
 *
 * @copyright    Copyright (c) hiniarata co.ltd
 * @link         https://hiniarata.jp
 * 
 * System Requirements: jQuery-3.6.0
 */

/**
 * 共通変数・定数定義
 **/
/********** media break points **********/
const mediaWidth_tabMin = 600;
const mediaWidth_tabMax = 1023;
const mediaWidth_pcMin = mediaWidth_tabMax + 1;
const mediaWidth_spMax = mediaWidth_tabMin - 1;
let windowWidth = $(window).innerWidth;

/**
 * 読み込み時処理
 **/
$(function() {
  /***** FLEX 左揃え対応 *****/
  addEmptyItemForFlex('.flexList', '.listItem', 'li');
  addEmptyItemForFlex('.top-recommend-caseBoxContainer', '.caseBox', 'section');
  /***** フォーム_ラジオボタンをspanで包む *****/
  if ( $('.mod-form-radio').next('label').length) {
    $('.mod-form-radio').next('label').addClass('radioLabel');
  }
  /***** フォーム_セレクトボックスをspnaで包む *****/
  if ($('.mod-form-select').length) {
    $('.mod-form-select').wrap('<span class="selectBox"></span>');
  }
});

/**
 * Load時処理
 **/
$(window).on('load', function(){
  /***** matchHeight *****/
  if ($('.matchHeight').length) {
    $('.matchHeight').matchHeight();
  }
  /***** スムーススクロール *****/
  smoothScrollforLoaded();
  /***** ページトップへ戻るボタン 非表示 *****/
  if ($('#pageTop').length) {
    $('#pageTop').hide();
  }
});

/**
 * scroll時処理
 **/
$(window).on('scroll', function () {
  let scrollHeight = $(document).height(); //ページの高さ
  let scrollPosition = $(window).height() + $(window).scrollTop(); //現在地
  /***** ヘッダー固定 *****/
  const fixedHeaderID = '#is-headerFixed'; // fixed DOM
  const scrolledHeaderClassName = 'scrolled'; // add css class
  const scrollY_min = 15; // 固定開始scroll位置
  if ($(fixedHeaderID).length) {
    if ($(window).scrollTop() > scrollY_min) {
      $(fixedHeaderID).addClass(scrolledHeaderClassName);
    } else {
      $(fixedHeaderID).removeClass(scrolledHeaderClassName);
    }
  }
  /***** ページトップへ戻るボタン *****/
  if ($('#pageTop').length) {
    if ($(this).scrollTop() > 100) {
      $('#pageTop').fadeIn('fast');
    } else {
      $('#pageTop').fadeOut('fast');
    }
    
    // let footHeight = $('.footer').innerHeight(); //停止位置
    let stopPosition = 30;
    if (isMobile) {
      stopPosition = 16;
    }
    if (scrollHeight - scrollPosition <= stopPosition - 10) { //ページの高さと現在地の差がfooterの高さ以下になったら
      $('#pageTop').css({
        'position': 'absolute',
        'bottom': stopPosition
      });
    } else {
      $('#pageTop').css({
        'position': 'fixed',
        'bottom': '10px'
      });
    }
  }

});

/**
 * 関数・クリック等イベント処理
 **/

/**
 * media query 判定関数
 * 各幅に該当する場合trueを返す
 **/
function isPc () {
  return window.matchMedia('screen and (min-width: ' + mediaWidth_pcMin + 'px)').matches;
}
function isTab () {
  return window.matchMedia('screen and (min-width: ' + mediaWidth_tabMin + 'px) and (max-width: ' + mediaWidth_tabMax + 'px)').matches;
}
function isSp () {
  return window.matchMedia('screen and (max-width: ' + mediaWidth_spMax + 'px').matches;
}
function isPcTab () {
  return window.matchMedia('screen and (min-width: ' + mediaWidth_tabMin + 'px)').matches;
}
function isMobile () {
  return window.matchMedia('screen and (max-width: ' + mediaWidth_tabMax + 'px)').matches;
}

/**
 * ページトップへ
 * 戻るボタン
 **/
$('#pageTop').on('click', function () {
  $('body,html').animate({
    scrollTop: 0
  }, 400);
  return false;
});

/**
 * スムーススクロール
 **/
//通常のクリック時
$('a[href*="#"]').on('click', function () {
  //ページ内リンク先を取得
  let href = $(this).attr("href");
  let array = href.split('#');
  //リンク先が#か空だったらhtmlに
  let hash = 'html';
  if (array[1]) {
    hash = '#' + array[1]
  }
  //スクロール実行
  scrollToAnker(hash);
  //リンク無効化
  return false;
});

// 関数：スムーススクロール
// 指定したアンカー(#ID)へアニメーションでスクロール
function scrollToAnker(hash) {
  let target = $(hash);
  let headerHeight = $('.header').outerHeight();
  // let headerHeight = 0;
  let position = target.offset().top - headerHeight;
  $('body,html').stop().animate({
    scrollTop: position
  }, 800);
}

// load 用処理
function smoothScrollforLoaded() {
  //URLのハッシュ値を取得
  let urlHash = location.hash;
  //ハッシュ値があればページ内スクロール
  if (urlHash) {
    if ($(urlHash).length) {
      //スクロールを0に戻す
      $('body,html').stop().scrollTop(0);
      setTimeout(function () {
        //ロード時の処理を待ち、時間差でスクロール実行
        scrollToAnker(urlHash);
      }, 100);
    }
  }
};

/**
 * FLEX 左揃え対応
 **/
function addEmptyItemForFlex (containerClass, itemClass, addElementType) {
  let itemClassName = itemClass.slice(1);
  if (($(containerClass).length)) {
    const $container = $(containerClass);
    $container.each(function () {
      let emptyCells = [],i;
      for (i = 0; i < $(this).find(itemClass).length; i++) {
        emptyCells.push($('<' + addElementType + '>', { class: itemClassName + ' isEmpty' }));
      }
      $(this).append(emptyCells);
    });
  }
}