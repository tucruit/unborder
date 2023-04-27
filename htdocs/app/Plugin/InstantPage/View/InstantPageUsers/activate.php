<div class="l-contents__inr">
	<div class="c-section">
		<div class="c-box c-box--a c-box--bg p-login">
			<h3 class="c-head c-head--b u-text-center u-mb-3">ユーザー登録</h3>

			<div class="u-text-center u-text u-text--fz16"><?php $this->BcBaser->flash() ?></div>

			<?php if ($activate): ?>
				<p class="u-text-center u-text u-text--fz16">
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
							<p><?php $this->BcBaser->link('ユーザー専用サイト', '/partner/', array('class' => 'button')); ?></p>
						<?php endif ?>
					</div>
				</div>

			<?php else: ?>

				<p class="u-text-center u-text u-text--fz16">登録に失敗しました。</p>
				<p class="u-text-center u-text u-text--fz16">既に登録済みか、有効期限が過ぎています。</p>

			<?php endif ?>
		</div>
			<p class="u-text-center u-text u-text--fz16 u-mt-1">お問い合わせは<a class="c-link-underline" href="/inquiry/">こちら</a>までご連絡下さい。</p>
	</div>
</div>
