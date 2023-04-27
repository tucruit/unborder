<?php
/**
 * [InstatntPage] ユーザー 設定 追加／編集
 */
$data = $this->request->data;
if(!isset($data['InstantPageUser'])) {
	header('Location: /mypage/instant_page/instant_page_users/login');
	exit;
}
$this->layout = 'mypage_login';
$userModel = Configure::read('BcAuthPrefix.' . $currentPrefix . '.userModel');
if (!$userModel) {
	$userModel = 'User';
}
$this->BcBaser->i18nScript([
	'alertMessage1' => __d('baser', '処理に失敗しました。'),
	'alertMessage2' => __d('baser', '送信先のプログラムが見つかりません。'),
	'confirmMessage1' => __d('baser', '更新内容をログイン情報に反映する為、一旦ログアウトします。よろしいですか？'),
]);
?>

<div id="AlertMessage" style="display: none"></div>
<div id="UserModel" hidden><?php echo $userModel ?></div>
<div id="LoginCredit" hidden><?php echo $this->BcBaser->siteConfig['login_credit'] ?></div>
	<?php $this->BcBaser->flash() ?>
	<?php echo $this->BcForm->create($userModel, ['url' => ['action' => 'mypage_edit_password']]) ?>
<?php echo $this->BcFormTable->dispatchBefore() ?>

<?php //echo $this->BcForm->create('InstantPageUser',  array('url' => array('action' => 'edit_password'))) ?>
<?php echo $this->BcForm->hidden('InstantPageUser.id') ?>
<?php echo $this->BcForm->unlockField('dummypass') ?>
<div class="l-contents__inr">
	<div class="c-section">
			<div class="c-box c-box--a c-box--bg p-login">
				<h3 class="c-head c-head--b u-text-center u-mb-3">パスワード編集</h3>
				<p class="u-text-center u-text u-text--fz16">
					<span hidden><?php echo $this->BcForm->label('InstantPageUser.real_name_1', 'ご担当者名') ?>:</span>
					<?php echo $this->BcForm->input('InstantPageUser.real_name_1', ['type' => 'text', 'size' => 20, 'maxlength' => 255, 'autofocus' => true, 'readonly' => 'readonly']) ?> 様
				</p>
				<table class="p-login__table u-mt-5">
					<tr>
						<th>
							<?php echo $this->BcForm->label('InstantPageUser.password_1', __d('baser', 'パスワード')) ?>
						</th>
						<td>
							<input type="password" name="dummypass" style="top:-100px;left:-100px;position:fixed;" />
							<?php echo $this->BcForm->input('InstantPageUser.password_1', ['type' => 'password', 'size' => 20, 'maxlength' => 255, 'class' => 'c-input']) ?>
							<i class="bca-icon--question-circle btn help bca-help"></i>
							<?php echo $this->BcForm->error('InstantPageUser.password') ?>
							<div id="helptextPassword" class="helptext">
								<ul>
									<li><?php echo __d('baser', '確認の為２回入力してください。') ?></li>
								</ul>
							</div>
						</td>
					</tr>
					<tr>
						<th>
							<?php echo $this->BcForm->label('InstantPageUser.password_2', __d('baser', 'パスワード（確認用）')) ?>
						</th>
						<td>
							<?php echo $this->BcForm->input('InstantPageUser.password_2', ['type' => 'password', 'size' => 20, 'maxlength' => 255, 'class' => 'c-input']) ?>
							<?php echo $this->BcForm->error('InstantPageUser.password') ?>
						</td>
					</tr>
				</table>
				<?php echo $this->BcForm->dispatchAfterForm() ?>
			</div>
			<div class="u-flex u-mt-6 u-mt-md-9 u-mb-3">
				<div class="u-flex__inr u-jc-center">
					<div class="c-btn c-btn--sm2"><a href="/mypage/instant_page/instant_page_users/login">ログイン画面へ戻る<i class="c-icon c-icon--arrow rev"></i></a></div>
					<div class="c-btn c-btn--input c-btn--sm2">
						<?php echo $this->BcForm->submit(__d('baser', '送信'), array('div' => false, 'class' => 'btn-red button bca-btn', 'data-bca-btn-status' => 'warning')) ?><i class="c-icon c-icon--arrow"></i>
					</div>
				</div>
			</div>
		<p class="u-text-center u-text u-text--fz16 u-mt-1">お問い合わせは<a class="c-link-underline" href="/inquiry/">こちら</a>までご連絡下さい。</p>
	</div>
</div>
<?php echo $this->BcForm->end() ?>
