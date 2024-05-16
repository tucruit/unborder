/*
 * System Requirements: jQuery-1.11.2
 */
$(function () {

/*
 * 定数宣言
 */
// アニメーションの発火地点（要素の前後何pxで発火させるか）
const triggerPoint_top = 50;
// .animationGroup子要素のアニメーションディレイタイム
const fadeAnimationDelay = 0.2;
// 各アニメーションクラスの指定
const arryAnimationClassNames = [ 
  'fadeIn',
  'fadeUp',
  'fadeDown',
  'fadeLeft',
  'fadeRight',
  'blur',
  'bgextendInner',
  'bgextend-lr',
  'bgextend-rl',
  'bgextend-bt',
  'bgextend-tb'
];
// スクロールで発火するClassのクラス名末尾
const triggerClassName = 'Trigger';

/*
 * アニメーションのコントロール処理
 */
function doAnimation(){
  for(let i = 0; i < arryAnimationClassNames.length; i++) {
    let animationClassName = arryAnimationClassNames[i];
    let targetClassNames = animationClassName + triggerClassName;
    $('.' + targetClassNames).each(function(){
      var elemPos = $(this).offset().top + triggerPoint_top;
      var scroll = $(window).scrollTop();
      var windowHeight = $(window).height();
      if (scroll >= elemPos - windowHeight){
        $(this).addClass(animationClassName);
      }else{
        $(this).removeClass(animationClassName);
      }
    });
  }
}

/*
 * .animationGroup内子要素のディレイ設定処理
 */
function setAnimationDelay() {
  $('.animationGroup').each(function(){
    for(let i = 0; i < arryAnimationClassNames.length; i++) {
      let animationClassName = arryAnimationClassNames[i];
      let targetClassNames = animationClassName + triggerClassName;
      let delayTime = '0s';
      let j = 0;
      let k = 0;
      $(this).find('.' + targetClassNames).each(function(){
        delayTime = String(fadeAnimationDelay * j) + 's';
        $(this).css('animation-delay', delayTime);
        j++;
      });
      $(this).find('.' + animationClassName).each(function(){
        delayTime = String(fadeAnimationDelay * k) + 's';
        $(this).css('animation-delay', delayTime);
        k++;
      });
    }
  });
}

/*
 * .parallaxBlock mobile 用JS
 */
function mobileParallax () {
  let targetClass = $(".parallaxBlock");
  if (window.matchMedia('(max-width:799px)').matches) {
    let objThisTarget;
    for (i=0; i < targetClass.length; i++) {
      objThisTarget = targetClass.eq(i);
      let targetPosOT1 = objThisTarget.offset().top;
      let targetFactor = 1;
      let windowH = $(window).innerHeight();
      let scrollYStart1 = targetPosOT1 - windowH;
      let scrollYEnd1 = scrollYStart1 + objThisTarget.height();
      let scrollY = $(window).scrollTop();

      objThisTarget.css('background-position-y','0px');

      if (scrollY >= scrollYEnd1) {
        objThisTarget.css('background-position-y','0px');
      }
      else if (scrollY >= scrollYStart1) {
        objThisTarget.css('background-position-y', (scrollY - scrollYStart1 - objThisTarget.height()) + 'px');
      }
    }
  }
  else {
    targetClass.css('background-position-y', '0px');
  }
}

/*
 * 各イベント時処理
 */
$(window).on('load', function(){
  // 印刷時除外
  if (!window.matchMedia('print').matches) {
    setAnimationDelay();
    doAnimation();
    mobileParallax();
  }
});

$(window).on('resize', function(){
  // 印刷時除外
  if (!window.matchMedia('print').matches) {
    doAnimation();
    mobileParallax();
  }
});


$(window).on('scroll', function (){
  // 印刷時除外
  if (!window.matchMedia('print').matches) {
    doAnimation();
    mobileParallax();
  }
});

  //end of jQuery
});
