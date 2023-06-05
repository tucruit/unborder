<?php
/**
 * [ADMIN] パスワードリセット画面
 */
$this->layout = 'InstantPage.mypage_login';
$this->BcBaser->setTitle('パスワードの再設定');
$userModel = Configure::read('BcAuthPrefix.' . $currentPrefix . '.userModel');
if (!$userModel) {
	$userModel = 'User';
}
$form_attr = ['action' => 'send_activate_url'];
if ($currentPrefix !== 'front') {
	$form_attr[$this->request->params['prefix']] = true;
}
?>
<div class="users usersSendActivateUrl">
	<div class="l-subContentsContainer sub-container usersInner">
		<?php
		if ($currentPrefix == 'front') {
			echo $this->BcForm->create($userModel, ['url' => ['controller' => 'instant_page_users', 'action' => 'reset_password', $this->request->params['prefix'] => true], 'class' => 'users-form']);
		} else {
			echo $this->BcForm->create($userModel, ['url' => $form_attr, 'class' => 'users-form']);
		}
		?>
			<h2 class="users-sectionHl users-form-hl">
				<span class="noWrap">パスワードを再設定します。</span>
			</h2>
			<p class="users-form-lead">
				ご登録されているメールメールアドレスを入力してください。<br class="dn-pc dn-sp">パスワード再設定用のメールが送信されます。
			</p>
			<div class="users-form-inputBlock">
				<div class="inputBlock-inputGroup">
					<label for="UserName" class="inputBlock-inputGroup-label">メールアドレス</label>
					<?php echo $this->BcForm->input($userModel . '.email', ['type' => 'text', 'div' => ['tag' => false], 'class' => 'mod-form-input-text inputBlock-inputGroup-txtBox', 'required' => 'required']) ?>
				</div>
				<div class="mod-btn-01 inputBlock-loginBtn">
					<span class="btnInner">送信</span>
					<?php echo $this->BcForm->submit(__d('baser', '送信'), array('div' => false, 'class' => 'btn-red button bca-btn', 'data-bca-btn-status' => 'warning')) ?>
				</div>
			</div>
		<?php echo $this->BcForm->end() ?>
	</div>
</div>
