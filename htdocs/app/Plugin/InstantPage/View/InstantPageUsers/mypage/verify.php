<?php
/**
 * [ADMIN] パスワードリセット画面
 */
$this->layout = 'mypage_login';
$userModel = Configure::read('BcAuthPrefix.' . $currentPrefix . '.userModel');
if (!$userModel) {
	$userModel = 'User';
}
list(, $userModel) = pluginSplit($userModel);
$userController = Inflector::tableize($userModel);
$userModel = Configure::read('BcAuthPrefix.' . $currentPrefix . '.userModel');
if (!$userModel) {
	$userModel = 'User';
}
?>

<div class="l-contents__inr">
	<div class="c-section">
		<?php
		if ($currentPrefix == 'front') {
			echo $this->BcForm->create($userModel, ['url' => ['action' => 'reset_password']]);
		} else {
			echo $this->BcForm->create($userModel, ['url' => ['action' => 'reset_password', $this->request->params['prefix'] => true]]);
		}
		?>
			<div class="c-box c-box--a c-box--bg p-login">
				<h3 class="c-head c-head--b u-text-center u-mb-3">パスワードリセット申込</h3>
				<p class="u-text-center u-text u-text--fz16">パスワードを忘れる、期限切れ等で<br class="u-only-sp">パスワードの再設定をされる場合は<br>下記のフォームにIDもしくは<br class="u-only-sp">メールアドレスをご入力の上、<br>メールに送信される再設定URLより<br class="u-only-sp">パスワードの再設定をして下さい。</p>
				<table class="p-login__table u-mt-5">
					<tr>
						<th>
							<label>ID（メールアドレス）</label>
						</th>
						<td>
							<?php echo $this->BcForm->input($userModel . '.email', array('type' => 'text', 'class' => 'c-input')) ?>
						</td>
					</tr>
				</table>
			</div>
			<div class="u-flex u-mt-6 u-mt-md-9 u-mb-3">
				<div class="u-flex__inr u-jc-center">
					<div class="c-btn c-btn--sm2"><a href="/instant_page/instant_page_users/login">ログイン画面へ戻る<i class="c-icon c-icon--arrow rev"></i></a></div>
					<div class="c-btn c-btn--input c-btn--sm2">
						<?php echo $this->BcForm->submit(__d('baser', '送信'), array('div' => false, 'class' => 'btn-red button bca-btn', 'data-bca-btn-status' => 'warning')) ?><i class="c-icon c-icon--arrow"></i>
					</div>
				</div>
			</div>
		<?php echo $this->BcForm->end() ?>
		<p class="u-text-center u-text u-text--fz16 u-mt-1">お問い合わせは<a class="c-link-underline" href="/inquiry/">こちら</a>までご連絡下さい。</p>
	</div>
</div>
