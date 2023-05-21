<header role="banner" class="header" id="is-headerFixed">
	<div class="headerInner">
		<!-- LOGO -->
		<a href="/" class="header-Logo">
			<?php if($this->BcBaser->isHome() || $this->BcBaser->getContentsName() == "Home"): ?>
				<h1 class="hdnTxt">ランディングページ制作支援ツール インスタントページ</h1>
			<?php endif; ?>
			<img src="<?php $this->BcBaser->themeUrl(); ?>img/common/logo.svg" alt="ランディングページ制作支援ツール インスタントページ" class="imgFit">
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
		<nav role="navigation" class="gNav isUnder">
			<div class="gNavInner">
				<div class="gNav-btnGroup">
					<div class="gNav-btn gNav-btn__login">
						<span class="btnInner">ログイン</span>
						<button type="submit" data-bca-btn-type="login" id="HeaderBtnLogin">ログイン</button>
					</div>
					<a href="#" class="gNav-btn gNav-btn__signUp">
						<span class="btnInner">新規登録</span>
					</a>
				</div>
				<ol class="gNav-list">
					<?php
					/* 
					<li>
						<a href="#" class="menuTitle">
							サービス紹介
						</a>
					</li>
					*/
					?>
					<li>
						<a href="/#top-features" class="menuTitle">
							機能紹介
						</a>
					</li>
					<li>
						<a href="/#top-plan" class="menuTitle">
							プラン紹介
						</a>
					</li>
					<li>
						<a href="/news/" class="menuTitle">
							お知らせ
						</a>
					</li>
					<li class="dn-pc">
						<a href="#" class="menuTitle">
							プライバシーポリシー
						</a>
					</li>
					<li>
						<a href="/contact/" class="menuTitle">
							お問い合わせ
						</a>
					</li>
				</ol>
				<div class="gNav-footerImg dn-pc">
					<img src="<?php $this->BcBaser->themeUrl(); ?>img/common/character.svg" alt="" class="">
				</div>
			</div>
		</nav>
		<!-- /NAVIGATION -->
	</div>
</header>