<?php
/*
 *
 */
// ログインユーザーの取得
$InstantPageUser = $this->Session->read('Auth.InstantPageUser');
?>
<!-- HEADER -->
<header role="banner" class="header" id="is-headerFixed">
	<div class="headerInner">
		<!-- LOGO -->
		<a href="/" class="header-Logo">
			<h1 class="hdnTxt">ランディングページ制作支援ツール インスタントページ</h1>
			<img src="/img/common/logo.svg" alt="ランディングページ制作支援ツール インスタントページ" class="imgFit">
		</a>
		<!-- /LOGO -->
		<!-- MOBILE MENU BUTTON -->
		<div id="header-mobileMenuBtn" class="dn-pc">
			<div class="header-mobileMenuBtnInner">
				<span>&thinsp;</span>
				<span>&thinsp;</span>
				<span>&thinsp;</span>
			</div>
		</div>
		<!-- /MOBILE MENU BUTTON -->
		<!-- NAVIGATION -->
		<nav role="navigation" class="gNav isUnder isSlide isTop">
			<div class="gNavInner">
				<div class="gNav-btnGroup">
					<div class="gNav-btn gNav-btn__login">
						<?php
						$text = 'ログイン';
						$href = '/instant_pages/';
						if (!empty($InstantPageUser)) {
							$text = 'ログアウト';
							$href = '/mypage/instant_page/instant_page_users/logout';
							// $user = BcUtil::loginUser(); システムユーザー
							// '/users/back_agent', '元のユーザーに戻る'
						}
						?>
						<span class="btnInner"><?php echo $text ?></span>
						<button type="submit" data-bca-btn-type="login" id="BtnLogin" onclick="location.href='<?php echo $href ?>'"><?php echo $text ?></button>
					</div>
					<a href="#" class="gNav-btn gNav-btn__signUp">
						<span class="btnInner">新規登録</span>
					</a>
				</div>
				<ol class="gNav-list">
					<li><a href="#" class="menuTitle">サービス紹介</a></li>
					<li><a href="#" class="menuTitle">機能紹介</a></li>
					<li><a href="#" class="menuTitle">プラン紹介</a></li>
					<li><a href="#" class="menuTitle">お知らせ</a></li>
					<li class="dn-pc"><a href="#" class="menuTitle">プライバシーポリシー</a></li>
					<li><a href="#" class="menuTitle">お問い合わせ</a></li>
				</ol>
				<div class="gNav-footerImg dn-pc">
					<img src="/img/common/character.svg" alt="" class="">
				</div>
			</div>
		</nav>
		<!-- /NAVIGATION -->
	</div>
</header>
