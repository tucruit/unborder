<?php
/*
 *
 */
// ログインユーザーの取得
$user = $this->Session->read('Auth');
$instantPageUser = !empty($user['Admin']) ? $this->Theme->getInstantPageUser($user['Admin']['id']) : [];
?>
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
					<?php
					$text = 'ログイン';
					$href = '/instant_page/instant_page_users/login';
					if (!empty($instantPageUser)) {
						$text = 'マイページ';
						$href = '/cmsadmin/instant_page/instant_pages/';
						// $user = BcUtil::loginUser(); システムユーザー
						// '/users/back_agent', '元のユーザーに戻る'
					}
					?>
					<a href="<?php echo $href ?>" class="gNav-btn gNav-btn__login" style="width: 130px;">
						<span class="btnInner"><?php echo $text ?></span>
						<button type="submit" data-bca-btn-type="login" id="HeaderBtnLogin"><?php echo $text ?></button>
					</a>
					<?php
					if (!empty($instantPageUser)) {
						$this->BcBaser->link('<span class="btnInner">登録情報変更</span>', '/cmsadmin/instant_page/instant_page_users/edit/'. $instantPageUser['id'], ['class' => 'gNav-btn gNav-btn__signUp', 'style' => 'width:130px;' ]);
					} else {
						$this->BcBaser->link('<span class="btnInner">新規登録</span>', '/signup/', ['class' => 'gNav-btn gNav-btn__signUp']);
					}
					?>
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
						<a href="/privacy" class="menuTitle">
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
