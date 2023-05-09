<?php
/**
 * [MYPAGE] ログイン
 */
$this->layout = 'mypage_login';
$userModel = Configure::read('BcAuthPrefix.' . $currentPrefix . '.userModel');
if (!$userModel) {
	$userModel = 'User';
}
list(, $userModel) = pluginSplit($userModel);
$userController = Inflector::tableize($userModel);
?>
<div id="UserModel" hidden><?php echo $userModel ?></div>
<div class="l-subContentsContainer sub-container usersInner">
	<?php $this->BcBaser->flash() ?>
	<?php echo $this->BcForm->create($userModel, ['url' => ['action' => 'login'], 'class' => 'users-form']) ?>
		<h2 class="users-sectionHl users-form-hl">
			<span class="noWrap">メールアドレスとパスワードを</span><span class="noWrap">入力して、ログインしてください。</span>
		</h2>
		<div class="users-form-inputBlock">
			<div class="inputBlock-inputGroup">
				<?php echo $this->BcForm->label($userModel . '.name', __d('baser', 'アカウント名'), ['class' => 'inputBlock-inputGroup-label']) ?>
				<?php echo $this->BcForm->input($userModel . '.name', ['type' => 'text', 'div' => ['tag' => false], 'required' => 'required', 'tabindex' => 1, 'autofocus' => true, 'class' => 'mod-form-input-text inputBlock-inputGroup-txtBox']) ?>
			</div>
			<div class="inputBlock-inputGroup">
				<?php echo $this->BcForm->label($userModel . '.password', __d('baser', 'パスワード'), ['class' => 'inputBlock-inputGroup-label']) ?>
				<?php echo $this->BcForm->input($userModel . '.password', ['type' => 'password', 'div' => ['tag' => false], 'tabindex' => 2, 'class' => 'mod-form-input-text inputBlock-inputGroup-txtBox']) ?>
			</div>
			<?php
			if ($currentPrefix == 'front'){
				$this->BcBaser->link(__d('baser', 'パスワードをお忘れの方はこちら＞'), ['action' => 'reset_password'], ['class' => 'inputBlock-linkForgotPass']);
			} else {
				$this->BcBaser->link(__d('baser', 'パスワードをお忘れの方はこちら＞'), ['action' => 'reset_password', $this->request->params['prefix'] => true], ['class' => 'inputBlock-linkForgotPass']);
			}
			?>
			<div class="mod-btn-01 inputBlock-loginBtn">
				<span class="btnInner">ログイン</span>
				<?php echo $this->BcForm->button(__d('baser', 'ログイン'), ['type' => 'submit', 'div' => false, 'class' => 'bca-btn--login bca-btn', 'data-bca-btn-type' => 'login', 'id' => 'BtnLogin', 'tabindex' => 4]) ?>

			</div>
		</div>
	<?php echo $this->BcForm->end() ?>
	<div class="usersLogin-signup">
		<h2 class="users-sectionHl usersLogin-signup-hl">まだご登録がお済みでない方</h2>
		<?php $this->BcBaser->link('<span class="btnInner">新規登録</span>', '/signup/', ['class' => 'mod-btn-03 usersLogin-signup-btn']) ?>
	</div>
</div>
