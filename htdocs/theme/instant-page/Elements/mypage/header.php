<?php
/*
 * mypage header
 */
// ログインユーザーの取得
$user = $this->Session->read('Auth');
$instantPageUser = !empty($user['Admin']) ? $this->Theme->getInstantPageUser($user['Admin']['id']) : [];
if (empty($instantPageUser)) {
	include __DIR__ . DS . '../header.php';
} else {
	$planIds =  Configure::read('InstantPage.plan_id');
?>
<!-- HEADER -->
<header role="banner" class="header" id="is-headerFixed">
	<div class="headerInner">
		<!-- LOGO -->
		<a href="/" class="header-Logo">
			<h1 class="hdnTxt">ランディングページ制作支援ツール インスタントページ</h1>
			<img src="/my_page/img/common/logo.svg" alt="ランディングページ制作支援ツール インスタントページ" class="imgFit">
		</a>
		<!-- /LOGO -->
		<div class="header-itemGroup">
			<!-- PLAN -->
			<?php if ($instantPageUser['plan_id'] == 1): ?>
				<div class="header-plan isFree">
					<span class="header-plan-txt">無料プラン</span>
				</div>
			<?php elseif ($instantPageUser['plan_id'] == 2):?>
				<div class="header-plan isRegular">
					<span class="header-plan-txt">有料プラン</span>
				</div>
			<?php else:?>
				<div class="header-plan isBusiness">
					<span class="header-plan-txt">ビジネス</span>
				</div>
			<?php endif;?>
			<!-- /PLAN -->
			<!-- MOBILE MENU BUTTON -->
			<div id="header-mobileMenuBtn">
				<div class="header-mobileMenuBtnInner">
					<span>&thinsp;</span>
					<span>&thinsp;</span>
					<span>&thinsp;</span>
				</div>
			</div>
			<!-- /MOBILE MENU BUTTON -->
		</div>
		<!-- NAVIGATION -->
		<nav role="navigation" class="gNav">
			<div class="gNavInner">
				<ol class="gNav-list">
					<li>
						<?php
						if (!empty($instantPageUser)) {
							$this->BcBaser->link('マイページ', '/cmsadmin/instant_page/instant_pages/', ['class' => 'menuTitle']);
						}
						?>
					</li>
					<li>
						<?php
						if (!empty($instantPageUser)) {
							$this->BcBaser->link('登録者情報', '/cmsadmin/instant_page/instant_page_users/edit/'. $instantPageUser['id'], ['class' => 'menuTitle']);
						}
						?>
					</li>
					<?php if (!empty($instantPageUser) && $instantPageUser['creator_flg']) : ?>
						<li>
							<?php
							$this->BcBaser->link('テーマ管理', '/cmsadmin/instant_page/instant_page_templates/', ['class' => 'menuTitle']);
							?>
						</li>
					<?php endif;?>

					<li>
						<?php
						 if ($this->Session->check('AuthAgent')) {
						 	// /users/back_agent
						 	$this->BcBaser->link(__d('baser', '元のユーザーに戻る'), ['admin' => false, 'plugin' => null, 'controller' => 'users', 'action' => 'back_agent'], ['class' => 'menuTitle']);
						 } elseif (!empty($instantPageUser)) {
							$this->BcBaser->link('ログアウト', '/cmsadmin/users/logout', ['class' => 'menuTitle']);
						}
						?>
					</li>
				</ol>
			</div>
		</nav>
		<!-- /NAVIGATION -->
	</div>
</header>
<!-- /HEADER -->
<?php
}
