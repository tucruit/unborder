<?php
/**
 * インスタントページユーザー 登録確認
 */
?>
<!-- PAGE CONTENTS -->
<div class="signup signupIndex">
	<div class="l-subContentsContainer sub-container signupInner">
		<h2 class="mod-hl-01 signup-hl">ユーザー登録</h2>
		<br>
		<?php if (Configure::read('debug') > 0): ?>
			<div class="u-text-center u-text u-text--fz16"><?php $this->BcBaser->flash() ?></div>
		<?php endif; ?>
		<?php if ($activate): ?>
			<p class="signup-lead">
				ご登録が完了しました。<br>
				ご登録頂いたメールアドレスに<br>
				『ユーザー登録完了のお知らせ』メールを発信しました。<br>
				ログインID・パスワードを保管してください。
			</p>

			<div class="u-flex u-mt-6 u-mt-md-9 u-mb-3">
				<div class="u-flex__inr u-jc-center">
					<?php if (isset($activate['InstantPageUser']['referer']) && $activate['InstantPageUser']['referer']): ?>
						<p><?php $this->BcBaser->link('引き続きダウンロード画面へ', $activate['InstantPageUser']['referer'], array('class' => 'button')); ?></p>
					<?php else: ?>
						<p><?php $this->BcBaser->link('ユーザー専用サイト', '/instant_pages/', array('class' => 'button')); ?></p>
					<?php endif ?>
				</div>
			</div>

		<?php else: ?>

			<p class="signup-lead">登録に失敗しました。<br>
			既に登録済みか、有効期限が過ぎています。</p>

		<?php endif ?>
		<p class="u-text-center u-text u-text--fz16 u-mt-1">お問い合わせは<a class="c-link-underline" href="/contact/">こちら</a>までご連絡下さい。</p>
	</div>
</div>
<!-- /PAGE CONTENTS -->
