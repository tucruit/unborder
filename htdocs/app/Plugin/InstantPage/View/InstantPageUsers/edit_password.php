<?php
/**
 * [InstatntPage] ユーザー 設定 追加／編集
 */
$data = $this->request->data;
if(!isset($data['InstantPageUser'])) {
	header('Location: /instant_page/instant_page_users/login');
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
	<?php $this->BcBaser->flash() ?>
	<?php echo $this->BcForm->create($userModel, ['url' => ['controller' => 'instant_page_users', 'action' => 'edit_password']]) ?>
	<?php echo $this->BcFormTable->dispatchBefore() ?>
	<?php echo $this->BcForm->hidden('InstantPageUser.id') ?>
	<?php echo $this->BcForm->hidden('User.id') ?>
	<?php echo $this->BcForm->hidden('User.user_group_id') ?>
	<?php echo $this->BcForm->unlockField('dummypass') ?>
<div class="l-contents__inr">
	<div class="l-subContentsContainer sub-container usersInner">
			<div class="c-box c-box--a c-box--bg p-login">
				<h3 class="c-head c-head--b u-text-center u-mb-3">パスワード編集</h3>
				<p class="u-text-center u-text u-text--fz16">
					<span hidden><?php echo $this->BcForm->label('User.real_name_1', 'ご担当者名') ?>:</span>
					<?php echo $this->BcForm->input('User.real_name_1', ['type' => 'text', 'size' => 20, 'maxlength' => 255, 'autofocus' => true, 'readonly' => 'readonly']) ?> 様
				</p>
				<table cellpadding="0" cellspacing="0" id="FormTable" class="form-table bca-form-table">
					<tr>
						<th class="col-head bca-form-table__label">
							<?php echo $this->BcForm->label('User.password_1', __d('baser', 'パスワード')) ?>
							<?php if ($this->request->action == 'admin_add'): ?>
								<span class="bca-label" data-bca-label-type="required"><?php echo __d('baser', '必須') ?></span>&nbsp;
							<?php endif; ?>
						</th>
						<td class="col-input bca-form-table__input">
							<?php if ($this->request->action == "admin_edit"): ?><small>[<?php echo __d('baser', 'パスワードは変更する場合のみ入力してください') ?>]</small><br /><?php endif ?>
							<!-- ↓↓↓自動入力を防止する為のダミーフィールド↓↓↓ -->
							<input type="password" name="dummypass" style="top:-100px;left:-100px;position:fixed;" />
							<?php echo $this->BcForm->input('User.password_1', ['type' => 'password', 'size' => 20, 'maxlength' => 255]) ?>
							<?php echo $this->BcForm->input('User.password_2', ['type' => 'password', 'size' => 20, 'maxlength' => 255]) ?>
							<i class="bca-icon--question-circle btn help bca-help"></i>
							<?php echo $this->BcForm->error('User.password') ?>
							<div id="helptextPassword" class="helptext">
								<ul>
									<li>
										<?php if ($this->request->action == "admin_edit"): ?>
											<?php echo __d('baser', 'パスワードの変更をする場合は、') ?>
										<?php endif; ?>
										<?php echo __d('baser', '確認の為２回入力してください。') ?></li>
										<li><?php echo __d('baser', '半角英数字(英字は大文字小文字を区別)とスペース、記号(._-:/()#,@[]+=&;{}!$*)のみで入力してください') ?></li>
										<li><?php echo __d('baser', '最低６文字以上で入力してください') ?></li>
									</ul>
								</div>
							</td>
						</tr>

				</table>
				<?php echo $this->BcForm->dispatchAfterForm() ?>
			</div>
			<div class="u-flex u-mt-6 u-mt-md-9 u-mb-3">
				<div class="u-flex__inr u-jc-center">
					<div class="mod-btn-01 signup-form-submit-send">
						<a href="/instant_page/instant_page_users/login">ログイン画面へ戻る</a>
					</div>
					<br>
					<div class="mod-btn-02 signup-form-submit-send">
						<span class="btnInner">保存</span>
						<?php echo $this->BcForm->submit(__d('baser', '送信'), array('div' => false, 'class' => 'btn-red button bca-btn', 'data-bca-btn-status' => 'warning')) ?>
					</div>
					<p class="u-text-center u-text u-text--fz16 u-mt-1">お問い合わせは<a class="c-link-underline" href="/inquiry/">こちら</a>までご連絡下さい。</p>
				</div>
			</div>
	</div>
</div>
<?php echo $this->BcForm->end() ?>
