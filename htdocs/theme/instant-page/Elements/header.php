<?php
/*
 *
 */
// ログインユーザーの取得
$user = $this->Session->read('Auth');
$instantPageUser = !empty($user['Admin']) ? $this->Theme->getInstantPageUser($user['Admin']['id']) : [];
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
		<nav role="navigation" class="gNav isUnder">
			<div class="gNavInner">
				<div class="gNav-btnGroup">
					<!-- CMS実装時用 -->
					<!-- <div class="gNav-btn gNav-btn__login">
					<span class="btnInner">ログイン</span>
					<button type="submit" data-bca-btn-type="login" id="HeaderBtnLogin">ログイン</button>
					</div> -->
					<!-- /CMS実装時用 -->
					<!-- Static閲覧用 -->
					<?php
					$text = 'ログイン';
					$href = '/instant_page/instant_page_users/login';
					if (!empty($instantPageUser)) {
						$text = 'ログアウト';
						$href = '/cmsadmin/users/logout';
						// $user = BcUtil::loginUser(); システムユーザー
						// '/users/back_agent', '元のユーザーに戻る'
					}
					?>
					<a href="<?php echo $href ?>" class="gNav-btn gNav-btn__login">
						<span class="btnInner"><?php echo $text ?></span>
						<button type="submit" data-bca-btn-type="login" id="HeaderBtnLogin"><?php echo $text ?></button>
					</a>
					<!-- /Static閲覧用 -->
					<?php
					if (!empty($instantPageUser)) {
						$this->BcBaser->link('<span class="btnInner">ページ一覧</span>', '/cmsadmin/instant_page/instant_pages/', ['class' => 'gNav-btn gNav-btn__signUp']);
						$this->BcBaser->link('<span class="btnInner">登録情報編集</span>', '/cmsadmin/instant_page/instant_page_users/edit/'. $instantPageUser['id'], ['class' => 'gNav-btn gNav-btn__signUp']);
					} else {
						$this->BcBaser->link('<span class="btnInner">新規登録</span>', '/signup/', ['class' => 'gNav-btn gNav-btn__signUp']);
					}
					?>
				</div>
				<ol class="gNav-list">
					<li>
						<a href="#" class="menuTitle">
							サービス紹介
						</a>
					</li>
					<li>
						<a href="/index#top-features" class="menuTitle">
							機能紹介
						</a>
					</li>
					<li>
						<a href="/index#top-plan" class="menuTitle">
							プラン紹介
						</a>
					</li>
					<li>
						<a href="news.html" class="menuTitle">
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
					<img src="/img/common/character.svg" alt="" class="">
				</div>
			</div>
		</nav>
		<!-- /NAVIGATION -->
	</div>
</header>
<!-- /HEADER -->
