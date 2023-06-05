<?php
/**
 * [ADMIN] パスワード再生成画面
 */
$this->layout = 'InstantPage.mypage';
$this->BcBaser->setTitle('パスワードの再設定');
?>
<div role="main" class="myPage">
	<h1 class="mod-hl-pageTitle">新しいパスワードを発行しました。</h1>
	<div class="l-container l-contentsContainer myPageInner">
		<h2 class="mod-hl-02">新しいパスワード : <?= $this->get('new_password') ?></h2>
		<p class="registrationInfo-form-lead">
			任意のパスワードを設定したい場合は
			<?php
			$this->BcBaser->link(
				__d('baser', '登録者情報 設定'),
				[
					SessionHelper::read('Auth.Admin.id'),
					'admin' => true,
					'plugin' => 'instant_page',
					'controller' => 'instant_page_users',
					'action' => 'edit'
				]
			);
			?>で変更してください。
		</p>
		<div class="myPage-siteTableWrap">
			<p class="myPage-siteTable">
				<?php
				$this->BcBaser->link(
					'<span class="btnInner">登録者情報 設定</span>',
					[
						SessionHelper::read('Auth.Admin.id'),
						'admin' => true,
						'plugin' => 'instant_page',
						'controller' => 'instant_page_users',
						'action' => 'edit'
					],
					['class' => 'myPage-siteTable-applicationStatus', 'escape' => false],
				);
				?>
			</p>
		</div>
	</div>
</div>
